<?php
/**
 * This file is part of the Flagbit_FilterUrls project.
 *
 * Flagbit_FilterUrls is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Michael Türk <tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
/**
 * Parser for given url string. Tries to map the string on attribute options to rebuild the underlying parameters.
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Karl Spies <karl.spies@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Model_Parser_Search extends Varien_Object implements Flagbit_FilterUrls_Model_Parser
{
    const SEARCH_STRING = 's';

    /**
     * Tries to parse a given request path and return the corresponding request parameters.
     *
     * @param $request The request.
     * @param int $storeId The current stores id (can be multilingual).
     * @return mixed array|false Returns the array of request parameters on success, false otherwise.
     */
    public function parseFilterInformationFromRequest($request, $storeId)
    {
        /* @var $requestInfo Flagbit_FilterUrls_Model_Request_Info */
        $requestInfo = Mage::getModel('filterurls/request_info');
        $requestString = trim($request->getPathInfo(), ' /');
        // case 1: there is a speaking url for current request path -> not our business
        /** @var $rewrite Mage_Core_Model_Url_Rewrite */
        $rewrite = Mage::getResourceModel('catalog/url')->getRewriteByRequestPath($requestString, $storeId);
        if ($rewrite && $rewrite->getUrlRewriteId()) {
            return $requestInfo;
        }

        $path = explode('/', $requestString);

        if (!in_array(self::SEARCH_STRING, $path) || count($path) < 2) {
            return $requestInfo;
        }

        $requestInfo->setParam('q', $path[1]);

        $requestInfo->setIsValid(true);
        //query parameter is always the first.
        return $requestInfo;
    }
}
