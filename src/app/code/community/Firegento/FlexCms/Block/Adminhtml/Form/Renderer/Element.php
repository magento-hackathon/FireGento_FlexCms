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
        $value = $this->getLink()->getData($this->getElement()->getData('name'));
        if (!is_null($value)) {
            $this->getElement()->setValue($value);
        }
        $this->getElement()->setDisabled(false);
        return $this->getElement()->getElementHtml();
    }
}