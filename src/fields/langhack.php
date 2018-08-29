<?php
/**
 * @copyright	Copyright ©2018 Joomlashack. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
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