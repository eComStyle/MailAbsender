<?php

/*    Please retain this copyright header in all versions of the software
*
*    Copyright (C) 2016  Josef A. Puckl | eComStyle.de
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see {http://www.gnu.org/licenses/}.
*/
class mailabsender extends mailabsender_parent {

	public function sendOrderEmailToOwner($oOrder, $sSubject = null) {
		$myConfig = $this->getConfig();
		$oShop = $this->_getShop();
		// cleanup
		$this->_clearMailer();
		// add user defined stuff if there is any
		$oOrder = $this->_addUserInfoOrderEMail($oOrder);
		$oUser = $oOrder->getOrderUser();
		$this->setUser($oUser);
		// send confirmation to shop owner
		// send not pretending from order user, as different email domain rise spam filters
		//ORGINAL: $this->setFrom( $oShop->oxshops__oxowneremail->value );
		//START:
		$sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();
		$this->setFrom($oUser->oxuser__oxusername->value, $sFullName);
		//END
		$oLang = oxRegistry::getLang();
		$iOrderLang = $oLang->getObjectTplLanguage();
		// if running shop language is different from admin lang. set in config
		// we have to load shop in config language
		if ($oShop->getLanguage() != $iOrderLang) {
			$oShop = $this->_getShop($iOrderLang);
		}
		$this->setSmtp($oShop);
		// create messages
		$oSmarty = $this->_getSmarty();
		$this->setViewData("order", $oOrder);
		// Process view data array through oxoutput processor
		$this->_processViewArray();
		$this->setBody($oSmarty->fetch($myConfig->getTemplatePath($this->_sOrderOwnerTemplate, false)));
		$this->setAltBody($oSmarty->fetch($myConfig->getTemplatePath($this->_sOrderOwnerPlainTemplate, false)));
		//Sets subject to email
		// #586A
		if ($sSubject === null) {
			if ($oSmarty->template_exists($this->_sOrderOwnerSubjectTemplate)) {
				$sSubject = $oSmarty->fetch($this->_sOrderOwnerSubjectTemplate);
			}
			else {
				$sSubject = $oShop->oxshops__oxordersubject->getRawValue() . " (#" . $oOrder->oxorder__oxordernr->value . ")";
			}
		}
		$this->setSubject($sSubject);
		$this->setRecipient($oShop->oxshops__oxowneremail->value, $oLang->translateString("order"));
		if ($oUser->oxuser__oxusername->value != "admin") {
			$sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();
			$this->setReplyTo($oUser->oxuser__oxusername->value, $sFullName);
		}
		$blSuccess = $this->send();
		// add user history
		$oRemark = oxNew("oxremark");
		$oRemark->oxremark__oxtext = new oxField($this->getAltBody(), oxField::T_RAW);
		$oRemark->oxremark__oxparentid = new oxField($oUser->getId(), oxField::T_RAW);
		$oRemark->oxremark__oxtype = new oxField("o", oxField::T_RAW);
		$oRemark->save();
		if ($myConfig->getConfigParam('iDebug') == 6) {
			oxRegistry::getUtils()->showMessageAndExit("");
		}
		return $blSuccess;
	}

	public function sendContactMail($sEmailAddress = null, $sSubject = null, $sMessage = null) {
		// shop info
		$oShop = $this->_getShop();
		//set mail params (from, fromName, smtp)
		$this->_setMailParams($oShop);
		$this->setBody($sMessage);
		$this->setSubject($sSubject);
		$this->setRecipient($oShop->oxshops__oxinfoemail->value, "");
		// Original: $this->setFrom($oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue());
		//START:
		$this->setFrom($sEmailAddress, "");
		//END
		$this->setReplyTo($sEmailAddress, "");
		return $this->send();
	}

}