<?php

class Firegento_FlexCms_Test_Config_Config extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * @test
     * @loadExpections
     */
    public function globalConfig()
    {
        $this->assertModuleVersion($this->expected('module')->getVersion());
        $this->assertModuleCodePool($this->expected('module')->getCodePool());

        $this->assertLayoutFileDefined('frontend', 'firegento/flexcms.xml');
        $this->assertLayoutFileExists('frontend', 'firegento/flexcms.xml');
        
        $this->assertLayoutFileDefined('adminhtml', 'firegento/flexcms.xml');
        $this->assertLayoutFileExists('adminhtml', 'firegento/flexcms.xml');
        
        $this->assertFileExists(Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS . 'de_DE' . DS . 'Firegento_FlexCms.csv');
    }

}