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
$installer = Mage::getResourceModel('catalog/setup','catalog_setup');

/* @var $installer Mage_Catalog_Model_Resource_Setup */

$installer->startSetup();

$_helper = Mage::helper('firegento_flexcms');

$installer->updateAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'flexcms_url_external',
    'is_global',
    0
);
$installer->updateAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'flexcms_cms_page',
    'is_global',
    0
);

$installer->endSetup();