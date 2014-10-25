<?php

class Firegento_FlexCms_Model_Resource_Content extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource
     *
     */
    protected function _construct()
    {
        $this->_init('firegento_flexcms/content', 'flexcms_content_id');
    }

    /**
     * Perform operations before object save
     *
     * @param Mage_Cms_Model_Block $object
     * @return Mage_Cms_Model_Resource_Block
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (! $object->getId()) {
            $object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }
        $object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }
}
