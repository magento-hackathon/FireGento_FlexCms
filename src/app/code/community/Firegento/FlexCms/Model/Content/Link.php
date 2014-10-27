<?php

class Firegento_FlexCms_Model_Content_Link extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('firegento_flexcms/content_link');
    }

    /**
     * @return Firegento_FlexCms_Model_Content
     */
    public function getContentModel()
    {
        return Mage::getModel('firegento_flexcms/content')->load($this->getContentId());
    }

    /**
     * Get decoded content data
     *
     * @return array
     */
    public function getContent()
    {
        if (is_array($this->_getData('content'))) {
            return $this->_getData('content');
        }

        if (trim($this->_getData('content'))) {
            try{
                return Zend_Json::decode($this->_getData('content'));
            }catch (Exception $e){

            }
        }

        return array();
    }

    /**
     * Update fields of link or content element depending on form entries
     *
     * @param $fields array($fieldName => $fieldValue)
     */
    public function updateFields($fields)
    {
        if (array_key_exists('delete', $fields)) {
            $this->_delete();
            return;
        }

        $contentElement = $this->getContentModel();

        $content = array();
        foreach ($fields as $fieldName => $fieldValue) {

            if ($fieldName == 'title') {
                $contentElement->setTitle($fieldValue);
            } elseif ($fieldName == 'sort_order') {
                $this->setSortOrder($fieldValue);
            } else {
                $content[$fieldName] = $fieldValue;
            }
        }

        $contentElement->setContent($content)->save();
        $this->save();
    }


    /**
     * delete link or entire content element if no other links are referenced to it
     */
    protected function _delete()
    {
        $parentElementUsageCollection = $this->getCollection()
            ->addFieldToFilter('content_id', array('eq' => $this->getContentId()));
        if (count($parentElementUsageCollection) == 1) {
            $this->getContentModel()->delete(); // link gets deleted implicitly via foreign key
        } else {
            $this->delete();
        }
    }
}