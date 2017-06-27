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
 * Helper for simple normalization of strings and translation issues
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Michael Türk <michael.tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Helper_Data extends Mage_Catalog_Helper_Product_Url
{

    public function __construct() {
        parent::__construct();
        $this->_convertTable['ä'] = 'ae';
        $this->_convertTable['Ä'] = 'Ae';
        $this->_convertTable['ö'] = 'oe';
        $this->_convertTable['Ö'] = 'Oe';
        $this->_convertTable['ü'] = 'ue';
        $this->_convertTable['Ü'] = 'Ue';
        $this->_convertTable['ß'] = 'ss';
        $this->_convertTable['/'] = '_';
        $this->_convertTable['-'] = '_';
        $this->_convertTable['"'] = '_';
        $this->_convertTable['&'] = '_';
        $this->_convertTable[' '] = '_';
        $this->_convertTable['('] = '_';
        $this->_convertTable[')'] = '_';
        $this->_convertTable['='] = '_';
    }

    /**
     * normalize Characters
     * Example: ü -> ue
     *
     * @param string $string
     * @return string
     */
    public function normalize($string)
    {
        $string = $this->format($string);
        $string = preg_replace('/_[^A-Za-z0-9]+|[^A-Za-z0-9]+_/', '_', $string);
        $string = trim($string, '_');
        return $string;
    }

    /**
     * Get the configured URL suffix
     *
     * @return string
     */
    public function getUrlSuffix()
    {
        $suffix = Mage::getStoreConfig('catalog/seo/category_url_suffix');
        if (Mage::helper('core')->isModuleEnabled('Enterprise_Catalog')
            && substr($suffix, 0, 1) !== '.') {
            $suffix = '.' . $suffix;
        }

        return $suffix;
    }

}
