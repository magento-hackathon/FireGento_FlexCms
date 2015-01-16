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