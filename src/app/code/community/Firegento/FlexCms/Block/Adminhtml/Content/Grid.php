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
        
        $collection = new Varien_Data_Collection();
        
        foreach($linkCollection as $link) {
            
            $linksByHandle[$link->getLayoutHandle()][] = $link;
        }
        
        foreach($linksByHandle as $handle => $links) {
            $summary = '';
            $item = new Varien_Object(array(
                'layout_handle' => $handle,
            ));
            
            $content = array();
            foreach($links as $link) {
                $content[$link->getArea()][] = sprintf('%s (%s)', $link->getTitle(), $link->getContentType());
            }

            foreach ($content as $area => $contentElements) {
                if ($summary) {
                    $summary .= '<br />';
                }
                $summary .= '<strong>' . $area . ':</strong><br />';
                $summary .= implode('<br />', $contentElements);
            }

            $item->setSummary($summary);


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

        $this->addColumn('summary', array(
            'header'    => Mage::helper('firegento_flexcms')->__('Content'),
            'align'     => 'left',
            'index'     => 'summary',
            'renderer'  => 'firegento_flexcms/adminhtml_grid_column_renderer_html',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
