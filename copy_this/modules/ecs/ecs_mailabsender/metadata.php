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
$sMetadataVersion = '1.1';
$aModule = array(
    'id'            => 'ecs_mailabsender',
    'title'         => '<strong style="color:#04B431;">e</strong><strong>ComStyle.de</strong>:  <i>Mailabsender</i>',
    'description'   => array(
                    'de' => '<i>Als Absender der Bestellmails wird der Name des Bestellers, als Absender der Kontaktformular-Nachrichten die Emailadresse des Absenders verwendet.</i>',
    ),
    'version'       => '1.3',
    'thumbnail'     => 'ecs.png',
    'author'        => '<strong style="font-size: 17px;color:#04B431;">e</strong><strong style="font-size: 16px;">ComStyle.de</strong>',
    'email'         => 'info@ecomstyle.de',
    'url'           => 'https://ecomstyle.de',
    'extend'        => array(
        'oxemail'     => 'ecs/ecs_mailabsender/mailabsender',
    ),
);
?>