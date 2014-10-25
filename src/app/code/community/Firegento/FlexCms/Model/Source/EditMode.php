<?php

class Firegento_FlexCms_Model_Source_ContentMode
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'simple', 'label'=>Mage::helper('firegento_flexcms')->__('Simple')),
            array('value' => 'advanced', 'label'=>Mage::helper('firegento_flexcms')->__('Advanced')),
        );
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