<?php

class Firegento_FlexCms_Model_Resource_Content_Link_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize collection
     *
     */
    public function _construct()
    {
        $this->_init('firegento_flexcms/content_link');
    }
}
