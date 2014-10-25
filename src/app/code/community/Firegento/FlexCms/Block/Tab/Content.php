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

        foreach ($sections as $section) {

            $fieldset = $form->addFieldset($section['key'], array('legend' => Mage::helper('firegento_flexcms')->__($section['title'])));

            $fieldset->addField('content_element_'.$section['key'], 'select', array(
                    'label' => Mage::helper('firegento_flexcms')->__('Content Element'),
                    'name' => 'content_element['.$section['key'].']',
                    'values' => Mage::helper('firegento_flexcms')->getFlexContents()
                ));
        }

        return parent::_prepareForm();
    }

}