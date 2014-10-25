<?php

class Firegento_FlexCms_Block_Adminhtml_Content extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'firegento_flexcms';
        $this->_controller = 'adminhtml_content';
        $this->_headerText = Mage::helper('firegento_flexcms')->__('Content Updates');
        $this->_addButtonLabel = Mage::helper('firegento_flexcms')->__('Add new Content Update');
        parent::__construct();
    }
}
