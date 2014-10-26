<?php

class Firegento_FlexCms_Model_Source_DisplayMode extends Mage_Catalog_Model_Category_Attribute_Source_Mode
{
    const CONTENT       = 'FLEXCMS_CONTENT';
    const CMS_PAGE      = 'FLEXCMS_CMS_PAGE';
    const URL_EXTERNAL  = 'FLEXCMS_URL_EXTERNAL';

    public function getAllOptions()
    {
        if (!$this->_options) {
            parent::getAllOptions();
            array_push(
                $this->_options,
                array(
                    'value' => self::CONTENT,
                    'label' => Mage::helper('firegento_flexcms')->__('Content (FlexCms)'),
                ),
                array(
                    'value' => self::CMS_PAGE,
                    'label' => Mage::helper('firegento_flexcms')->__('CMS Page (FlexCms)'),
                ),
                array(
                    'value' => self::URL_EXTERNAL,
                    'label' => Mage::helper('firegento_flexcms')->__('External URL (FlexCms)'),
                )
            );
        }

        return $this->_options;
    }
}