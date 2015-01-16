<?php

class Firegento_FlexCms_Block_Adminhtml_Handle extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'firegento_flexcms';
        $this->_controller = 'adminhtml_handle';
        $this->_headerText = Mage::helper('firegento_flexcms')->__('Content Updates By Handle');
        $this->_addButtonLabel = Mage::helper('firegento_flexcms')->__('Add new Content Element');
        parent::__construct();
    }
}
