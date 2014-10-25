<?php
/**
 * This file is part of a FireGento e.V. module.
 *
 * This FireGento e.V. module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  FireGento
 * @package   FireGento_FlexCms
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2014 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */

/**
 * FlexCms Observer
 *
 * @category FireGento
 * @package  FireGento_FlexCms
 * @author   FireGento Team <team@firegento.com>
 */
class Firegento_FlexCms_Model_Observer
{
    /**
     * add CMSPAGE_{ID} handle to layout on cms page render
     *
     * @param Varien_Event_Observer $observer
     * @return Varien_Event_Observer
     */
    public function cmsPageRender(Varien_Event_Observer $observer)
    {
        $observer->getControllerAction()->getLayout()->getUpdate()
            ->addHandle('CMSPAGE_'.$observer->getPage()->getId());
    }

    /**
     * Add tab for Category content
     *
     * @param Varien_Event_Observer $observer
     */
    public function addContentCategoryTab(Varien_Event_Observer $observer)
    {

        $tabs = $observer->getTabs();

        $tabs->addTab('content', array(
                'label'     => Mage::helper('firegento_flexcms')->__('Content'),
                'content'   => $tabs->getLayout()->createBlock(
                    'firegento_flexcms/tab_content',
                    'flexcms.content.form'
                )->toHtml(),
            ));
    }

    /**
     * Save category flex content
     *
     * @param Varien_Event_Observer $observer
     *
     */
    public function saveCategoryFlexContent(Varien_Event_Observer $observer)
    {
        $data = $observer->getRequest()->getParams();

        $categoryId = $data['id'];
        $layoutHandle = 'Category_'.$categoryId;
        foreach ($data['content_element'] as $area => $value) {
            $flexContentLink = Mage::getModel('firegento_flexcms/content_link');
            $flexContentLink->setArea($area);
            $flexContentLink->setLayoutHandle($layoutHandle);
            $flexContentLink->setContentId($value);
            $flexContentLink->save();
        }
    }
}