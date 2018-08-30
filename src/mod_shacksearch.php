<?php
/**
 * @package   ShackSearch
 * @author    Johan Sundell <labs@pixpro.net>
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright Copyright © You Rock AB 2003-2017 All Rights Reserved.
 * @copyright 2018 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of ShackSearch.
 *
 * ShackSearch is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * ShackSearch is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ShackSearch.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper.php';
$params = modShackSearchHelper::getParams($params);
modShackSearchHelper::init($params, $module->id);

$ver = new JVersion;
$ver = $ver->getShortVersion();

$version = $ver[0];

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_shacksearch', $params->get('layout', 'default'));

