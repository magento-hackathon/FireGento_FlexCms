<?php
/**
 * This file is part of a FireGento e.V. module.
 *
 * This FireGento e.V. module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  FireGento
 * @package   FireGento_FlexCms
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2014 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */

/**
 * FlexCms Content Renderer
 *
 * @category FireGento
 * @package  FireGento_FlexCms
 * @author   FireGento Team <team@firegento.com>
 */
class Firegento_FlexCms_Model_Content extends Mage_Core_Model_Abstract
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('firegento_flexcms/content');
    }

    /**
     * @return Firegento_FlexCms_Model_Content_Data
     */
    protected function _afterSave()
    {
        /** @var Firegento_FlexCms_Model_Content_Data $contentData */
        $contentData = $this->getContentDataModel();
        $contentData->setContent($this->getContent());
        $contentData->setIsActive($this->getIsActive());
        $contentData->save();
        return parent::_afterSave();
    }

    /**
     * @return Firegento_FlexCms_Model_Content_Data
     */
    protected function _afterLoad()
    {
        /** @var Firegento_FlexCms_Model_Content_Data $contentData */
        $contentData = $this->getContentDataModel();
        
        $this->setContent($contentData->getContent());
        $this->setDefaultContent($contentData->getDefaultContent());
        $this->setIsActive($contentData->getIsActive());

        return parent::_afterLoad();
    }

    /**
     * @var int $storeId
     * @return Firegento_FlexCms_Model_Content_Data
     */
    public function setStoreId($storeId)
    {
        $this->setData('store_id', $storeId);
        
        $this->_afterLoad();

        return $this;
    }

    /**
     * @return Firegento_FlexCms_Model_Content_Data
     */
    public function getContentDataModel()
    {
        if ($this->getId()) {
            /** @var $contentDataCollection Firegento_FlexCms_Model_Resource_Content_Data_Collection */
            $contentDataCollection = Mage::getResourceModel('firegento_flexcms/content_data_collection');
            $contentDataCollection->addFieldToFilter('content_id', $this->getId());
            if ($this->getStoreId()) {
                $contentDataCollection->addFieldToFilter('store_id', array('in' => array(0, $this->getStoreId())));
                $contentDataCollection->setOrder('store_id', Varien_Data_Collection::SORT_ORDER_DESC);
                if ($contentDataCollection->getSize()) {
                    $contentData = $contentDataCollection->getFirstItem();
                    if ($contentDataCollection->getSize() > 1) { // store specific content data exists
                        $defaultContentData = $contentDataCollection->getLastItem();
                        $contentData->setDefaultContent($defaultContentData->getContent());
                    } else {
                        $contentData
                            ->setId(null)
                            ->setDefaultContent($contentData->getContent())
                            ->setStoreId($this->getStoreId());
                    }
                    
                    return $contentData;
                }
            } else {
                $contentDataCollection->addFieldToFilter('store_id', 0);
                if ($contentDataCollection->getSize()) {
                    return $contentDataCollection->getFirstItem();
                }
            }
        }

        return Mage::getModel('firegento_flexcms/content_data')->setContentId($this->getId());
    }
}