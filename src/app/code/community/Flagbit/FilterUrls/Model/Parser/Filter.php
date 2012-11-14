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
 * @author Karl Spies <karl.Spies@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Model_Parser_Filter extends Varien_Object implements Flagbit_FilterUrls_Model_Parser
{
    /**
     * Tries to parse a given request path and return the corresponding request parameters.
     *
     * @param $request The request.
     * @param int $storeId The current stores id (can be multilingual).
     * @param array|false Returns the array of request parameters on success, false otherwise.
     * @return Flagbit_FilterUrls_Model_Request_Info
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

        $configUrlSuffix = Mage::getStoreConfig('catalog/seo/category_url_suffix');
        $shortRequestString = substr($requestString, 0, strrpos($requestString, '/')) . $configUrlSuffix;
        $rewrite = Mage::getResourceModel('catalog/url')->getRewriteByRequestPath($shortRequestString, $storeId);

        // case 2: the shortened request path cannot be found as rewrite -> no category -> not our business
        if (!$rewrite || !$rewrite->getUrlRewriteId() || !$rewrite->getCategoryId()) {
            return $requestInfo;
        }

        // case 3: we have a category. May be our business.
        $categoryId = $rewrite->getCategoryId();
        $category = Mage::getModel('catalog/category')->load($categoryId);
        if (!$category->getId()) {
            return $requestInfo;
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
        /** @var $rewriteCollection Flagbit_FilterUrls_Model_Resource_Mysql4_Rewrite_Collection */
        $rewriteCollection = Mage::getModel('filterurls/rewrite')
            ->getCollection()
            ->addFieldToFilter('rewrite', array('in' => $filterInfos))
            ->addFieldToFilter('store_id', $storeId);

        // Ugly workaround. If rewrite doesn't exist in the current store view,
        // search for the rewrite in other store views and take the first.
        // @todo generate non existing rewrites on every filterurl request
        if (count($rewriteCollection) == 0) {
            $rewriteCollection = Mage::getModel('filterurls/rewrite')
                ->getCollection()
                ->addFieldToFilter('rewrite', array('in' => $filterInfos));

            $rewriteCollection->getSelect()->group('rewrite');
        }

        if (count($rewriteCollection) == count($filterInfos)) {
            /** @var $rewrite Flagbit_FilterUrls_Model_Rewrite */
            foreach ($rewriteCollection as $rewrite) {
                $requestInfo->setParam($rewrite->getAttributeCode(), $rewrite->getOptionId());
            }
        } else {
            return $requestInfo;
        }
        $requestInfo->setParam('id', $categoryId);
        $requestInfo->setIsValid(true);
        return $requestInfo;
    }
}
