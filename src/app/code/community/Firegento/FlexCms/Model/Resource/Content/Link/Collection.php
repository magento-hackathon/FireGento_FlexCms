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

    /**
     * join content from firegento_flexcms/content 1:1
     */
    public function joinContentData(){
        $this->getSelect()->join(
            array('reference_table' => Mage::getSingleton('core/resource')->getTableName('firegento_flexcms/content')),
            'reference_table.flexcms_content_id=main_table.content_id',
            array('reference_table.*')
        );
    }
}
