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
class Firegento_FlexCms_Model_Content_Data extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('firegento_flexcms/content_data');
    }

    /**
     * @return Firegento_FlexCms_Model_Content
     */
    public function getContentModel()
    {
        return Mage::getModel('firegento_flexcms/content')->load($this->getContentId());
    }

    /**
     * @return Firegento_FlexCms_Model_Content_Data
     */
    protected function _beforeSave()
    {
        if (is_array($this->getData('content'))) {
            $this->setData('content', Zend_Json::encode($this->getData('content')));
        }
        return parent::_beforeSave();
    }

    /**
     * @return Firegento_FlexCms_Model_Content_Data
     */
    protected function _afterLoad()
    {
        if (!is_array($this->getContent())) {
            try {
                $this->setContent(Zend_Json::decode($this->getContent()));
            } catch (Exception $e) {
                $this->setContent(array());
            }
        }
        return parent::_afterLoad();
    }
}