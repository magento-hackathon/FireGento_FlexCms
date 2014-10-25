<?php
/**
 * Form.php
 *
 * @category  magento
 * @package   magento_
 * @copyright Copyright (c) 2014 Unic AG (http://www.unic.com)
 * @author    juan.alonso@unic.com
 */

class Firegento_FlexCms_Block_Tab_Content extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare Form
     *
     * @return Parent _prepareForm
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $sections = Mage::helper('firegento_flexcms')->getFlexContentSectionsForm();

        foreach ($sections as $key => $section) {

            $fieldset = $form->addFieldset($key, array('legend' => Mage::helper('firegento_flexcms')->__($section['label'])));

            $layoutHandle = 'CATEGORY_'.$this->getCategoryId();
            $element = Mage::getModel('firegento_flexcms/content_link')->loadByHandleAndArea($layoutHandle, $key);

            $fieldset->addField('content_element_'.$key, 'select', array(
                    'label' => Mage::helper('firegento_flexcms')->__('Content Element'),
                    'name' => 'flexcms_element['.$key.'][content_id]',
                    'values' => Mage::helper('firegento_flexcms')->getFlexContents(),
                    'value' => ($element->getId()) ? $element->getContentId() : 0
                ));

            $fieldset->addField('sort_order'.$key, 'text', array(
                    'label' => Mage::helper('firegento_flexcms')->__('Sort Order'),
                    'name' => 'flexcms_element['.$key.'][sort_order]',
                    'value' => ($element->getId()) ? $element->getSortOrder() : ""
                ));

        }

        return parent::_prepareForm();
    }

    /**
     * Retrieve current category instance
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCategoryId()
    {
        if (Mage::registry('category')) {
            return Mage::registry('category')->getId();
        }
        return false;
    }

}