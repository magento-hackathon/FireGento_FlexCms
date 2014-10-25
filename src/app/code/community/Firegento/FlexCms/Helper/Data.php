<?php

class Firegento_FlexCms_Helper_Data extends Mage_Core_Helper_Abstract
{
    const Firegento_Flexcms_Areas_Xml = "default/firegento_flexcms/areas";

    private $_contentElementArray = array();

    /**
     * Get Content sections
     *
     * @return array
     */
    public function getFlexContentSectionsForm()
    {
        $areas = Mage::getConfig()->getNode(self::Firegento_Flexcms_Areas_Xml)->asArray();
        return $areas;

    }

    /**
     * Get existing flex cms contents
     *
     * @return array
     */
    public function getFlexContents()
    {
        if (!$this->_contentElementArray) {
            $contentCollection    = Mage::getResourceModel('firegento_flexcms/content_collection')->load();

            $this->_contentElementArray[] = array(
                'value' => 0,
                'label' => 'No content'
            );

            foreach ($contentCollection as $content) {
                $this->_contentElementArray[] = array(
                    'value' => $content->getFlexcmsContentId(),
                    'label' => $content->getTitle()
                );
            }
        }

        return $this->_contentElementArray;
    }

}