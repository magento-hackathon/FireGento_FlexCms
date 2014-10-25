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
class N98_HaWiInterface_Block_Adminhtml_Job_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Defines Fields which should be displayed
     *
     * @return N98_HaWiInterface_Block_Adminhtml_Job_Edit_Form
     */
    protected function _prepareForm()
    {
        $job = Mage::registry('job');

        $data = $job->getData();
        $data['start_time'] = str_replace(':', ',', $data['start_time']);
        $data['end_time'] = str_replace(':', ',', $data['end_time']);

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post',
        ));

        $fieldset = $form->addFieldset('edit_job_general',
            array(
                'legend' => Mage::helper('n98_hawiinterface')->__('Job Data'),
                'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            )
        );

        $fieldset->addField('name', 'text',
            array(
                'name'  => 'name',
                'label' => Mage::helper('n98_hawiinterface')->__('Name'),
                'id'    => 'name',
                'title' => Mage::helper('n98_hawiinterface')->__('Name'),
                'required' => true,
            )
        );

        $fieldset->addField('description', 'textarea',
            array(
                'name'  => 'description',
                'label' => Mage::helper('n98_hawiinterface')->__('Description'),
                'id'    => 'description',
                'title' => Mage::helper('n98_hawiinterface')->__('Description'),
                'required' => false,
            )
        );

        $fieldset->addField('enabled', 'select',
            array(
                'name'  => 'enabled',
                'label' => Mage::helper('n98_hawiinterface')->__('Enabled'),
                'id'    => 'enabled',
                'title' => Mage::helper('n98_hawiinterface')->__('Enabled'),
                'required' => false,
                'values' => Mage::getSingleton('eav/entity_attribute_source_boolean')->getOptionArray(),
            )
        );

        $fieldset->addField('status', 'select',
            array(
                'name'  => 'status',
                'label' => Mage::helper('n98_hawiinterface')->__('Status'),
                'id'    => 'status',
                'title' => Mage::helper('n98_hawiinterface')->__('Status'),
                'required' => true,
                'values' => Mage::getSingleton('n98_hawiinterface/source_status_job')->getOptionArray(),
            )
        );

        $fieldset->addField('timeout', 'text',
            array(
                'name'  => 'timeout',
                'label' => Mage::helper('n98_hawiinterface')->__('Timeout (seconds)'),
                'id'    => 'timeout',
                'title' => Mage::helper('n98_hawiinterface')->__('Timeout (seconds)'),
                'required' => true,
            )
        );

        $fieldset->addField('sort_order', 'text',
            array(
                'name'  => 'sort_order',
                'label' => Mage::helper('n98_hawiinterface')->__('Sort Order'),
                'id'    => 'sort_order',
                'title' => Mage::helper('n98_hawiinterface')->__('Sort Order'),
                'required' => true,
            )
        );

        $fieldset->addField('start_time', 'time',
            array(
                'name'  => 'start_time',
                'label' => Mage::helper('n98_hawiinterface')->__('Start Time'),
                'id'    => 'start_time',
                'title' => Mage::helper('n98_hawiinterface')->__('Start Time'),
                'required' => false,
            )
        );

        $fieldset->addField('end_time', 'time',
            array(
                'name'  => 'end_time',
                'label' => Mage::helper('n98_hawiinterface')->__('End Time'),
                'id'    => 'end_time',
                'title' => Mage::helper('n98_hawiinterface')->__('End Time'),
                'required' => false,
            )
        );

        $fieldset->addField('interval', 'text',
            array(
                'name'  => 'interval',
                'label' => Mage::helper('n98_hawiinterface')->__('Interval between two runs (Minutes)'),
                'id'    => 'interval',
                'title' => Mage::helper('n98_hawiinterface')->__('Interval between two runs (Minutes)'),
                'required' => false,
            )
        );

        $fieldset->addField('last_start', 'date',
            array(
                'name'  => 'last_start',
                'label' => Mage::helper('n98_hawiinterface')->__('Last Run (Start)'),
                'id'    => 'last_start',
                'title' => Mage::helper('n98_hawiinterface')->__('Last Run (Start)'),
                'required' => false,
                'readonly' => true,
                'format' => 'dd.MM.Y H:m:s',
                'style' => 'width: 274px; border: 0;',
            )
        );

        $fieldset->addField('last_end', 'date',
            array(
                'name'  => 'last_end',
                'label' => Mage::helper('n98_hawiinterface')->__('Last Run (End)'),
                'id'    => 'last_end',
                'title' => Mage::helper('n98_hawiinterface')->__('Last Run (End)'),
                'required' => false,
                'readonly' => true,
                'format' => 'dd.MM.Y H:m:s',
                'style' => 'width: 274px; border: 0;',
            )
        );

        $form->setUseContainer(true);
        if (!$job->getId()) {
            $job->setIsActive(true);
        }
        $form->setValues($data);
        if (!$job->getTimeout()) {
            $form->addValues(array('timeout' => 900));
        }
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
