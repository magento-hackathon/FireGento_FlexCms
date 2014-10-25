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
     *
     */
    protected function _construct()
    {
        $this->_loadContentElements();

        parent::_construct();
    }

    /**
     *
     */
    private function _loadContentElements()
    {

        /* @var array $layoutHandles */
        $layoutHandles = Mage::app()->getLayout()->getUpdate()->getHandles();

        /* @var Firegento_FlexCms_Model_Resource_Content_Link_Collection $contentCollection */
        $contentCollection = Mage::getModel('firegento_flexcms/content_link')->getCollection()
            ->addFieldToFilter('area', array('eq' => $this->getAreaKey()))
            ->addFieldToFilter('layout_handle', array('in' => $layoutHandles));

        if (!Mage::app()->isSingleStoreMode()) {
            $contentCollection->addFieldToFilter('store_id',
                array('finset' => array(0, Mage::app()->getStore()->getId()))
            );
        }

    }
}