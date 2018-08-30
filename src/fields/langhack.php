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
defined('JPATH_PLATFORM') or die;
JFormHelper::loadFieldClass('hidden');

class JFormFieldLangHack extends JFormFieldHidden
{
	protected $type = 'LangHack';

	public function __construct($form = null)
	{
		parent::__construct($form);

		JFactory::getLanguage()->load( 'com_search' );
	}

	protected function getInput()
	{
		return null;
	}

	public function __get($name)
	{
		return null;
	}
}
