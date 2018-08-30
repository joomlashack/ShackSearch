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

class modShackSearchHelper
{
    static function getParams(&$params)
    {
        return $params;
    }

    static function init($params, $id)
    {
        $document = JFactory::getDocument();
        $document->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_shacksearch/media/css/mod_shacksearch.css');

        $settings               = new stdClass();
        $settings->searchText   = JText::_('MOD_SHACKSEARCH_SEARCH_LABEL');
        $settings->nextLinkText = JText::_('MOD_SHACKSEARCH_NEXT_LABEL');
        $settings->prevLinkText = JText::_('MOD_SHACKSEARCH_PREV_LABEL');
        $settings->viewAllText  = JText::_('MOD_SHACKSEARCH_VIEW_ALL_LABEL');
        $settings->resultText   = JText::_('MOD_SHACKSEARCH_RESULTS_LABEL');
        $settings->readMoreText = JText::_('MOD_SHACKSEARCH_READ_MORE_LABEL');
        $settings->baseUrl      = JURI::root();

        $settings->ordering       = $params->get('ordering', 'newest');
        $settings->use_grouping   = (boolean)$params->get('use_grouping', false);
        $settings->searchType     = $params->get('searchphrase', 'all');
        $settings->pagesize       = (int)$params->get('pagesize', 10);
        $settings->numsearchstart = (int)$params->get('searchstartchar', 4);
        $settings->use_images     = (boolean)$params->get('use_images', true);
        $settings->show_read_more = (boolean)$params->get('show_readmore', true);
        $settings->areas          = $params->get('areas', array());
        $settings->link_read_more = JRoute::_('index.php?option=com_search');

        $document->addScriptDeclaration('var ps_settings_' . $id . ' = ' . json_encode($settings) . ';');
        $document->addScript(JURI::root() . 'modules/mod_shacksearch/media/js/shacksearch.js');
        $document->addScriptDeclaration('shacksearches.push( ' . $id . ');');
        $document->addScript(JURI::root() . 'modules/mod_shacksearch/media/js/gshacksearch/gshacksearch.nocache.js');
    }

    public static function getAjax()
    {
        require_once JPATH_SITE . '/components/com_search/models/search.php';
        require_once JPATH_ADMINISTRATOR . '/components/com_search/helpers/search.php';

        $input        = JFactory::getApplication()->input;
        $word         = $input->getString('searchword', '');
        $ordering     = $input->getString('ordering', 'newest');
        $searchphrase = $input->getString('searchphrase', 'all');
        $results      = array();
        $searchResult = new stdClass();
        $total        = 0;
        $error        = null;
        if ($word != '') {
            $model = new SearchModelSearch();

            // log the search
            SearchHelper::logSearch($word);

            $lang        = JFactory::getLanguage();
            $upper_limit = $lang->getUpperLimitSearchWord();
            $lower_limit = $lang->getLowerLimitSearchWord();
            if (SearchHelper::limitSearchWord($searchword)) {
                $error = JText::sprintf('COM_SEARCH_ERROR_SEARCH_MESSAGE', $lower_limit, $upper_limit);
            }

            if (SearchHelper::santiseSearchWord($word, $model->getState()->get('match'))) {
                $error = JText::_('COM_SEARCH_ERROR_IGNOREKEYWORD');
            }

            $model->getState()->set('keyword', $word);

            if ($error == null) {
                $model->setSearch($word, $searchphrase, $ordering);
                $results = $model->getData();
                $total   = $model->getTotal();

                require_once JPATH_SITE . '/components/com_content/helpers/route.php';

                for ($i = 0, $count = count($results); $i < $count; $i++) {
                    $row = &$results[$i]->text;

                    if ($model->getState()->get('match') == 'exact') {
                        $searchwords = array($word);
                        $needle      = $word;
                    } else {
                        $searchworda = preg_replace('#\xE3\x80\x80#s', ' ', $word);
                        $searchwords = preg_split("/\s+/u", $searchworda);
                        $needle      = $searchwords[0];
                    }

                    $row         = SearchHelper::prepareSearchContent($row, $needle);
                    $searchwords = array_unique($searchwords);
                    $searchRegex = '#(';
                    $x           = 0;

                    foreach ($searchwords as $k => $hlword) {
                        $searchRegex .= ($x == 0 ? '' : '|');
                        $searchRegex .= preg_quote($hlword, '#');
                        $x++;
                    }
                    $searchRegex .= ')#iu';

                    $row = preg_replace($searchRegex, '<span class="highlight">\0</span>', $row);

                    $result = &$results[$i];
                    if ($result->created) {
                        $created = JHtml::_('date', $result->created, JText::_('DATE_FORMAT_LC3'));
                    } else {
                        $created = '';
                    }

                    $result->text    = JHtml::_('content.prepare', $result->text, '', 'com_search.search');
                    $result->created = $created;
                    $result->count   = $i + 1;
                    $result->href    = JRoute::_($result->href);
                }
            }
        }
        $searchResult->items = $results;
        $searchResult->total = $total;

        return '(' . json_encode($searchResult) . ');';
    }
}
