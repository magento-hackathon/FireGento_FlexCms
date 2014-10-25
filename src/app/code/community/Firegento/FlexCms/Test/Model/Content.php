<?php
/**
 * @category   Firegento
 * @package    Firegento_FlexCms
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */ 

class Firegento_FlexCms_Test_Model_Content extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @test
     * @loadFixture ~Firegento_FlexCms/content
     */
    public function testModelAndCollection()
    {
        $content = Mage::getModel('firegento_flexcms/content')->load(1);
        $this->assertEquals(1, $content->getId());
        $this->assertEquals('Entry 1', $content->getTitle());

        /** @var $content Firegento_FlexCms_Model_Content */
        $content = Mage::getModel('firegento_flexcms/content');
        $content->addData(array(
            'title' => 'Test Content',
            'content' => 'Test Content',
            'content_type' => 'html',
        ))
        ->save();

        $contentCollection = Mage::getResourceModel('firegento_flexcms/content_collection');
        
        $this->assertEquals(2, $contentCollection->getSize());
    }
}