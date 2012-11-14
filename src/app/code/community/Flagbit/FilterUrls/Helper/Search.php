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
 * Helper for simple normalization of strings and translation issues
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Michael Türk <tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Helper_Search extends Mage_CatalogSearch_Helper_Data
{
    /**
     * Get the search result url.
     *
     * return string
     */
    public function getResultUrl($query = null)
    {
        return $this->_getUrl(Flagbit_FilterUrls_Model_Parser_Search::SEARCH_STRING . '/', array(
            '_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
        ));
    }

    public function getQueryParamName()
    {
        return Flagbit_FilterUrls_Model_Parser_Search::SEARCH_STRING;
    }
}
