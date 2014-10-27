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
class Firegento_FlexCms_Block_Adminhtml_Tab_Content extends Mage_Adminhtml_Block_Widget_Form
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
        $layoutHandle = 'CATEGORY_' . $this->getCategoryId();

        foreach ($sections as $key => $section) {

            $fieldset = $form->addFieldset($key, array('legend' => Mage::helper('firegento_flexcms')->__($section['label'])));

            $fieldset->addType('content', 'Firegento_FlexCms_Block_Adminhtml_Form_Element_Content');

            if ($this->getCategoryId()) {
                $fieldset->addField('content_' . $key, 'content', array(
                    'layout_handle' => $layoutHandle,
                    'area' => $key,
                ));
            }
        }

        return parent::_prepareForm();
    }

    /**
     * Retrieve current category instance
     *
     * @return int
     */
    public function getCategoryId()
    {
        if (Mage::registry('category')) {
            return Mage::registry('category')->getId();
        }
        return 0;
    }

}