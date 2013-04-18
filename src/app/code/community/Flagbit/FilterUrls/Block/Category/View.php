<?php

class Flagbit_FilterUrls_Block_Category_View extends Mage_Catalog_Block_Category_View
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->getLayout()->createBlock('catalog/breadcrumbs');

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $category = $this->getCurrentCategory();
            if ($title = $category->getMetaTitle()) {
                $headBlock->setTitle($title);
            }
            if ($description = $category->getMetaDescription()) {
                $headBlock->setDescription($description);
            }
            if ($keywords = $category->getMetaKeywords()) {
                $headBlock->setKeywords($keywords);
            }
            if ($this->helper('catalog/category')->canUseCanonicalTag()) {
                $rewrite = Mage::getStoreConfig('web/seo/use_rewrites',Mage::app()->getStore()->getId());
                // Rewrite
                if($rewrite == 1) {
                    $headBlock->removeItem('link_rel',$category->getUrl());
                    $headBlock->addLinkRel('canonical', Mage::getModel('filterurls/catalog_layer_filter_item')->getSpeakingFilterUrl(FALSE, TRUE));
                }
                else{
                    $headBlock->addLinkRel('canonical', $category->getUrl());

                }
            }

            if(Mage::registry('filterurls_active') === true)
            {
                $headBlock->setRobots('noindex,nofollow');
            }

            /*
            want to show rss feed in the url
            */
            if ($this->IsRssCatalogEnable() && $this->IsTopCategory()) {
                $title = $this->helper('rss')->__('%s RSS Feed',$this->getCurrentCategory()->getName());
                $headBlock->addItem('rss', $this->getRssLink(), 'title="'.$title.'"');
            }
        }
        return $this;
    }

}