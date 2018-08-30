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

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('checkboxes');

class JFormFieldAreas extends JFormFieldCheckboxes
{
	protected $type = 'Areas';

	protected function getOptions()
	{
		$ver = new JVersion();
		if( (int)$ver->getShortVersion() == 2 )
		{
			require_once JPATH_SITE.'/components/com_search/models/search.php';
			$model = new SearchModelSearch();

			$areas = $model->getAreas();

		}
		else if( (int)$ver->getShortVersion() == 3 )
		{
			$areas = array();

			JPluginHelper::importPlugin('search');
			$dispatcher = JEventDispatcher::getInstance();
			$searchareas = $dispatcher->trigger('onContentSearchAreas');

			foreach ($searchareas as $area)
			{
				if (is_array($area))
				{
					$areas = array_merge($areas, $area);
				}
			}
		}

		$options = array();

		if( $areas )
		{
			foreach( $areas as $key => $area )
			{
				$tmp = JHtml::_( 'select.option', $key, JText::_( $area ) );
				$tmp->checked = '';
				$options[] = $tmp;
			}

		}
		$options = array_merge( parent::getOptions(), $options );
		return $options;
	}
}
