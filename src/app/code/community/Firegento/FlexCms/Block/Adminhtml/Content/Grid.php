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
 * FlexCms Content Renderer
 *
 * @category FireGento
 * @package  FireGento_FlexCms
 * @author   FireGento Team <team@firegento.com>
 */
class Firegento_FlexCms_Block_Adminhtml_Content_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /** @var Mage_Cms_Model_Resource_Page_Collection _cmsPageCollection */
    protected $_usedCmsPagesCollection;

    /** @var Mage_Catalog_Model_Resource_Product_Collection _cmsPageCollection */
    protected $_usedProductsCollection;

    /** @var Mage_Catalog_Model_Resource_Category_Collection _cmsPageCollection */
    protected $_usedCategoriesCollection;

    /**
     * set grid defaults
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('contentGrid');
        $this->setDefaultSort('layout_handle');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareCollection()
    {
        /** @var Firegento_FlexCms_Model_Resource_Content_Link_Collection $linkCollection */
        $linkCollection = Mage::getModel('firegento_flexcms/content_link')->getCollection()
            ->joinContentData()
            ->setOrder('layout_handle', $this->getParam($this->getVarNameDir(), $this->_defaultDir));

        /** @var $collection Firegento_FlexCms_Model_Resource_Update_Collection */
        $updateCollection = Mage::getResourceModel('firegento_flexcms/update_collection');

        // load collections with referenced items (e.g. products, cms_pages, categories)
        $this->_preloadHandleReferenceCollections($linkCollection);

        // collect all handles with coresponding links
        $linksByHandle = $this->getLinksByHandle($linkCollection);

        foreach ($linksByHandle as $handle => $links) {
            $updateCollection->addItem(new Varien_Object(
                array(
                    'layout_handle' => $handle,
                    'handle_description' => $this->_getLayoutHandleDescription($handle),
                    'content_type' => $this->_getLayoutHandleType($handle),
                    'summary' => $this->_getSummary($links),
                )
            ));
        }

        $this->setCollection($updateCollection);
        parent::_prepareCollection();

        return $this;
    }

    /**
     * @param $linkCollection
     * @return array
     */
    protected function _getLinksByHandles($linkCollection)
    {
        $linksByHandle = array();

        foreach ($linkCollection as $link) {
            if (!isset($linksByHandle[$link->getLayoutHandle()])) {
                $linksByHandle[$link->getLayoutHandle()] = array();
            }
            $linksByHandle[$link->getLayoutHandle()][] = $link;
        }

        return $linksByHandle;
    }

    /**
     * @param array $pageIds
     */
    protected function _preloadHandleReferenceCollections($linkCollection)
    {

        $referencedHandleIds = array(
            "CMSPAGE" => array(),
            "CATEGORY" => array(),
            "PRODUCT" => array()
        );

        foreach ($linkCollection as $link) {
            if (strstr($link->getLayoutHandle(), "_")) {
                $handleAsArray = explode("_", $link->getLayoutHandle());
                $referenceId = $handleAsArray[1];
                $referencedHandleIds[$handleAsArray[0]][] = $referenceId;
            }
        }

        /** @var Mage_Cms_Model_Resource_Page_Collection _cmsPageCollection */
        $this->_usedCmsPagesCollection = Mage::getModel('cms/page')->getCollection()
            ->addFieldToFilter('page_id', array('in' => $referencedHandleIds["CMSPAGE"]));

        /** @var Mage_Catalog_Model_Resource_Product_Collection _cmsPageCollection */
        $this->_usedProductsCollection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('entity_id', array('in' => $referencedHandleIds["PRODUCT"]))
            ->addAttributeToSelect('name');

        /** @var Mage_Catalog_Model_Resource_Category_Collection _cmsPageCollection */
        $this->_usedCategoriesCollection = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToFilter('entity_id', array('in' => $referencedHandleIds["CATEGORY"]))
            ->addAttributeToSelect('name');
    }

    /**
     * @param string $handle
     * @return string
     */
    protected function _getLayoutHandleDescription($handle)
    {
        $handleType = $this->_getLayoutHandleType($handle);
        if ($handleType == Firegento_FlexCms_Model_Source_HandleType::CONTENT_TYPE_OTHER) {
            return '';
        }

        $handleKey = array_pop(explode("_", $handle));

        // page title for cms_pages
        if ($this->_getLayoutHandleType($handle) == Firegento_FlexCms_Model_Source_HandleType::CONTENT_TYPE_CMS_PAGE) {
            return $this->_usedCmsPagesCollection->getItemById($handleKey)->getTitle();
        }

        // product name for products
        if ($this->_getLayoutHandleType($handle) == Firegento_FlexCms_Model_Source_HandleType::CONTENT_TYPE_PRODUCT) {
            return $this->_usedProductsCollection->getItemById($handleKey)->getName();
        }

        // category name for products
        if ($this->_getLayoutHandleType($handle) == Firegento_FlexCms_Model_Source_HandleType::CONTENT_TYPE_CATEGORY) {
            return $this->_usedCategoriesCollection->getItemById($handleKey)->getName();
        }
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {

        $this->addColumn('handle_description', array(
            'header' => Mage::helper('firegento_flexcms')->__('Description'),
            'align' => 'left',
            'index' => 'handle_description',
        ));

        $this->addColumn('layout_handle', array(
            'header' => Mage::helper('firegento_flexcms')->__('Identifier'),
            'align' => 'left',
            'index' => 'layout_handle',
        ));

        $this->addColumn('content_type', array(
            'header' => Mage::helper('firegento_flexcms')->__('Content Type'),
            'align' => 'left',
            'index' => 'content_type',
            'type' => 'options',
            'options' => Mage::getSingleton('firegento_flexcms/source_handleType')->toArray(),
        ));

        $this->addColumn('summary', array(
            'header' => Mage::helper('firegento_flexcms')->__('Content'),
            'align' => 'left',
            'index' => 'summary',
            'sortable' => false,
            'renderer' => 'firegento_flexcms/adminhtml_grid_column_renderer_html',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * @param array $links
     * @return string
     */
    protected function _getSummary($links)
    {
        $summary = '';
        $content = array();
        foreach ($links as $link) {
            $content[$link->getArea()][] = sprintf('%s (%s)', $link->getTitle(), $link->getContentType());
        }

        foreach ($content as $area => $contentElements) {
            if ($summary) {
                $summary .= '<br />';
            }
            $summary .= sprintf('<strong>%s:</strong><br />', $area);
            $summary .= implode('<br />', $contentElements);
        }
        return $summary;
    }

    /**
     * @param $handle
     * @return mixed
     */
    protected function _getLayoutHandleType($handle)
    {
        if (strpos($handle, 'PRODUCT_') === 0) {
            return Firegento_FlexCms_Model_Source_HandleType::CONTENT_TYPE_PRODUCT;
        }
        if (strpos($handle, 'CATEGORY_') === 0) {
            return Firegento_FlexCms_Model_Source_HandleType::CONTENT_TYPE_CATEGORY;
        }
        if (strpos($handle, 'CMSPAGE_') === 0) {
            return Firegento_FlexCms_Model_Source_HandleType::CONTENT_TYPE_CMS_PAGE;
        }
        return Firegento_FlexCms_Model_Source_HandleType::CONTENT_TYPE_OTHER;
    }

}
