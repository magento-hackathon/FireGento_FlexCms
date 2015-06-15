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
 * @copyright 2015 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */

/**
 * FlexCms Content Renderer
 *
 * @category FireGento
 * @package  FireGento_FlexCms
 * @author   FireGento Team <team@firegento.com>
 */
class Firegento_FlexCms_Block_Adminhtml_Catalog_Form_Popup extends Mage_Adminhtml_Block_Template
{
    /**
     * Initialize block template
     */
    protected function _construct()
    {
        $this->setTemplate('firegento/flexcms/popup.phtml');
    }

    /**
     * @return Firegento_FlexCms_Model_Category_Changes_Message[]
     */
    public function getMessages()
    {
        return Mage::helper('firegento_flexcms')->getChangesObject($this->getCategory())->getMessages();
    }

    public function getRequestPublishPostUrl()
    {
        return $this->getUrl('adminhtml/publish/requestPublishPost');
    }
    
    public function getMessagePostUrl()
    {
        return $this->getUrl('adminhtml/publish/messagePost');
    }
    
    public function getPublisherHtmlSelect()
    {
        /** @var $block Mage_Adminhtml_Block_Html_Select */
        $block = $this->getLayout()->createBlock('adminhtml/html_select');
        $block->setOptions(Mage::getSingleton('firegento_flexcms/source_publisher')->toOptionArray(true));
        $block->setTitle($this->__('Publisher'));
        $block->setId('publisher');
        $block->setName('publisher');
        $block->setClass('required-entry');
        
        return $block->toHtml();
    }
    
    public function getEditorHtmlSelect()
    {
        /** @var $block Mage_Adminhtml_Block_Html_Select */
        $block = $this->getLayout()->createBlock('adminhtml/html_select');
        $block->setOptions(Mage::getSingleton('firegento_flexcms/source_editor')->toOptionArray(true));
        $block->setTitle($this->__('Editor'));
        $block->setId('editor');
        $block->setName('editor');
        $block->setClass('');
        
        return $block->toHtml();
    }
}