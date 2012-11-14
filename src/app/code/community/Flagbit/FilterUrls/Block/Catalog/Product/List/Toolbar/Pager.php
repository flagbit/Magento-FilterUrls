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
 * Magento's catalog list toolbar pager. Needs to be adapted to adapt toolbar links to FilterUrls URL scheme.
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Michael Türk <michael.tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Block_Catalog_Product_List_Toolbar_Pager extends Mage_Page_Block_Html_Pager
{
    /**
     * Overwritten method. Does not use the _current-method of URL models anymore. Retrieves a speaking filter url from
     * own model.
     * 
     * @see Mage_Page_Block_Html_Pager::getPagerUrl()
     * @param array $params The params to be added to current url
     * @return string The resulting speaking url to be used in toolbar.
     */
    public function getPagerUrl($params=array())
    {
        
        $url = Mage::getModel('filterurls/catalog_layer_filter_item')->getSpeakingFilterUrl(FALSE, TRUE, $params);
        return $url;
    }
}