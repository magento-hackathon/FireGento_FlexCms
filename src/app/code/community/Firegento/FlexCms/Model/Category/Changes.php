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
 * @copyright 2015 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */

/**
 * FlexCms Category Changes Model
 *
 * @category FireGento
 * @package  FireGento_FlexCms
 * @author   FireGento Team <team@firegento.com>
 */
class Firegento_FlexCms_Model_Category_Changes extends Mage_Core_Model_Abstract
{
    /** @var Firegento_FlexCms_Model_Category_Changes_Message[] */
    protected $_messages = array();
    
    protected function _construct()
    {
        $this->_init('firegento_flexcms/category_changes');
    }

    public function loadByCategory(Mage_Catalog_Model_Category $category)
    {
        $this->_getResource()->loadByCategory($this, $category->getId(), $category->getStoreId());
        $this->_afterLoad();
        return $this;
    }
    
    protected function _afterLoad()
    {
        $messages = @unserialize($this->getData('messages'));
        if (is_array($messages)) {
            foreach($messages as $message) {
                $this->addMessage($message['text'], $message['admin_user'], $message['date']);
            }
        }
        return parent::_afterLoad();
    }
    
    protected function _beforeSave()
    {
        $messages = array();
        if (sizeof($this->_messages)) {
            foreach($this->_messages as $message) {
                $messages[] = array(
                    'text' => $message->getText(),
                    'admin_user' => $message->getAdminUser()->getId(),
                    'date' => $message->getDate()->get('YYYY-MM-dd HH:mm:ss'),
                );
            }
        }
        
        $this->setData('messages', serialize($messages));
    }

    /**
     * @return Firegento_FlexCms_Model_Category_Changes_Message[]
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     * @param string $text
     * @param Mage_Admin_Model_User|int|null $adminUser
     * @param Zend_Date|string|null $date
     * @return Firegento_FlexCms_Model_Category_Changes
     */
    public function addMessage($text, $adminUser = null, $date = null)
    {
        /** @var $message Firegento_FlexCms_Model_Category_Changes_Message */
        $message = Mage::getModel('firegento_flexcms/category_changes_message');
        $message->setText($text);
        if (is_null($adminUser)) {
            $message->setAdminUser(Mage::getSingleton('admin/session')->getUser());
        } else {
            if (!$adminUser instanceof Mage_Admin_Model_User) {
                $adminUser = Mage::getModel('admin/user')->load($adminUser);
            }
            $message->setAdminUser($adminUser);
        }
        
        if (is_null($date)) {
            $date = new Zend_Date();
        } else if (!$date instanceof Zend_Date) {
            $date = new Zend_Date($date, 'YYYY-MM-dd HH:mm:ss');
        }
        
        $message->setDate($date);
        
        $this->_messages[] = $message; 
        return $this;
    }
}