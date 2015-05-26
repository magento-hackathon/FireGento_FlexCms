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
 * FlexCms Content Renderer
 *
 * @category FireGento
 * @package  FireGento_FlexCms
 * @author   FireGento Team <team@firegento.com>
 */
class Firegento_FlexCms_Model_Source_Publisher
{
    /**
     * Options getter
     *
     * @param boolean $addEmptyOption
     * @return array
     */
    public function toOptionArray($addEmptyOption = false)
    {
        $options = array();
        if ($addEmptyOption) {
            $options[] = array(
                'value' => '',
                'label' => Mage::helper('core')->__('-- Please Select --'),
            );
        }

        $allowedAdminRoleIds = $this->_getPublishingAdminRoleIds();
                
        /** @var Mage_Admin_Model_User $currentAdminUser */
        $currentAdminUser = Mage::getSingleton('admin/session')->getUser();
        
        /** @var $adminUserCollection Mage_Admin_Model_Resource_User_Collection */
        $adminUserCollection = Mage::getResourceModel('admin/user_collection');
        $adminUserCollection->addFieldToFilter('is_active', 1);
        $adminUserCollection->addFieldToFilter('user_id', array('neq' => $currentAdminUser->getId()));
        foreach($adminUserCollection as $adminUser) {
            
            if (!in_array($adminUser->getRole()->getId(), $allowedAdminRoleIds)) {
                continue;
            }
            
            if ($allowedWebsites = $currentAdminUser->getAllowedWebsites() && $adminUser->getAllowedWebsites()) {
                $currentAllowedWebsites = explode(',', $currentAdminUser->getAllowedWebsites());
                $userAllowedWebsites = explode(',', $adminUser->getAllowedWebsites());
                if (!sizeof(array_intersect($currentAllowedWebsites, $userAllowedWebsites))) {
                    continue;
                }    
            }
            
            $options[] = array(
                'value' => $adminUser->getId(),
                'label' => $adminUser->getFirstname() . ' ' . $adminUser->getLastname(),
            );
        }
        return $options;
    }
    
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $options = array();
        foreach($this->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }

        return $options;
    }

    /**
     * Get all Roles (admin user groups) which are allowed to publish categories
     * 
     * @return int[]
     */
    protected function _getPublishingAdminRoleIds()
    {
        $publishingAdminRoleIds = array();

        /** @var $adminRules Mage_Admin_Model_Resource_Rules_Collection */
        $adminRules = Mage::getResourceModel('admin/rules_collection');
        $adminRules->addFieldToFilter('role_type', 'G');
        $adminRules->addFieldToFilter('permission', 'allow');
        $adminRules->addFieldToFilter('resource_id', array('in' => array('all', 'admin/catalog/publish_categories')));

        foreach ($adminRules as $adminRule) {
            
            $publishingAdminRoleIds[$adminRule->getRoleId()] = $adminRule->getRoleId();
        }
        return $publishingAdminRoleIds;
    }
}