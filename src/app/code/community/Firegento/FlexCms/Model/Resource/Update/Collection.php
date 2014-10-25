<?php
/**
 * integer_net Magento Module
 *
 * @category   Firegento
 * @package    Firegento_FlexCms
 * @copyright  Copyright (c) 2014 integer_net GmbH (http://www.integer-net.de/)
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */ 
class Firegento_FlexCms_Model_Resource_Update_Collection extends Varien_Data_Collection
{
    /**
     * @param $field
     * @param $condition
     */
    public function addFieldToFilter($field, $condition){
        $i = 0;

        foreach($this->getItems() as $item){
            if(isset($condition["like"])){
                $conditionValue = $condition["like"];
            }else if(isset($condition["eq"])){
                $conditionValue = $condition["eq"];
            }else{
                return $this;
            }

            $pattern = str_replace("'", "", str_replace('%', '.*', $conditionValue));
            if(!preg_match("/^{$pattern}$/i", $item->getData($field))){
                $this->removeItemByKey($i);
            }

            $i++;
        }

        return $this;
    }
}