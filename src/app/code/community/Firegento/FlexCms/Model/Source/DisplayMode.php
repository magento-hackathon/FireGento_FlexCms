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