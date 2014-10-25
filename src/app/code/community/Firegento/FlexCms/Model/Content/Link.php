<?php

class Firegento_FlexCms_Model_Content_Link extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('firegento_flexcms/content_link');
    }

    /**
     * Load content link by handle and area
     *
     * @param string $handle
     * @param string $area
     *
     * @return object content_link
     */
    public function loadByHandleAndArea($handle, $area){
        $collection = $this->getCollection()
            ->addFieldToFilter('layout_handle', $handle)
            ->addFieldToFilter('area', $area);

        return $collection->getFirstItem();
    }
}