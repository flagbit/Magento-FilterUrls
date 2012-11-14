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
* Resource model for rewrites for filterable attribute options.
*
* @category Flagbit_FilterUrls
* @package Flagbit_FilterUrls
* @author Michael Türk <michael.tuerk@flagbit.de>
* @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
* @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
* @version 0.1.0
* @since 0.1.0
*/
class Flagbit_FilterUrls_Model_Resource_Mysql4_Rewrite extends Mage_Core_Model_Mysql4_Abstract {


    /**
     * Constructor
     *
     */
    protected function _construct() {

        $this->_init('filterurls/rewrite', 'rewrite_id');
    }
    
    /**
     * Loads the rewrite model reading a dataset from database using attribute code and option id.
     * 
     * @param Flagbit_FilterUrls_Model_Rewrite $rewrite The model to be loaded.
     * @param string $attributeCode The given attribute code.
     * @param int $optionId The given option id.
     * @return Flagbit_FilterUrls_Model_Resource_Mysql4_Rewrite Self.
     */
    public function loadByFilterOption(Flagbit_FilterUrls_Model_Rewrite $rewrite, $attributeCode, $optionId) {
        $read = $this->_getReadAdapter();
        
        if ($read && !empty($attributeCode) && (int) $optionId) {
            $select = $read->select()
                        ->from($this->getMainTable())
                        ->where($this->getMainTable() . '.' . 'attribute_code = ?', $attributeCode)
                        ->where($this->getMainTable() . '.' . 'option_id = ?', $optionId)
                        ->where($this->getMainTable() . '.' . 'store_id = ?', Mage::app()->getStore()->getId());
            
            $data = $read->fetchRow($select);
            
            if ($data) {
                $rewrite->setData($data);
            }
        }
        
        return $this;
    }
    
    /**
     * Loads the rewrite model reading a dataset from database using the rewrite string.
     * 
     * @param Flagbit_FilterUrls_Model_Rewrite $rewrite The model to be loaded.
     * @param string $rewriteString The rewrite string that is looked for.
     * @return Flagbit_FilterUrls_Model_Resource_Mysql4_Rewrite Self.
     */
    public function loadByRewriteString(Flagbit_FilterUrls_Model_Rewrite $rewrite, $rewriteString) {
        $read = $this->_getReadAdapter();
         
        if ($read && !empty($rewriteString)) {
            $select = $read->select()
                        ->from($this->getMainTable())
                        ->where($this->getMainTable() . '.' . 'rewrite = ?', $rewriteString)
                        ->where($this->getMainTable() . '.' . 'store_id = ?', Mage::app()->getStore()->getId());
            
            $data = $read->fetchRow($select);
            
            if ($data) {
                $rewrite->setData($data);
            }
        }
        
        return $this;
    }
    
}