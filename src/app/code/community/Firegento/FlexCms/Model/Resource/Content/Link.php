<?php

class Firegento_FlexCms_Model_Resource_Content extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource
     *
     */
    protected function _construct()
    {
        $this->_init('firegento_flexcms/content_link', 'flexcms_content_link_id');
    }
}
