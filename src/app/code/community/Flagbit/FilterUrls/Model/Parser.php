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
 * @author Michael Türk <tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Model_Parser extends Mage_Core_Model_Abstract
{
    /**
     * Tries to parse a given request path and return the corresponding request parameters.
     * 
     * @param string $requestString The request path string to be parsed.
     * @param int $storeId The current stores id (can be multilingual).
     * @param array|false Returns the array of request parameters on success, false otherwise.
     */
    public function parseFilterInformationFromRequest($requestString, $storeId) {
        // case 1: there is a speaking url for current request path -> not our business
        $rewrite = Mage::getResourceModel('catalog/url')->getRewriteByRequestPath($requestString, $storeId);
        if ($rewrite && $rewrite->getUrlRewriteId()) {
            return false;
        }
        
        $configUrlSuffix = Mage::getStoreConfig('catalog/seo/category_url_suffix');
        $shortRequestString = substr($requestString, 0, strrpos($requestString, '/')) . $configUrlSuffix;
        $rewrite = Mage::getResourceModel('catalog/url')->getRewriteByRequestPath($shortRequestString, $storeId);
        
        // case 2: the shortened request path cannot be found as rewrite -> no category -> not our business
        if (!$rewrite || !$rewrite->getUrlRewriteId() || !$rewrite->getCategoryId()) {
            return false;
        }
        
        // case 3: we have a category. May be our business.
        $categoryId = $rewrite->getCategoryId();
        $category = Mage::getModel('catalog/category')->load($categoryId);
        if (!$category->getId()) {
            return false;
        }
        
        // get last part of the URL - if we have filter base urls the filter options are lowercased and concetenated by
        // dashes. The standard file extension of catalog pages may have to be removed first.
        $filterString = substr($requestString, strrpos($requestString, '/') + 1);
        if (substr($filterString, -strlen($configUrlSuffix)) == $configUrlSuffix) {
            $filterString = substr($filterString, 0, -strlen($configUrlSuffix));
        }
        
        // get different filter option values and active filterable attributes
        // if one of them is empty, this is not our business
        $filterInfos = explode('-', $filterString);
        
        // try to translate filter option values to request parameters using the rewrite models
        $params = array();
        foreach ($filterInfos as $filterInfo) {
            $rewrite = Mage::getModel('filterurls/rewrite')
                ->loadByRewriteString($filterInfo);
            
            if ($rewrite->getId()) {
                $params[$rewrite->getAttributeCode()] = $rewrite->getOptionId();
            }
            else {
                return false;
            }
        }
        
        // return structured result
        return array(
            'categoryId' => $categoryId,
            'additionalParams' => $params
        );
    }
}