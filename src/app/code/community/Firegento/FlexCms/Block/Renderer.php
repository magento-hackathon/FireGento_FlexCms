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

    /* @var Firegento_FlexCms_Model_Resource_Content_Link_Collection */
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
        $this->_loadContentElements();
    }

    /**
     * load content elements by sort order
     */
    private function _loadContentElements()
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

            $this->_contentElements[$order] = $link;
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
    private function getContentCollection()
    {
        $linkCollection = Mage::getModel('firegento_flexcms/content_link')->getCollection()
            ->addFieldToFilter('area', array('eq' => $this->getAreaKey()))
            ->addFieldToFilter('layout_handle', array('in' => $this->_layoutHandles));

        if (!Mage::app()->isSingleStoreMode()) {
            $linkCollection->addFieldToFilter('store_id',
                array('finset' => array(0, Mage::app()->getStore()->getId()))
            );
        }

        $linkCollection->getSelect()->join(array(
                'content' => Mage::getSingleton('core/resource')->getTableName('firegento_flexcms/content')),
            'main_table.content_id = content.flexcms_content_id',
            array('content.*')
        );

        return $linkCollection;
    }
}