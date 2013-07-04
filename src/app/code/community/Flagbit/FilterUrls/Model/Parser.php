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
 * @author Michael Türk <michael.tuerk@flagbit.de>
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
 * @author Michael Türk <michael.tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Model_Parser extends Mage_Core_Model_Abstract
{
    const CATEGORY_VIEW_REQUEST_STRING = 'catalog/category/view';

    /**
     * Tries to parse a given request path and return the corresponding request parameters.
     *
     * @param string $requestString The request path string to be parsed.
     * @param int $storeId The current stores id (can be multilingual).
     * @param array|false Returns the array of request parameters on success, false otherwise.
     */
    public function parseFilterInformationFromRequest($requestString, $storeId)
    {
        $categoryId = $this->_getCategoryIdByRequestString($requestString, $storeId);
        if(!$categoryId) {
            return false;
        }

        $configUrlSuffix = Mage::helper('filterurls')->getUrlSuffix();

        // get last part of the URL - if we have filter base urls the filter options are lowercased and concetenated by
        // dashes. The standard file extension of catalog pages may have to be removed first.
        $configUrlSuffix = Mage::helper('filterurls')->getUrlSuffix();
        $filterString = substr($requestString, strrpos($requestString, '/') + 1);
        if (substr($filterString, -strlen($configUrlSuffix)) == $configUrlSuffix) {
            $filterString = substr($filterString, 0, -strlen($configUrlSuffix));
        }

        // get different filter option values and active filterable attributes
        // if one of them is empty, this is not our business
        $filterInfos = explode('-', $filterString);

        // try to translate filter option values to request parameters using the rewrite models
        $params = array();
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
                $params[$rewrite->getAttributeCode()] = $rewrite->getOptionId();
            }
        } else {
            return false;
        }

        // return structured result
        return array(
            'categoryId' => $categoryId,
            'additionalParams' => $params
        );
    }

    protected function _getCategoryIdByRequestString($requestString, $storeId)
    {
        $categoryId = null;
        $request = Mage::app()->getRequest();

        $configUrlSuffix = Mage::helper('filterurls')->getUrlSuffix();
        $shortRequestString = substr($requestString, 0, strrpos($requestString, '/')) . $configUrlSuffix;

        // case 1: if enterprise is running, we need to handle things different
        if(Mage::helper('core')->isModuleEnabled('Enterprise_UrlRewrite'))
        {
            $origRequestUri = $request->getRequestUri();
            $origPathInfo = $request->getPathInfo();

            // emulate new enterprise rewrite stuff
            $request->setPathInfo($shortRequestString);
            Mage::getModel('enterprise_urlrewrite/url_rewrite_request')->rewrite();

            // if we're on a category, we directly get the native url (new enterprise rewrite stuff)
            if(strpos($request->getPathInfo(), self::CATEGORY_VIEW_REQUEST_STRING) !== FALSE)
            {
                if(preg_match('/id\/([0-9]+)/', $request->getPathInfo(), $matches))
                {
                    $categoryId = $matches[1];
                }
            }

            $request->setRequestUri($origRequestUri);
            $request->setPathInfo($origPathInfo);
        }

        if(!$categoryId)
        {
            // case 2: there is a speaking url for current request path -> not our business
            /** @var $rewrite Mage_Core_Model_Url_Rewrite */
            $rewrite = Mage::getResourceModel('catalog/url')->getRewriteByRequestPath($requestString, $storeId);
            if ($rewrite && $rewrite->getUrlRewriteId())
            {
                return false;
            }

            $rewrite = Mage::getResourceModel('catalog/url')->getRewriteByRequestPath($shortRequestString, $storeId);

            // case 3: the shortened request path cannot be found as rewrite -> no category -> not our business
            if (!$rewrite || !$rewrite->getUrlRewriteId() || !$rewrite->getCategoryId())
            {
                return false;
            }

            $categoryId = $rewrite->getCategoryId();
        }

        // case 4: we have a category. May be our business.
        if (!Mage::getResourceModel('catalog/category')->checkId($categoryId))
        {
            return false;
        }

        return $categoryId;
    }
}