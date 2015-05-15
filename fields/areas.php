<?php
/**
 * @copyright	Copyright (C) 2013 Johan Sundell. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
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
		else
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
