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
 * @author Karl Spies <karl.spies@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.2.0
 * @since 0.2.0
 */
/**
 * Item model for link item of layered navigation.
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Karl Spies <karl.spies@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.2.0
 * @since 0.2.0
 */
class Flagbit_FilterUrls_Block_Seo_Sitemap extends Mage_Catalog_Block_Seo_Sitemap_Abstract
{

    public function _prepareLayout()
    {
        /* @var $collection Flagbit_FilterUrls_Model_Resource_Mysql4_Url_Collection */
        $collection = Mage::getModel('filterurls/url')->getCollection();
        $collection->addFieldToFilter('store_id', Mage::app()->getStore()->getId());
        foreach ($collection as $item) {
            $name = str_replace('/', ' ', $item->getRequestPath());
            $name = str_replace('_', ' ', $name);
            $name = str_replace('-', ' ', $name);
            $name = str_replace(Mage::helper('filterurls')->getUrlSuffix(), '', $name);
            $item->setName(uc_words($name));
        }
        $this->setCollection($collection);
        return parent::_prepareLayout();
    }

    /**
     * Get item URL
     *
     * In most cases should be overriden in descendant blocks
     *
     * @param Varien_Object $item
     * @return string
     */
    public function getItemUrl($item)
    {
        return $this->getBaseUrl() . $item->getRequestPath();
    }
}
