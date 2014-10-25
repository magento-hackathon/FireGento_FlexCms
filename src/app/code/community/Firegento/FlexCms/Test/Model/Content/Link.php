<?php
/**
 * @category   Firegento
 * @package    Firegento_FlexCms
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */ 

class Firegento_FlexCms_Test_Model_Content_Link extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @test
     * @loadFixture ~Firegento_FlexCms/content
     */
    public function testModelAndCollection()
    {
        $content = Mage::getModel('firegento_flexcms/content_link')->load(1);
        $this->assertEquals(1, $content->getId());
        $this->assertEquals('default', $content->getLayoutHandle());

        /** @var $content Firegento_FlexCms_Model_Content */
        $content = Mage::getModel('firegento_flexcms/content_link');
        $content->addData(array(
            'content_id' => 1,
            'layout_handle' => 'checkout_cart_index',
            'area' => 'contnent',
            'store_ids' => 0,
        ))
        ->save();

        $contentCollection = Mage::getResourceModel('firegento_flexcms/content_link_collection');
        
        $this->assertEquals(2, $contentCollection->getSize());
    }
}