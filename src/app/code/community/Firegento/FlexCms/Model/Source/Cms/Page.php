<?php

class Firegento_FlexCms_Model_Source_Cms_Page extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        return Mage::getResourceModel('cms/page_collection')->load()->toOptionIdArray();
    }
}