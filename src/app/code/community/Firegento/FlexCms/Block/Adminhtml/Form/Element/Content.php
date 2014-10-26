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
class Firegento_FlexCms_Block_Adminhtml_Form_Element_Content extends Varien_Data_Form_Element_Abstract
{

    /**
     * @var Firegento_FlexCms_Model_Resource_Content_Link_Collection
     */
    protected $_linkCollection;

    /**
     * Element type classes
     *
     * @var array
     */
    protected $_types = array();

    /**
     * @return string
     */
    public function getHtml()
    {
        $html = $this->_getAddHtml();

        foreach ($this->getAreaLinksCollection() as $link) {
            $renderer = $this->_getRenderer($link);
            $renderer->setElements($this->_getElements($link));
            $html .= $renderer->toHtml();
        }

        return $html;
    }

    public function addType($type, $className){

    }

    /**
     * @return Firegento_FlexCms_Model_Resource_Content_Link_Collection
     */
    protected function getAreaLinksCollection()
    {
        $this->_linkCollection = Mage::getModel('firegento_flexcms/content_link')->getCollection()
            ->addFieldToFilter('area', $this->getArea())
            ->addFieldToFilter('layout_handle', $this->getLayoutHandle())
            ->setOrder('sort_order', 'asc')
            ->joinContentData();
        return $this->_linkCollection;
    }

    /**
     * @param   string $elementId
     * @param   string $type
     * @param   array $config
     * @return Varien_Data_Form_Element_Abstract
     */
    protected function _getField($elementId, $type, $config)
    {
        if (isset($this->_types[$type])) {
            $className = $this->_types[$type];
        }
        else {
            $className = 'Varien_Data_Form_Element_'.ucfirst(strtolower($type));
        }

        $element = new $className($config);
        $element->setId($elementId);
        $element->setForm($this->_getForm());
        return $element;
    }

    /**
     * @return Varien_Object Form Dummy
     */
    protected function _getForm()
    {
        return new Varien_Data_Form(array(
            'html_id_prefix' => 'flexform_area_' . $this->getArea() . '_',
        ));
    }

    /**
     * @param Firegento_FlexCms_Test_Model_Content_Link $link
     * @return Mage_Core_Block_Abstract
     */
    protected function _getRenderer($link)
    {
        return Mage::app()->getLayout()->createBlock(
            'adminhtml/template',
            'flexcms_content_renderer_' . $link->getId(),
            array(
                'template' => 'firegento/flexcms/element/content.phtml',
                'link' => $link,
            )
        );
    }

    /**
     * @param Firegento_FlexCms_Test_Model_Content_Link $link
     * @return Varien_Data_Form_Element_Abstract[]
     */
    protected function _getElements($link)
    {
        $elements = array();

        $elements[] = $this->_getField(
            'flexcms_content_link_' . $link->getId() . '_field_title',
            'text',
            array(
                'label' => Mage::helper('firegento_flexcms')->__('Title (not displayed on frontend)'),
                'name' => 'flexcms_element[' . $link->getId() . '][title]',
                'value' => $link->getTitle(),
                'class' => 'flexcms_element flexcms_element_title',
            )
        );

        $contentType = $link->getContentType();
        $contentTypeConfig = Mage::getStoreConfig('firegento_flexcms/types/' . $contentType);

        if (is_array($contentTypeConfig["fields"])) {
            foreach ($contentTypeConfig['fields'] as $fieldCode => $fieldConfig) {

                $content = $link->getContent();
                $elementConfig = array(
                    'label' => Mage::helper('firegento_flexcms')->__($fieldConfig['label']),
                    'name' => 'flexcms_element[' . $link->getId() . '][' . $fieldCode . ']',
                    'value' => ((isset($content[$fieldCode])) ? $content[$fieldCode] : ''),
                    'class' => 'flexcms_element flexcms_element_' . $fieldConfig['frontend_type'],
                );
                foreach ($fieldConfig as $key => $value) {
                    if (in_array($key, array('label', 'frontend_type'))) {
                        continue;
                    }
                    $elementConfig[$key] = $value;
                }
                if ($fieldConfig['frontend_type'] == 'editor') {
                    $elementConfig['config'] = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array(
                        'mode' => 'exact',
                    ));
                }


                $elements[] = $this->_getField(
                    'flexcms_content_link_' . $link->getId() . '_field_' . $fieldCode,
                    $fieldConfig['frontend_type'],
                    $elementConfig
                );
            }
        }
        $elements[] = $this->_getField(
            'flexcms_content_link_' . $link->getId() . '_field_sort_order',
            'text',
            array(
                'label' => Mage::helper('firegento_flexcms')->__('Sort Order'),
                'name' => 'flexcms_element[' . $link->getId() . '][sort_order]',
                'class' => 'flexcms_element flexcms_element_sort_order',
                'value' => 'true'
            )
        );
        $elements[] = $this->_getField(
            'flexcms_content_link_' . $link->getId() . '_field_delete',
            'checkbox',
            array(
                'label' => Mage::helper('firegento_flexcms')->__('Delete this element'),
                'name' => 'flexcms_element[' . $link->getId() . '][delete]',
                'class' => 'flexcms_element flexcms_element_delete',
                'value' => 'true'
            )
        );
        return $elements;
    }

    /**
     * @return string
     */
    protected function _getAddHtml()
    {
        /** @var $block Firegento_FlexCms_Block_Adminhtml_Add */
        $block = Mage::app()->getLayout()->createBlock(
            'firegento_flexcms/adminhtml_add',
            'flexform_add_' . $this->getArea(),
            array('area_code' => $this->getArea())
        );
        return $block->toHtml();
    }
}