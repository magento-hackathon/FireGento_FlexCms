<?php

class Firegento_FlexCms_Model_Source_ContentType
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        foreach(Mage::getStoreConfig('firegento_flexcms/types') as $key => $type) {
            $options[] = array('value' => $key, 'label' => $type['label']);
        }
        
        return $options;
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