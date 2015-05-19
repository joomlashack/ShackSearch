<?php
/**
* @copyright	Copyright (C) 2013 Johan Sundell. All rights reserved.
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

class modPixSearchHelper
{
	static function getParams( &$params )
	{
		return $params;
	}
	
	static function init( $params, $id )
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet( '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
		$document->addStyleSheet( JURI::base().'modules/mod_pixsearch/media/css/mod_pixsearch.css' );
	
		$settings = new stdClass();
		$settings->searchText = JText::_( 'MOD_PIXSEARCH_SEARCH_LABEL' );
		$settings->nextLinkText = JText::_( 'MOD_PIXSEARCH_NEXT_LABEL' );
		$settings->prevLinkText = JText::_( 'MOD_PIXSEARCH_PREV_LABEL' );
		$settings->viewAllText = JText::_( 'MOD_PIXSEARCH_VIEW_ALL_LABEL' );
		$settings->resultText = JText::_( 'MOD_PIXSEARCH_RESULTS_LABEL' );
		$settings->readMoreText = JText::_( 'MOD_PIXSEARCH_READ_MORE_LABEL' );
		$settings->baseUrl = JURI::root();
		
		$settings->ordering = $params->get( 'ordering', 'newest' );
		$settings->use_grouping = (boolean)$params->get( 'use_grouping', false );
		$settings->searchType = $params->get( 'searchphrase', 'all' );
		$settings->pagesize = (int)$params->get( 'pagesize', 10 );
		$settings->numsearchstart = (int)$params->get( 'searchstartchar', 4 );
		$settings->use_images = (boolean)$params->get( 'use_images', true );
		$settings->show_read_more = (boolean)$params->get( 'show_readmore', true );
		$settings->areas = $params->get( 'areas', array() );
		$settings->link_read_more = JRoute::_( 'index.php?option=com_search' );
	
		$document->addScriptDeclaration( 'var ps_settings_'.$id.' = '.json_encode( $settings ).';' );
		$document->addScript(JURI::root().'modules/mod_pixsearch/media/js/pixsearch.js' );
		$document->addScriptDeclaration( 'pixsearches.push( '.$id.');' );
		$document->addScript( JURI::root().'modules/mod_pixsearch/media/js/gpixsearch/gpixsearch.nocache.js' );
	}
}
