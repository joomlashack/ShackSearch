<?php
/**
* @copyright	Copyright ©2018 Joomlashack. All rights reserved.
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';
$params = modShackSearchHelper::getParams( $params );
modShackSearchHelper::init( $params, $module->id );

$ver = new JVersion;
$ver = $ver->getShortVersion();

$version = $ver[0];

$moduleclass_sfx = htmlspecialchars( $params->get( 'moduleclass_sfx' ) );
require JModuleHelper::getLayoutPath( 'mod_shacksearch', $params->get( 'layout', 'default' ) );

