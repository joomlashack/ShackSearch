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

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\SearchHelper as JSearchHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

defined('_JEXEC') or die();

class modShackSearchHelper
{
    /**
     * @param Registry $params
     * @param int      $id
     */
    public static function init(Registry $params, $id)
    {
        HTMLHelper::_('stylesheet', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
        HTMLHelper::_('stylesheet', 'mod_shacksearch/mod_shacksearch.css', ['relative' => true]);

        $settings = (object)[
            'searchText'     => Text::_('MOD_SHACKSEARCH_SEARCH_LABEL'),
            'nextLinkText'   => Text::_('MOD_SHACKSEARCH_NEXT_LABEL'),
            'prevLinkText'   => Text::_('MOD_SHACKSEARCH_PREV_LABEL'),
            'viewAllText'    => Text::_('MOD_SHACKSEARCH_VIEW_ALL_LABEL'),
            'resultText'     => Text::_('MOD_SHACKSEARCH_RESULTS_LABEL'),
            'readMoreText'   => Text::_('MOD_SHACKSEARCH_READ_MORE_LABEL'),
            'baseUrl'        => Uri::root(),
            'ordering'       => $params->get('ordering', 'newest'),
            'use_grouping'   => (boolean)$params->get('use_grouping', false),
            'searchType'     => $params->get('searchphrase', 'all'),
            'pagesize'       => (int)$params->get('pagesize', 10),
            'numsearchstart' => (int)$params->get('searchstartchar', 4),
            'use_images'     => (boolean)$params->get('use_images', true),
            'show_read_more' => (boolean)$params->get('show_readmore', true),
            'areas'          => $params->get('areas', []),
            'link_read_more' => Route::_('index.php?option=com_search')
        ];

        $document = Factory::getDocument();

        $document->addScriptDeclaration('var ps_settings_' . $id . ' = ' . json_encode($settings) . ';');
        HTMLHelper::_('script', 'mod_shacksearch/shacksearch.js', ['relative' => true]);

        $document->addScriptDeclaration('shacksearches.push( ' . $id . ');');
        HTMLHelper::_('script', 'mod_shacksearch/gshacksearch/gshacksearch.nocache.js', ['relative' => true]);
    }

    public static function getAjax()
    {
        require_once JPATH_SITE . '/components/com_search/models/search.php';
        require_once JPATH_ADMINISTRATOR . '/components/com_search/helpers/search.php';

        $input        = Factory::getApplication()->input;
        $word         = $input->getString('searchword', '');
        $ordering     = $input->getString('ordering', 'newest');
        $searchphrase = $input->getString('searchphrase', 'all');
        $results      = [];
        $searchResult = (object)[];
        $total        = 0;
        $error        = null;
        if ($word != '') {
            $model = new SearchModelSearch();

            // log the search
            JSearchHelper::logSearch($word, 'com_search');

            $lang        = Factory::getLanguage();
            $upper_limit = $lang->getUpperLimitSearchWord();
            $lower_limit = $lang->getLowerLimitSearchWord();
            if (SearchHelper::limitSearchWord($searchword)) {
                $error = Text::sprintf('COM_SEARCH_ERROR_SEARCH_MESSAGE', $lower_limit, $upper_limit);
            }

            if (SearchHelper::santiseSearchWord($word, $model->getState()->get('match'))) {
                $error = Text::_('COM_SEARCH_ERROR_IGNOREKEYWORD');
            }

            $model->getState()->set('keyword', $word);

            if ($error == null) {
                $model->setSearch($word, $searchphrase, $ordering);
                $results = $model->getData();
                $total   = $model->getTotal();

                require_once JPATH_SITE . '/components/com_content/helpers/route.php';

                for ($i = 0, $count = count($results); $i < $count; $i++) {
                    $result = &$results[$i];

                    if ($model->getState()->get('match') == 'exact') {
                        $searchwords = [$word];
                        $needle      = $word;
                    } else {
                        $searchworda = preg_replace('#\xE3\x80\x80#s', ' ', $word);
                        $searchwords = preg_split("/\s+/u", $searchworda);
                        $needle      = $searchwords[0];
                    }

                    $result->text = SearchHelper::prepareSearchContent($result->text, $needle);
                    $searchwords  = array_unique($searchwords);
                    $searchRegex  = '#(';
                    $x            = 0;

                    foreach ($searchwords as $k => $hlword) {
                        $searchRegex .= ($x == 0 ? '' : '|');
                        $searchRegex .= preg_quote($hlword, '#');
                        $x++;
                    }
                    $searchRegex .= ')#iu';

                    $highlighter   = '<span class="highlight">\0</span>';
                    $result->text  = preg_replace($searchRegex, $highlighter, $result->text);
                    $result->title = preg_replace($searchRegex, $highlighter, $result->title);

                    if ($result->created) {
                        $created = HTMLHelper::_('date', $result->created, Text::_('DATE_FORMAT_LC3'));
                    } else {
                        $created = '';
                    }

                    $result->text    = HTMLHelper::_('content.prepare', $result->text, '', 'com_search.search');
                    $result->created = $created;
                    $result->count   = $i + 1;
                    $result->href    = Route::_($result->href);
                }
            }
        }
        $searchResult->items = $results;
        $searchResult->total = $total;

        return '(' . json_encode($searchResult) . ');';
    }
}
