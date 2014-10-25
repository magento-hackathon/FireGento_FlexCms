<?php

$installer = Mage::getResourceModel('catalog/setup','catalog_setup');

/* @var $installer Mage_Catalog_Model_Resource_Setup */

$installer->startSetup();

$installer->updateAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'display_mode',
    'source_model',
    'firegento_flexcms/source_displayMode'
);

$installer->endSetup();