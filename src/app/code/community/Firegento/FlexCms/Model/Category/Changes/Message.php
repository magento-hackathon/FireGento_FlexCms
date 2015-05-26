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

class Firegento_FlexCms_Model_Category_Changes_Message extends Varien_Object
{
    /**
     * @param Mage_Admin_Model_User $adminUser
     */
    public function setAdminUser($adminUser)
    {
        $this->setData('admin_user', $adminUser);
    }

    /**
     * @return Mage_Admin_Model_User
     */
    public function getAdminUser()
    {
        return $this->getData('admin_user');
    }
    
    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->setData('text', $text);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->getData('text');
    }
    
    /**
     * @param Zend_Date $date
     */
    public function setDate($date)
    {
        $this->setData('date', $date);
    }

    /**
     * @return Zend_Date
     */
    public function getDate()
    {
        return $this->getData('date');
    }
    
    public function getFormattedDate()
    {
        $date = $this->getDate();
        return Mage::helper('core')->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_SHORT, true);
    }
}