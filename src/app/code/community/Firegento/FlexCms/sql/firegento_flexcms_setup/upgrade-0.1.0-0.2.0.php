<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('firegento_flexcms/content_link'))
    ->addColumn('flexcms_content_link_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Content Link Id')
    ->addColumn('content_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => false,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => false,
    ), 'Content Id')
    ->addColumn('layout_handle', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        'default'   => '',
    ), 'Layout Handle')
    ->addColumn('area', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        'default'   => '',
    ), 'Area')
    ->addColumn('store_ids', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Area')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => false,
        'unsigned'  => false,
        'nullable'  => false,
        'primary'   => false,
        'default'   => 0,
    ), 'Content Id')
    ->addIndex($installer->getIdxName('firegento_flexcms/content_link', array('layout_handle', 'store_ids')),
        array('layout_handle', 'store_ids')
    )
    ->addForeignKey($installer->getFkName('firegento_flexcms/content_link', 'content_id', 'firegento_flexcms/content', 'flexcms_content_id'),
        'content_id', $installer->getTable('firegento_flexcms/content'), 'flexcms_content_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Links between FlexCms Content and Magento Pages');

$installer->getConnection()->createTable($table);

$installer->endSetup();
