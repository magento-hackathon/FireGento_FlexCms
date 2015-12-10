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
class Firegento_FlexCms_Adminhtml_HandleController extends Mage_Adminhtml_Controller_Action
{
    /**
     *
     */
    public function indexAction()
    {
        $this->_title(Mage::helper('cms')->__('CMS'))->_title($this->__('FlexCms Content By Handle'));

        $this->loadLayout();
        $this->_setActiveMenu('cms/flexcms_handle');
        $this->_addBreadcrumb(Mage::helper('cms')->__('CMS'), Mage::helper('firegento_flexcms')->__('FlexCms Content'));

        $this->_addContent($this->getLayout()->createBlock('firegento_flexcms/adminhtml_handle'));

        $this->renderLayout();
    }

    /**
     *
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Action for /admin/content/edit/
     * Edit content details
     *
     * @return void
     */
    public function editAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('firegento_flexcms/adminhtml_content_edit'));
        $this->renderLayout();
    }

    /**
     *
     */
    public function saveAction()
    {
        $contentId = $this->getRequest()->getParam('id', false);

        if ($data = $this->getRequest()->getPost()) {

            try {
                //  $content->save();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('firegento_flexcms')
                        ->__('Content was saved successfully'));
                $this->getResponse()->setRedirect($this->getUrl('*/*/'));
                return;
            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')
                    ->addError($e->getMessage());
            }
        }
        $this->_redirectReferer();

    }

    /**
     *
     */
    public function deleteAction()
    {
        $contentId = $this->getRequest()->getParam('id', false);

        try {
            Mage::getModel('firegento_flexcms/content')->setId($contentId)->delete();
            Mage::getSingleton('adminhtml/session')
                ->addSuccess(Mage::helper('firegento_flexcms')
                    ->__('Content was deleted successfully'));
            $this->getResponse()->setRedirect($this->getUrl('*/*/'));

            return;
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirectReferer();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/publish_categories');
    }
}