<?php
class Firegento_FlexCms_Block_Adminhtml_Content_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('contentGrid');
        $this->setDefaultSort('sort_order');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var Firegento_FlexCms_Model_Resource_Content_Link_Collection $linkCollection */
        $linkCollection = Mage::getModel('firegento_flexcms/content_link')->getCollection();
        $linkCollection->setOrder('area', 'asc');
        $linkCollection->setOrder('sort_order', 'asc');
        
        
        $linkCollection->getSelect()->join(
            array('content' => Mage::getSingleton('core/resource')->getTableName('firegento_flexcms/content')),
            'content.flexcms_content_id=main_table.content_id',
            array('content.*')
        );
        
        $linksByHandle = array();

        /** @var $collection Firegento_FlexCms_Model_Resource_Update_Collection */
        $collection = Mage::getResourceModel('firegento_flexcms/update_collection');
        
        foreach($linkCollection as $link) {
            
            $linksByHandle[$link->getLayoutHandle()][] = $link;
        }
        
        foreach($linksByHandle as $handle => $links) {
            $item = new Varien_Object(array(
                'layout_handle' => $handle,
                'content_type' => $this->_getLayoutHandleType($handle),
                'summary' =>  $this->_getSummary($links),
            ));

            $collection->addItem($item);
        }
        
        $this->setCollection($collection);
        parent::_prepareCollection();

        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('layout_handle', array(
            'header'    => Mage::helper('firegento_flexcms')->__('Layout Handle'),
            'align'     => 'left',
            'index'     => 'layout_handle',
        ));
        
        $this->addColumn('content_type', array(
            'header'    => Mage::helper('firegento_flexcms')->__('Content Type'),
            'align'     => 'left',
            'index'     => 'content_type',
            'type'      => 'options',
            'options'   => Mage::getSingleton('firegento_flexcms/source_handleType')->toArray(),
        ));

        $this->addColumn('summary', array(
            'header'    => Mage::helper('firegento_flexcms')->__('Content'),
            'align'     => 'left',
            'index'     => 'summary',
            'sortable'  => false,
            'renderer'  => 'firegento_flexcms/adminhtml_grid_column_renderer_html',
        ));

        return parent::_prepareColumns();
    }

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
