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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('firegento_flexcms/content_data'))
    ->addColumn('flexcms_content_data_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Content Data Id')
    ->addColumn('content_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => false,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => false,
    ), 'Content Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => false,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => false,
    ), 'Store Id')
    ->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => false,
        'default'   => '',
    ), 'Content (JSON)')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => false,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => false,
    ), 'Is Active')
    ->addIndex($installer->getIdxName('firegento_flexcms/content_data', array('content_id', 'store_id')),
        array('content_id', 'store_id')
    )
    ->addForeignKey($installer->getFkName('firegento_flexcms/content_data', 'content_id', 'firegento_flexcms/content', 'flexcms_content_id'),
        'content_id', $installer->getTable('firegento_flexcms/content'), 'flexcms_content_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey($installer->getFkName('firegento_flexcms/content_data', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Store-dependant Data of FlexCms Content Elements');

$installer->getConnection()->createTable($table);

$installer->getConnection()->dropColumn(
    $installer->getTable('firegento_flexcms/content'),
    'content'
);

$installer->getConnection()->addColumn(
    $installer->getTable('firegento_flexcms/content'),
    'is_reusable',
    "tinyint(1) unsigned NOT NULL default '0'"
);

$installer->getConnection()->addColumn(
    $installer->getTable('firegento_flexcms/content'),
    'is_deleted',
    "tinyint(1) unsigned NOT NULL default '0'"
);

$installer->getConnection()->addColumn(
    $installer->getTable('firegento_flexcms/content'),
    'is_active_from',
    'datetime'
);

$installer->getConnection()->addColumn(
    $installer->getTable('firegento_flexcms/content'),
    'is_active_until',
    'datetime'
);

$installer->endSetup();
