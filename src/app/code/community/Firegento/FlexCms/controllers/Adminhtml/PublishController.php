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
class Firegento_FlexCms_Adminhtml_PublishController extends Mage_Adminhtml_Controller_Action
{
    /**
     *
     */
    public function requestPublishPostAction()
    {
        $post = $this->getRequest()->getPost();
        if ( $post ) {

            $categoryId = $this->getRequest()->getParam('category_id');
            $storeId = $this->getRequest()->getParam('store_id');
            $store = Mage::app()->getStore($storeId);

            /** @var Mage_Catalog_Model_Category $category */
            $category = Mage::getModel('catalog/category')->setStoreId($storeId)->load($categoryId);

            $adminUserId = $this->getRequest()->getParam('publisher');

            /** @var $adminUser Mage_Admin_Model_User */
            $adminUser = Mage::getModel('admin/user')->load($adminUserId);

            /** @var $currentAdminUser Mage_Admin_Model_User */
            $currentAdminUser = Mage::getSingleton('admin/session')->getUser();

            $comment = $this->getRequest()->getParam('comment');

            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $data = array(
                    'current_admin_user' => $currentAdminUser,
                    'admin_user' => $adminUser,
                    'category' => $category,
                    'category_path' => $this->_getCategoryPath($category),
                    'store' => $store,
                    'comment' => nl2br($comment),
                    'url' => $this->getUrl('adminhtml/catalog_category/'),
                );

                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($currentAdminUser->getEmail())
                    ->sendTransactional(
                        Mage::getStoreConfig('firegento_flexcms/workflow/publication_request_email_template'),
                        Mage::getStoreConfig('firegento_flexcms/workflow/publication_request_sender_email_identity'),
                        $adminUser->getEmail(),
                        $adminUser->getFirstname() . ' ' . $adminUser->getLastname(),
                        $data
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('firegento_flexcms')->__('The request has been sent. The selected user will be notified.'));
                echo 1;

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                echo 0;
                return;
            }

        } else {
            $this->_redirect('*/*/');
        }
    }

    /**
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    protected function _getCategoryPath($category)
    {
        $categoryPathIds = $category->getPathIds();
        array_shift($categoryPathIds);
        
        $categoryNames = array();
        foreach($categoryPathIds as $categoryId) {
            $categoryNames[] = Mage::getResourceSingleton('catalog/category')->getAttributeRawValue($categoryId, 'name', $category->getStoreId());
        }
        
        return implode(' > ', $categoryNames);
    }
}