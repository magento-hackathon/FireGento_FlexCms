<?php

class Firegento_FlexCms_Model_Source_HandleType
{
    const CONTENT_TYPE_PRODUCT = 'product';
    const CONTENT_TYPE_CATEGORY = 'category';
    const CONTENT_TYPE_CMS_PAGE = 'cms_page';
    const CONTENT_TYPE_OTHER = 'other';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::CONTENT_TYPE_PRODUCT, 'label'=>Mage::helper('firegento_flexcms')->__('Product')),
            array('value' => self::CONTENT_TYPE_CATEGORY, 'label'=>Mage::helper('firegento_flexcms')->__('Category')),
            array('value' => self::CONTENT_TYPE_CMS_PAGE, 'label'=>Mage::helper('firegento_flexcms')->__('CMS Page')),
            array('value' => self::CONTENT_TYPE_OTHER, 'label'=>Mage::helper('firegento_flexcms')->__('Other')),
        );
    }

    /**
     * @param string $value
     * @return string
     */
    public function getOptionLabel($value) 
    {
        $options = $this->toArray();
        if (isset($options[$value])) {
            return $options[$value];
        }
        return '';
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $options = array();
        foreach($this->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }
        
        return $options;
    }
}