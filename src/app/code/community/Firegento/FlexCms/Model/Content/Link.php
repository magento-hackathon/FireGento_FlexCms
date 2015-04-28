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
class Firegento_FlexCms_Model_Content_Link extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('firegento_flexcms/content_link');
    }

    /**
     * @return Firegento_FlexCms_Model_Content
     */
    public function getContentModel()
    {
        return Mage::getModel('firegento_flexcms/content')->load($this->getContentId())->setStoreId($this->getStoreId());
    }

    /**
     * Get decoded content data
     *
     * @return array
     */
    public function getContent()
    {
        if (is_array($this->_getData('content'))) {
            return $this->_getData('content');
        }

        if (trim($this->_getData('content'))) {
            try {
                return Zend_Json::decode($this->_getData('content'));
            } catch (Exception $e) {}
        }

        return array();
    }

    /**
     * Update fields of link or content element depending on form entries
     *
     * @param $fields array($fieldName => $fieldValue)
     */
    public function updateFields($fields)
    {
        if (array_key_exists('delete', $fields)) {
            $this->_delete();
            return;
        }

        if ($storeId = Mage::app()->getRequest()->getParam('store')) {
            $this->setStoreId($storeId);
        }
        
        $contentElement = $this->getContentModel();

        $content = array();
        $isReusable = false;
        foreach ($fields as $fieldName => $fieldValue) {

            if ($fieldName == 'title') {
                $contentElement->setTitle($fieldValue);
            } elseif ($fieldName == 'sort_order') {
                $this->setSortOrder($fieldValue);
            } elseif ($fieldName == 'is_reusable') {
                $isReusable = (bool) $fieldValue;
            } else {
                if (substr($fieldName, -8) == '_default') {
                    continue;
                }
                if (isset($fields[$fieldName . '_default']) && $fields[$fieldName . '_default']) {
                    continue;
                }
                $content[$fieldName] = $fieldValue;
            }
        }

        $contentElement
            ->setContent($content)
            ->setIsReusable($isReusable)
            ->save();
        
        $this->save();
    }


    /**
     * delete link or entire content element if no other links are referenced to it
     */
    protected function _delete()
    {
        $parentElementUsageCollection = $this->getCollection()
            ->addFieldToFilter('content_id', array('eq' => $this->getContentId()));
        if (count($parentElementUsageCollection) == 1) {
            $this->getContentModel()->delete(); // link and content_data get deleted implicitly via foreign key
        } else {
            $this->delete();
        }
    }
}
