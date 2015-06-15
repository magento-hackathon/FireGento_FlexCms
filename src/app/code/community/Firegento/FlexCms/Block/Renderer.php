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
class Firegento_FlexCms_Block_Renderer extends Mage_Core_Block_Template
{
    /**
     * @var string
     */
    protected $_template = "firegento/flexcms/area.phtml";

    /**
     * @var array
     */
    protected $_layoutHandles = array('default');

    /* @var Firegento_FlexCms_Model_Content_Link[] */
    protected $_contentElements = array();

    /**
     * collect all layout handles
     */
    protected function _construct()
    {
        $this->_layoutHandles = Mage::app()->getLayout()->getUpdate()->getHandles();
    }

    /**
     * load content elements
     */
    protected function _beforeToHtml()
    {
        if(is_array($this->_layoutHandles) && sizeof($this->_layoutHandles) > 1){
            $this->_loadContentElements();
            $this->_initElementRendering();
        }
    }

    /**
     * configure block renderer class per element
     */
    protected function _initElementRendering(){
        $typeConfig = Mage::getStoreConfig('firegento_flexcms/types');

        if(is_array($this->_contentElements)){
            foreach($this->_contentElements as $element){
                if(isset($typeConfig[$element->getContentType()])){
                    $cfg = new Varien_Object($typeConfig[$element->getContentType()]);
                    if(!$renderType = $cfg->getBlockType()){
                        $rendererType = 'firegento_flexcms/type_default';
                    }

                    $rendererName = 'flexcms_content_render_'.$element->getArea().'_'.$element->getContentId();
                    $rendererTemplate = $cfg->getBlockTemplate();
                    $rendererContent = new Varien_Object($element->getContent());

                    /** @var Firegento_FlexCms_Block_Type_Abstract $block */
                    $block = Mage::app()->getLayout()->createBlock($rendererType, $rendererName);
                    $block->setTemplate($rendererTemplate);
                    $block->setContentData($rendererContent);

                    $element->setRenderer($block);
                }
            }
        }
    }

    /**
     * load content elements by sort order
     */
    protected function _loadContentElements()
    {
        /* @var Firegento_FlexCms_Model_Resource_Content_Link_Collection $linkCollection */
        $linkCollection = $this->getContentCollection();

        $layoutHandles = array_flip($this->_layoutHandles);

        // determine content element for sort orders, keep most important element per order
        foreach ($linkCollection as $link) {
            $order = $link->getSortOrder();
            if (isset($this->_contentElements[$order])) {
                if ($layoutHandles[$this->_contentElements[$order]["layout_handle"]] < $layoutHandles[$link["layout_handle"]]) {
                    $this->_contentElements[$order] = $link;
                }
                continue;
            }

            if ($link->getIsActive()) {
                $this->_contentElements[$order] = $link;
            }
        }

        // sort content elements by sort order
        ksort($this->_contentElements);
    }

    /**
     * @param $value
     * @return $this
     */
    public function setContentElements($value)
    {
        $this->_contentElements = $value;
        return $this;
    }

    /**
     * @return Firegento_FlexCms_Model_Resource_Content_Link_Collection
     */
    public function getContentElements()
    {
        return $this->_contentElements;
    }

    /**
     * fetch content links, filtered by current store, area and layout_handle
     *
     * @return Firegento_FlexCms_Model_Resource_Content_Link_Collection
     */
    protected function getContentCollection()
    {
        $linkCollection = Mage::getModel('firegento_flexcms/content_link')->getCollection()
            ->addFieldToFilter('area', array('eq' => $this->getAreaKey()))
            ->addFieldToFilter('layout_handle', array('in' => $this->_layoutHandles));

        if ($storeId = Mage::app()->getStore()->getId()) {
            $linkCollection->setStoreId($storeId);
        }

        return $linkCollection;
    }
}