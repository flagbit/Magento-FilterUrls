<?php 

class Flagbit_FilterUrls_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
    /**
    * Get attribute sets identifiers of current product set
    *
    * @return array
    */
    protected function _getSetIds()
    {
        $key = $this->getStateKey().'_SET_IDS';
        $setIds = $this->getAggregator()->getCacheData($key);
    
        if ($setIds === null) {
            $productCollection = $this->getProductCollection();
            $select = clone $productCollection->getSelect();
            /** @var $select Varien_Db_Select */
            $select->reset(Zend_Db_Select::COLUMNS);
            $select->distinct(true);
            $select->columns('attribute_set_id');
            $setIds = $productCollection->getConnection()->fetchCol($select);
            
            $this->getAggregator()->saveCacheData($setIds, $key, $this->getStateTags());
        }
    
        return $setIds;
    }
}