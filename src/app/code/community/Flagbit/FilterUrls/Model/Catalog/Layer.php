<?php 

class Flagbit_FilterUrls_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
//     SELECT DISTINCT `e`.`attribute_set_id` FROM `catalog_product_entity` AS `e`
//     INNER JOIN `catalog_category_product_index` AS `cat_index` ON cat_index.product_id=e.entity_id AND cat_index.store_id='1' AND cat_index.visibility IN(2, 4) AND cat_index.category_id='10'
//     INNER JOIN `catalog_product_index_price` AS `price_index` ON price_index.entity_id = e.entity_id AND price_index.website_id = '1' AND price_index.customer_group_id = 0

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