<?php

class JZahedieh_ThemeFallbackTest_Test_Controller_Block extends EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Test by Ivan that asserts everything is working correctly using default package.
     *
     * @see         https://github.com/EcomDev/EcomDev_PHPUnit/issues/60
     *
     * @loadFixture default_package.yaml
     */
    public function testDispatch()
    {

        $this->dispatch('');
        $this->assertRequestRoute('cms/index/index');

        $this->assertEquals('rwd', Mage::getStoreConfig('design/package/name'));

        $this->assertLayoutBlockCreated('left');
        $this->assertLayoutBlockCreated('right');
        $this->assertLayoutBlockRendered('content');
        $this->assertLayoutBlockTypeOf('left', 'core/text_list');
        $this->assertLayoutBlockNotTypeOf('left', 'core/links');

        $this->assertResponseBodyContains('Magento');
        $this->assertResponseBodyContains('Home Page');
        $this->assertResponseBodyContains('Compare Products');
        $this->assertResponseBodyNotContains('Non existing text');
    }

    /**
     * @loadFixture unittest_package.yaml
     */
    public function testDispatchUnittestPackage()
    {

        $this->dispatch('');
        $this->assertRequestRoute('cms/index/index');

        $this->assertEquals('unittest', Mage::getStoreConfig('design/package/name'));

        $this->assertLayoutBlockCreated('left');
        $this->assertLayoutBlockCreated('right');
        $this->assertLayoutBlockRendered('content');
        $this->assertLayoutBlockTypeOf('left', 'core/text_list');
        $this->assertLayoutBlockNotTypeOf('left', 'core/links');

        $this->assertResponseBodyContains('Magento');
        $this->assertResponseBodyContains('Home Page');
        $this->assertResponseBodyContains('Compare Products');
        $this->assertResponseBodyNotContains('Non existing text');
    }


}