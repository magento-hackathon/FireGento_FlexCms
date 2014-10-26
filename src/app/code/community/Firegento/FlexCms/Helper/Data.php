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
class Firegento_FlexCms_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * config xml node
     */
    const Firegento_Flexcms_Areas_Xml = "default/firegento_flexcms/areas";

    /**
     * product view layout handle prefix
     */
    const DYNAMIC_HANDLE_PREFIX_PRODUCT = 'PRODUCT_';

    /**
     * category view layout handle prefix
     */
    const DYNAMIC_HANDLE_PREFIX_CATEGORY = 'CATEGORY_';

    /**
     * cms-page view layout handle prefix
     */
    const DYNAMIC_HANDLE_PREFIX_CMS_PAGES = 'CMSPAGE_';

    /**
     * @var array
     */
    private $_contentElementArray = array();

    /**
     * Get Content sections
     *
     * @return array
     */
    public function getFlexContentSectionsForm()
    {
        $areas = Mage::getConfig()->getNode(self::Firegento_Flexcms_Areas_Xml)->asArray();
        return $areas;

    }

    /**
     * Get existing flex cms contents
     *
     * @return array
     */
    public function getFlexContents()
    {
        if (!$this->_contentElementArray) {
            $contentCollection    = Mage::getResourceModel('firegento_flexcms/content_collection')->load();

            $this->_contentElementArray[] = array(
                'value' => 0,
                'label' => 'No content'
            );

            foreach ($contentCollection as $content) {
                $this->_contentElementArray[] = array(
                    'value' => $content->getFlexcmsContentId(),
                    'label' => $content->getTitle()
                );
            }
        }

        return $this->_contentElementArray;
    }

}