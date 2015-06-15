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

if (@class_exists('AvS_ScopeHint_Block_AdminhtmlCatalogFormRendererFieldsetElement')) {
    class Firegento_FlexCms_Block_Adminhtml_Catalog_Form_Renderer_Fieldset_Element_Abstract extends AvS_ScopeHint_Block_AdminhtmlCatalogFormRendererFieldsetElement
    {}
} else {
    class Firegento_FlexCms_Block_Adminhtml_Catalog_Form_Renderer_Fieldset_Element_Abstract extends Mage_Adminhtml_Block_Catalog_Form_Renderer_Fieldset_Element
    {}
}

/**
 * FlexCms Content Renderer
 *
 * @category FireGento
 * @package  FireGento_FlexCms
 * @author   FireGento Team <team@firegento.com>
 */
class Firegento_FlexCms_Block_Adminhtml_Catalog_Form_Renderer_Fieldset_Element extends Firegento_FlexCms_Block_Adminhtml_Catalog_Form_Renderer_Fieldset_Element_Abstract
{
    protected $_changes = array();
    
    /**
     * Initialize block template
     */
    protected function _construct()
    {
        $category = Mage::registry('current_category');
        if (!$category) {
            return parent::_construct();
        }

        /** @var $changesObject Firegento_FlexCms_Model_Category_Changes */
        $changesObject = Mage::getModel('firegento_flexcms/category_changes')->loadByCategory($category);

        if (!$changesObject->getId()) {
            return parent::_construct();
        }
        
        $this->_changes = $changesObject->getChanges();
        
        $this->setTemplate('firegento/flexcms/element.phtml');
    }
    
    public function getChanges($name = null)
    {
        if (is_null($name)) {
            return $this->_changes;
        }
        if (isset($this->_changes[$name])) {
            return $this->_changes[$name];
        }
        return null;
    }
    
    /**
     * Retrieve element html
     *
     * @return string
     */
    public function getPublishedElementHtml()
    {   
        $origData = $this->getElement()->getData();
        $this->getElement()->setDisabled(true);
        $this->getElement()->setData('html_id', 'published_' . $this->getElement()->getData('html_id'));
        $this->getElement()->setData('name', 'published_' . $this->getElement()->getData('name'));
        $elementHtml = $this->getElementHtml();
        $this->getElement()->addData($origData);
        return $elementHtml;
    }
    
    /**
     * Retrieve element html
     *
     * @return string
     */
    public function getDraftElementHtml()
    {
        $value = $this->getChanges($this->getElement()->getData('name'));
        if (!is_null($value)) {
            $this->getElement()->setValue($value);
        }
        $this->getElement()->setDisabled(false);
        return $this->getElementHtml();
    }

    /**
     * @return bool
     */
    protected function _canPublishCategory()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/publish_categories');
    }
}