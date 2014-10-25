<?php
/**
 * netz98 magento module
 *
 * LICENSE
 *
 * This source file is subject of netz98.
 * You may be not allowed to change the sources
 * without authorization of netz98 new media GmbH.
 *
 * @copyright Copyright (c) 1999-2012 netz98 new media GmbH (http://www.netz98.de)
 * @category N98
 * @package N98_HaWiInterface
 */

/**
 * @category N98
 * @package N98_HaWiInterface
 */
class N98_HaWiInterface_Block_Adminhtml_Job_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'n98_hawiinterface';
        $this->_mode = 'edit';
        $this->_controller = 'adminhtml_job';


        if( $this->getRequest()->getParam($this->_objectId) ) {
            $jobData = Mage::getModel('n98_hawiinterface/job')
                ->load($this->getRequest()->getParam($this->_objectId));
            Mage::register('job', $jobData);
        } else {
            $jobData = Mage::getModel('n98_hawiinterface/job');
            Mage::register('job', $jobData);
        }
    }

    public function getHeaderText()
    {
        if (Mage::registry('job')->getId()) {
            return Mage::helper('n98_hawiinterface')->__('Edit Job \'%s\'', Mage::registry('job')->getName());
        }
        else {
            return Mage::helper('n98_hawiinterface')->__('New Job');
        }
    }
}
