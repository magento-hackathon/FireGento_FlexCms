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

    /**
     * @return Firegento_FlexCms_Model_Content
     */
    public function getContentModel()
    {
        return Mage::getModel('firegento_flexcms/content')->load($this->getContentId());
    }

    public function getContent()
    {
        if (is_array($this->_getData('content'))) {
            return $this->_getData('content');
        }

        if (trim($this->_getData('content'))) {
            return Zend_Json::decode($this->_getData('content'));
        }

        return array();
    }
}