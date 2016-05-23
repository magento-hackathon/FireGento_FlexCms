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
class Firegento_FlexCms_Block_Adminhtml_Form_Renderer_Element extends Mage_Adminhtml_Block_Widget_Form_Renderer_Element
{
    protected function _construct()
    {
        $this->setTemplate('firegento/flexcms/element/renderer.phtml');
    }

    public function getLink()
    {
        return $this->getData('link');
    }

    /**
     * @return Varien_Data_Form_Element_Abstract
     */
    public function getElement()
    {
        return $this->_element;
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
        $elementHtml = $this->getElement()->getElementHtml();
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
        $value = $this->getDraftValue();
        if (!is_null($value)) {
            if ($this->getElement()->getType() == 'checkbox') {
                $this->getElement()->setChecked($value);
            } else {
                $this->getElement()->setValue($value);
            }
        }
        if ($this->getElement()->getDisabled()) {
            $this->getElement()->addClass('disabled');
        }
        $this->getElement()->setDisabled(false);
        return $this->getElement()->getElementHtml();
    }

    /**
     * @return mixed
     */
    public function getPublishedValue()
    {
        return $this->getElement()->getValue();
    }

    /**
     * @return mixed
     */
    public function getDraftValue()
    {
        $content = Mage::getModel('firegento_flexcms/content')->load($this->getLink()->getDraftContentId());
        $fieldCode = $this->getElement()->getFieldCode();
        $value = $content->getData($fieldCode);
        if ($fieldCode == 'content' || is_null($value)) {
            $contentArray = $content->getContent();
            if (isset($contentArray[$fieldCode])) {
                $value = $contentArray[$fieldCode];
            }
        }
        return $value;
    }

    public function showBothFields()
    {
        if (is_null($this->getDraftValue())) {
            return false;
        }
        if ($this->getPublishedValue() != $this->getDraftValue()) {
            return true;
        }
        if ($this->getElement()->getType() == 'checkbox') {
            if ($this->getElement()->getChecked() != $this->getDraftValue()) {
                return true;
            }
        }
        return false;
    }
}