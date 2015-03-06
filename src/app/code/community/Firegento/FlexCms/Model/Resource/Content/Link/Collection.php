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
class Firegento_FlexCms_Model_Resource_Content_Link_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_storeId = 0;
    
    /**
     * Initialize collection
     *
     */
    public function _construct()
    {
        $this->_init('firegento_flexcms/content_link');
    }

    /**
     * @param int $storeId
     * @return Firegento_FlexCms_Model_Resource_Content_Link_Collection
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * join content from firegento_flexcms/content 1:1
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        
        foreach($this->_items as $item) { /** @var Firegento_FlexCms_Model_Content_Link $item */
            $item->setStoreId($this->_storeId);
            $item->addData($item->getContentModel()->getData());
        }
    }
}
