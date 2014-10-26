<?php

$installer = Mage::getResourceModel('catalog/setup','catalog_setup');

/* @var $installer Mage_Catalog_Model_Resource_Setup */

$installer->startSetup();

$_helper = Mage::helper('firegento_flexcms');

$installer->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'flexcms_cms_page',
    array(
        'group'      => 'Display Settings',
        'input'      => 'select',
        'label'      => 'CMS Page (FlexCms)',
        'required'   => 0,
        'sort_order' => '15',
        'source'     => 'firegento_flexcms/source_cms_page',
        'type'       => 'text',
        'note'       => $_helper->__('Only used when "Display Mode" is set to "CMS Page (FlexCms)"')
    )
);

$installer->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'flexcms_url_external',
    array(
        'group'      => 'Display Settings',
        'label'      => 'External URL (FlexCms)',
        'required'   => 0,
        'sort_order' => '15',
        'type'       => 'text',
        'note'       => $_helper->__('Only used when "Display Mode" is set to "External URL (FlexCms)"')
    )
);

$installer->updateAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'display_mode',
    'source_model',
    'firegento_flexcms/source_displayMode'
);

$installer->endSetup();