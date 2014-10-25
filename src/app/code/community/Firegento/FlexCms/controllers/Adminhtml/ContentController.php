<?php

class Firegento_FlexCms_Adminhtml_ContentController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Cms'))->_title($this->__('FlexCms Content'));

        $this->loadLayout();
        $this->_setActiveMenu('cms/flexcms_content');
        $this->_addBreadcrumb(Mage::helper('cms')->__('CMS'), Mage::helper('firegento_flexcms')->__('FlexCms Content'));

        $this->_addContent($this->getLayout()->createBlock('firegento_flexcms/adminhtml_content'));

        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Action for /admin/job/edit/
     * Edit job details
     *
     * @return void
     */
    public function editAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('firegento_flexcms/adminhtml_content_edit'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        $jobId = $this->getRequest()->getParam('id', false);

        if ($data = $this->getRequest()->getPost()) {

            try {
                //  $job->save();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('firegento_flexcms')
                    ->__('Content was saved successfully'));
                $this->getResponse()->setRedirect($this->getUrl('*/*/'));
                return;
            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')
                    ->addError($e->getMessage());
            }
        }
        $this->_redirectReferer();

    }

    public function deleteAction()
    {
        $jobId = $this->getRequest()->getParam('id', false);

        try {
            //Mage::getModel('n98_hawiinterface/job')->setId($jobId)->delete();
            Mage::getSingleton('adminhtml/session')
                ->addSuccess(Mage::helper('firegento_flexcms')
                ->__('Job successfully deleted'));
            $this->getResponse()->setRedirect($this->getUrl('*/*/'));

            return;
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirectReferer();
    }
}