<?php

class JZahedieh_ThemeFallbackTest_Test_Controller_Symlink extends EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Using default package and no symlinks, should pass
     *
     * @loadFixture default_package.yaml
     * @loadFixture disable_symlinks.yaml
     */
    public function testDispatch()
    {
        $this->dispatch('');
        $this->assertRequestRoute('cms/index/index');

        $this->assertEquals('rwd', Mage::getStoreConfig('design/package/name'));
        $this->assertEquals(0, Mage::getStoreConfig('dev/template/allow_symlink'));

        $this->_assertHomepageLayoutAndResponse();
    }

    /**
     * Test with a custom package with rewritten 1column.phtml brought in using a symlink.
     *
     * Will fail due to trying to include the file from the composer vendor file:
     *
     * @see         Mage_Core_Block_Template::fetchView() line 239-244
     *
     *   $includeFilePath = realpath($this->_viewDir . DS . $fileName);
     *   if (strpos($includeFilePath, realpath($this->_viewDir)) === 0 || $this->_getAllowSymlinks()) {
     *       include $includeFilePath;
     *   } else {
     *       Mage::log('Not valid template file:'.$fileName, Zend_Log::CRIT, null, null, true);
     *   }
     *
     * @loadFixture unittest_package.yaml
     * @loadFixture disable_symlinks.yaml
     */
    public function testDispatchUnittestPackageWithoutSymlinkConfig()
    {
        $this->dispatch('');
        $this->assertRequestRoute('cms/index/index');

        $this->assertEquals('unittest', Mage::getStoreConfig('design/package/name'));
        $this->assertEquals(0, Mage::getStoreConfig('dev/template/allow_symlink'));
        $this->_assertHomepageLayoutAndResponse();
    }

    /**
     * With symlinks enabled should pass.
     *
     * @loadFixture unittest_package.yaml
     * @loadFixture enable_symlinks.yaml
     */
    public function testDispatchUnittestPackageWithSymlinkConfig()
    {
        $this->dispatch('');
        $this->assertRequestRoute('cms/index/index');

        $this->assertEquals('unittest', Mage::getStoreConfig('design/package/name'));
        $this->assertEquals(1, Mage::getStoreConfig('dev/template/allow_symlink'));

        $this->_assertHomepageLayoutAndResponse();
    }

    /**
     * Assertions by Ivan that check everything the homepage is working correctly
     *
     * @see https://github.com/EcomDev/EcomDev_PHPUnit/issues/60
     */
    protected function _assertHomepageLayoutAndResponse()
    {
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