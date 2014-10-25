<?php

class Firegento_FlexCms_Model_Content extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('firegento_flexcms/content');
    }
    
    protected function _beforeSave()
    {
        if (is_array($this->getContent())) {
            $this->setContent(Zend_Json::encode($this->getContent()));
        }
        return parent::_beforeSave();
    }
    
    protected function _afterLoad()
    {
        if (!is_array($this->getContent())) {
            $this->setContent(Zend_Json::decode($this->getContent()));
        }
        return parent::_afterLoad();
    }
    
    public function getContent()
    {
        if (is_array($this->_getData('content'))) {
            return $this->_getData('content');
        }
        
        if (trim($this->_getData('content'))) {
            return Zend_Json::decode($this->_getData('content'));
        }
        
        return array();
    }
}