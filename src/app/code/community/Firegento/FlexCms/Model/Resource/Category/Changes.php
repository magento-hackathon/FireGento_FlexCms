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
class Firegento_FlexCms_Model_Resource_Category_Changes extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource
     *
     */
    protected function _construct()
    {
        $this->_init('firegento_flexcms/category_changes', 'flexcms_category_changes_id');
    }

    /**
     * Perform actions after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getChanges() && !is_array($object->getChanges())) {
            $object->setChanges(Zend_Json::decode($object->getChanges()));
        }
        return parent::_afterLoad($object);
    }

    /**
     * Perform actions before object save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (is_array($object->getChanges()) && sizeof($object->getChanges())) {
            $object->setChanges(Zend_Json::encode($object->getChanges()));
        }
        return parent::_beforeSave($object);
    }

    /**
     * Load an object
     *
     * @param Mage_Core_Model_Abstract $object
     * @param int $categoryId
     * @param int $storeId
     * @return Firegento_FlexCms_Model_Resource_Category_Changes
     */
    public function loadByCategory(Mage_Core_Model_Abstract $object, $categoryId, $storeId = 0)
    {
        $read = $this->_getReadAdapter();
        if ($read && !is_null($categoryId)) {
            $select = $this->_getReadAdapter()->select()
                ->from($this->getMainTable())
                ->where('category_id=?', $categoryId)
                ->where('store_id=?', $storeId);

            $data = $read->fetchRow($select);

            if ($data) {
                $object->setData($data);
            }
        }

        $this->_afterLoad($object);

        return $this;
    }

}
