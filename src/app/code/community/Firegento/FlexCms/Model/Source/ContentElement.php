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
class Firegento_FlexCms_Model_Source_ContentElement
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();

        $contentCollection = Mage::getResourceModel('firegento_flexcms/content_collection')
            ->setOrder('content_type', 'asc')
            ->setOrder('title', 'asc');

        foreach($contentCollection as $content) {
            $options[] = array(
                'value' => $content->getId(),
                'label' => sprintf('%s: %s',
                    Mage::getSingleton('firegento_flexcms/source_contentType')->getOptionLabel($content->getContentType()),
                    $content->getTitle()
                ),
            );
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