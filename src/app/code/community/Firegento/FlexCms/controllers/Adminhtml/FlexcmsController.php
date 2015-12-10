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
class Firegento_FlexCms_Adminhtml_FlexcmsController extends Mage_Adminhtml_Controller_Action
{
    /**
     *
     */
    public function newAction()
    {
        $area = $this->getRequest()->getParam('area');
        $layoutHandle = $this->getRequest()->getParam('layouthandle');
        $elementType = $this->getRequest()->getParam('elementtype');
        
        if (!$area || !$elementType || !$layoutHandle) {
            Mage::throwException($this->__('Wrong parameters.'));
        }
        $content = $this->_getNewContent($elementType);

        $contentLink = $this->_getNewContentLink($content, $area, $layoutHandle);

        /** @var $contentBlock Firegento_FlexCms_Adminhtml_FlexcmsController */
        $contentBlock = new Firegento_FlexCms_Block_Adminhtml_Form_Element_Content();
        
        $this->getResponse()->setBody($contentBlock->getLinkHtml($contentLink));
    }

    /**
     *
     */
    public function existingAction()
    {
        $area = $this->getRequest()->getParam('area');
        $layoutHandle = $this->getRequest()->getParam('layouthandle');
        $contentId = $this->getRequest()->getParam('contentid');

        if (!$area || !$contentId || !$layoutHandle) {
            Mage::throwException($this->__('Wrong parameters.'));
        }
        $content = $this->_getExistingContent($contentId);

        $contentLink = $this->_getNewContentLink($content, $area, $layoutHandle);

        /** @var $contentBlock Firegento_FlexCms_Adminhtml_FlexcmsController */
        $contentBlock = new Firegento_FlexCms_Block_Adminhtml_Form_Element_Content();

        $this->getResponse()->setBody($contentBlock->getLinkHtml($contentLink));
    }

    /**
     * @param string $elementType
     * @throws Exception
     * @return Firegento_FlexCms_Model_Content
     */
    protected function _getNewContent($elementType)
    {
        /** @var $content Firegento_FlexCms_Model_Content */
        $content = Mage::getModel('firegento_flexcms/content');

        $content->setData(array(
            'content_type' => $elementType,
            'is_active' => false,
        ));

        $content->save();
        
        return $content;
    }

    /**
     * @param int $contentId
     * @throws Exception
     * @return Firegento_FlexCms_Model_Content
     */
    protected function _getExistingContent($contentId)
    {
        /** @var $content Firegento_FlexCms_Model_Content */
        $content = Mage::getModel('firegento_flexcms/content');

        $content->load($contentId);

        return $content;
    }

    /**
     * @param Firegento_FlexCms_Model_Content $content
     * @param string $area
     * @param string $layoutHandle
     * @throws Exception
     * @return Firegento_FlexCms_Model_Content_Link
     */
    protected function _getNewContentLink($content, $area, $layoutHandle)
    {
        /** @var $content Firegento_FlexCms_Model_Content_Link */
        $contentLink = Mage::getModel('firegento_flexcms/content_link');

        $contentLink->setData(array(
            'content_id' => $content->getId(),
            'area' => $area,
            'layout_handle' => $layoutHandle,
            'store_ids' => 0,
        ));

        $contentLink->save();

        $contentLink->addData($content->getData());
        
        return $contentLink;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/categories');
    }
}