<?php

class Firegento_FlexCms_Model_Source_DisplayMode extends Mage_Catalog_Model_Category_Attribute_Source_Mode
{
    const URL       = 'URL';
    const CONTENT   = 'CONTENT';

    public function getAllOptions()
    {
        if (!$this->_options) {
            parent::getAllOptions();
            array_push(
                $this->_options,
                array(
                    'value' => self::URL,
                    'label' => Mage::helper('firegento_flexcms')->__('URL (FlexCms)'),
                ),
                array(
                    'value' => self::CONTENT,
                    'label' => Mage::helper('firegento_flexcms')->__('Content (FlexCms)'),
                )
            );
        }

        return $this->_options;
    }
}