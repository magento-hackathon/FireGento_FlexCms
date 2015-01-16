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
class Firegento_FlexCms_Block_Adminhtml_Add extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        $this->setTemplate('firegento/flexcms/add.phtml');
    }
    
    public function getContentTypes()
    {
        return Mage::getSingleton('firegento_flexcms/source_contentType')->toArray();
    }
    
    public function getContentElements()
    {
        return Mage::getSingleton('firegento_flexcms/source_contentElement')->toArray();
    }

    public function getNewItemAjaxUrl($areaCode)
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/flexcms/new', array(
            'area' => $areaCode,
            'layouthandle' => $this->_getLayoutHandle(),
        ));
    }
    
    public function getExistingItemAjaxUrl($areaCode)
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/flexcms/existing', array(
            'area' => $areaCode,
            'layouthandle' => $this->_getLayoutHandle(),
        ));
    }

    protected function _getLayoutHandle()
    {
        if (Mage::registry('category')) {
            return 'CATEGORY_' . Mage::registry('category')->getId();
        }
    }
}