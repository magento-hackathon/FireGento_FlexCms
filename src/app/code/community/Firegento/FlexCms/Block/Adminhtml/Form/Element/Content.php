<?php
/**
 * integer_net Magento Module
 *
 * @category   Firegento
 * @package    Firegento_FlexCms
 * @copyright  Copyright (c) 2014 integer_net GmbH (http://www.integer-net.de/)
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */ 
class Firegento_FlexCms_Block_Adminhtml_Form_Element_Content extends Varien_Data_Form_Element_Abstract
{
    /**
     * Element type classes
     *
     * @var array
     */
    protected $_types = array();

    public function getHtml()
    {
        /** @var Firegento_FlexCms_Model_Resource_Content_Link_Collection $linkCollection */
        $linkCollection = Mage::getModel('firegento_flexcms/content_link')->getCollection();
        $linkCollection->addFieldToFilter('area', $this->getArea());
        $linkCollection->addFieldToFilter('layout_handle', $this->getLayoutHandle());
        $linkCollection->setOrder('sort_order', 'asc');

        $linkCollection->getSelect()->join(
            array('content' => Mage::getSingleton('core/resource')->getTableName('firegento_flexcms/content')),
            'content.flexcms_content_id=main_table.content_id',
            array('content.*')
        );
        
        

        $html = '';
        foreach ($linkCollection as $link) {
            
            $contentType = $link->getContentType();
            $contentTypeConfig = Mage::getStoreConfig('firegento_flexcms/types/' . $contentType);
            
            foreach($contentTypeConfig['fields'] as $fieldCode => $fieldConfig) {
                $html .= $this->_getField('flexcms_content_link_' . $link->getId() . '_field_' . $fieldCode, $fieldConfig['frontend_type'], array(
                    'label' => Mage::helper('firegento_flexcms')->__($fieldConfig['label']),
                    'name' => 'flexcms_element[' . $link->getId() . '][' . $fieldCode . ']',
                    'value' => $link->getContent(),
                ))->getHtml();
            }
            
  /*          
            $fieldset->getFiedl('content', 'text', array(
                'layout_handle' => '',
                'area' => '',
            ));
*/
        }
        
        return $html;
    }

    /**
     * @param   string $elementId
     * @param   string $type
     * @param   array  $config
     * @return Varien_Data_Form_Element_Abstract
     */
    protected function _getField($elementId, $type, $config)
    {
        if (isset($this->_types[$type])) {
            $className = $this->_types[$type];
        }
        else {
            $className = 'Varien_Data_Form_Element_'.ucfirst(strtolower($type));
        }

        $element = new $className($config);
        $element->setId($elementId);
        $element->setForm($this->_getForm());
        return $element;
    }
    
    protected function _getForm()
    {
        return new Varien_Object(array(
            'html_id_prefix' => 'flexform_area_' . $this->getArea() . '_',
        ));
    }
}