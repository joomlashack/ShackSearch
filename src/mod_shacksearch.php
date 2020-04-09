<?php
/**
 * @package   ShackSearch
 * @author    Johan Sundell <labs@pixpro.net>
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2003-2017 You Rock AB All Rights Reserved.
 * @copyright 2018-2020 Joomlashack.com. All rights reserved
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

use Joomla\Registry\Registry;

defined('_JEXEC') or die();

/**
 * @var object    $module
 * @var array     $attribs
 * @var array     $chrome
 * @var string    $scope
 * @var Registry  $params
 * @var string    $template
 * @var string    $path
 * @var JLanguage $lang
 * @var string    $coreLanguageDirectory
 * @var string    $extensionLanguageDirectory
 * @var string[]  $langPaths
 * @var string    $content
 */

require_once __DIR__ . '/helper.php';

modShackSearchHelper::init($params, $module->id);

$ver = new JVersion;
$ver = $ver->getShortVersion();

$version = $ver[0];

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_shacksearch', $params->get('layout', 'default'));

