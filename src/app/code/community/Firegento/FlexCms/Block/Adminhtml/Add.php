<?php
/**
 * integer_net Magento Module
 *
 * @category   Firegento
 * @package    Firegento_FlexCms
 * @copyright  Copyright (c) 2014 integer_net GmbH (http://www.integer-net.de/)
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */ 
class Firegento_FlexCms_Block_Adminhtml_Add extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        $this->setTemplate('firegento/flexcms/add.phtml');
    }
    
    public function getContentTypes()
    {
        return Mage::getSingleton('firegento_flexcms/source_contentType')->toArray();
    }
    
    public function getAjaxUrl($areaCode)
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/flexcms/new', array(
            'area' => $areaCode,
            'layouthandle' => $this->_getLayoutHandle(),
        ));
    }
    
    protected function _getLayoutHandle()
    {
        if (Mage::registry('category')) {
            return 'CATEGORY_' . Mage::registry('category')->getId();
        }
    }
}