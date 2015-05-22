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
 * FlexCms Observer
 *
 * @category FireGento
 * @package  FireGento_FlexCms
 * @author   FireGento Team <team@firegento.com>
 */
class Firegento_FlexCms_Model_Observer
{
    protected $_attributesUsingDefault = null;
    protected $_attributesUsingConfig = null;

    /**
     * add CMSPAGE_{ID} handle to layout on cms page render
     *
     * @param Varien_Event_Observer $observer
     * @return Varien_Event_Observer
     */
    public function cmsPageRender(Varien_Event_Observer $observer)
    {
        $observer->getControllerAction()->getLayout()->getUpdate()
            ->addHandle('CMSPAGE_' . $observer->getPage()->getId());
    }

    /**
     * Add tab for Category content
     *
     * @param Varien_Event_Observer $observer
     */
    public function adminhtmlCatalogCategoryTabs(Varien_Event_Observer $observer)
    {
        $tabs = $observer->getTabs();

        $tabs->addTab('content', array(
            'label' => Mage::helper('firegento_flexcms')->__('FlexCms'),
            'content' => $tabs->getLayout()->createBlock(
                'firegento_flexcms/adminhtml_tab_content',
                'flexcms.content.form'
            )->toHtml(),
        ));
    }


    /**
     * Save category flex content
     *
     * @param Varien_Event_Observer $observer
     */
    public function catalogCategorySaveCommitAfter(Varien_Event_Observer $observer)
    {
        $params = new Varien_Object(Mage::app()->getRequest()->getParams());

        if (!$params->getFlexcmsElement()) {
            return;
        }

        foreach ($params->getFlexcmsElement() as $linkId => $fields) {

            $contentLink = Mage::getModel('firegento_flexcms/content_link')->load($linkId);

            $contentLink->updateFields($fields);
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function addFlexCmsUrlAttributes(Varien_Event_Observer $observer)
    {
        $observer->getCategoryCollection()->addAttributeToSelect(
            array('display_mode', 'flexcms_cms_page', 'flexcms_url_external')
        );
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function catalogCategoryCollectionLoadAfter(Varien_Event_Observer $observer)
    {
        /* @var $collection Mage_Catalog_Model_Resource_Category_Collection */
        $collection = $observer->getCategoryCollection();

        foreach ($collection as $category) {
            $this->_checkSetUrlUpdate($category);
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function catalogCategoryLoadAfter(Varien_Event_Observer $observer)
    {
        $this->_checkSetUrlUpdate($observer->getCategory());
        $this->_updateDisplayMode($observer->getCategory());
    }

    /**
     * @param Mage_Catalog_Model_Category $category
     */
    protected function _updateDisplayMode($category)
    {
        switch ($category->getDisplayMode()) {
            case Firegento_FlexCms_Model_Source_DisplayMode::CONTENT:
                $category->setDisplayMode(Mage_Catalog_Model_Category::DM_PAGE);
                break;
            case Firegento_FlexCms_Model_Source_DisplayMode::CONTENT_AND_PRODUCTS:
                $category->setDisplayMode(Mage_Catalog_Model_Category::DM_PRODUCT);
                break;
        }
    }

    /**
     * @param Mage_Catalog_Model_Category $category
     */
    protected function _checkSetUrlUpdate($category)
    {
        if (
            $category->getDisplayMode() === Firegento_FlexCms_Model_Source_DisplayMode::CMS_PAGE
            && $category->getFlexcmsCmsPage()
        ) {
            $category->setUrl(Mage::helper('cms/page')->getPageUrl($category->getFlexcmsCmsPage()));
        }
        if (
            $category->getDisplayMode() === Firegento_FlexCms_Model_Source_DisplayMode::URL_EXTERNAL
            && $category->getFlexcmsUrlExternal()
        ) {
            $category->setUrl($category->getFlexcmsUrlExternal());
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function catalogCategoryPrepareSave(Varien_Event_Observer $observer)
    {
        /** @var Mage_Catalog_Model_Category $category */
        $category = $observer->getCategory();

        if ($this->_shouldPublishCategory()) {
            if ($category->getId()) {
                /** @var $changesObject Firegento_FlexCms_Model_Category_Changes */
                $changesObject = Mage::getModel('firegento_flexcms/category_changes')->loadByCategory($category);
                if ($changesObject->getId()) {
                    $changesObject->delete();
                }
            }
            return;
        }

        /** @var Mage_Core_Controller_Request_Http $request */
        $request = $observer->getRequest();

        if ($category->getId()) {
            /** @var $changesObject Firegento_FlexCms_Model_Category_Changes */
            $changesObject = Mage::getModel('firegento_flexcms/category_changes')->loadByCategory($category);
            if (!$changesObject->getId()) {
                $changesObject->setCategoryId($category->getId());
                $changesObject->setStoreId($category->getStoreId());
            }

            $changes = array();
            foreach ($category->getData() as $key => $value) {
                if (in_array($key, array('id', 'path_ids'))) {
                    continue;
                }
                $origValue = $category->getOrigData($key);
                if ($this->_isUsingDefaultValue($request, $key)) {
                    $value = $category->getResource()->getAttributeRawValue($category->getId(), $key, 0);
                }
                if ($origValue != $value || (is_null($origValue) && $value)) {
                    $changes[$key] = $value;
                    if (is_null($origValue)) {
                        $category->setData($key, false);
                    } else {
                        $category->setData($key, $origValue);
                    }
                }
            }
            if (sizeof($changes)) {
                $changesObject->setChanges($changes)->save();
            } else {
                if ($changesObject->getId()) {
                    $changesObject->delete();
                }
            }

        } else {
            if ($category->getIsActive()) {

                $category->setIsActive(false);
                $category->setIsDraft(true);
                $category->setOriginalIsActive(true);
            }
        }
    }

    /**
     * Save category draft data for new categories
     *
     * @param Varien_Event_Observer $observer
     */
    public function catalogCategorySaveAfter(Varien_Event_Observer $observer)
    {
        if ($this->_shouldPublishCategory()) {
            return;
        }

        /** @var Mage_Catalog_Model_Category $category */
        $category = $observer->getCategory();
        if ($category->getOriginalIsActive()) {
            /** @var $changesObject Firegento_FlexCms_Model_Category_Changes */
            $changesObject = Mage::getModel('firegento_flexcms/category_changes')->loadByCategory($category);
            $changesObject->setCategoryId($category->getId());
            $changesObject->setStoreId($category->getStoreId());
            $changesObject->setChanges(array('is_active' => 1));
            $changesObject->save();
        }
    }

    /**
     * @return bool
     */
    protected function _canPublishCategory()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/publish_categories');
    }

    /**
     * @return bool
     */
    protected function _shouldPublishCategory()
    {
        return Mage::app()->getRequest()->getParam('publish') && $this->_canPublishCategory();
    }

    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @param string $attributeCode
     * @return boolean
     */
    protected function _isUsingDefaultValue($request, $attributeCode)
    {
        if (is_null($this->_attributesUsingDefault)) {
            $this->_attributesUsingDefault = array();
            if ($useDefaults = $request->getPost('use_default')) {
                $this->_attributesUsingDefault = $useDefaults;
            }
        }

        return in_array($attributeCode, $this->_attributesUsingDefault);
    }

    public function coreBlockAbstractPrepareLayoutAfter(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Category_Edit_Form) {
            if ($this->_canPublishCategory() && !$block->getCategory()->isReadonly()) {
                $block->addAdditionalButton('save_publish_button', array(
                        'label' => Mage::helper('firegento_flexcms')->__('Save and Publish Category'),
                        'onclick' => "categoryPublish('" . $block->getSaveUrl() . "', true)",
                        'class' => 'save'
                    )
                );

                $block->setChild('save_button',
                    $block->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label'     => Mage::helper('catalog')->__('Save Draft'),
                            'onclick'   => "categorySubmit('" . $block->getSaveUrl() . "', true)",
                            'class' => 'save'
                        ))
                );

            }
        }
    }
}