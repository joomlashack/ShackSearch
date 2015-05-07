<?php
/**
* @copyright	Copyright (C) 2013 Johan Sundell. All rights reserved.
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';
$params = modPixSearchHelper::getParams( $params );
modPixSearchHelper::init( $params, $module->id );

$ver = new JVersion;
$ver = $ver->getShortVersion();

$version = $ver[0];

$moduleclass_sfx = htmlspecialchars( $params->get( 'moduleclass_sfx' ) );
require JModuleHelper::getLayoutPath( 'mod_pixsearch', $params->get( 'layout', 'default' ) );

