<?php

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Package\Package;
use Composer\Package\RootPackage;

class Zend_Composer_InstallerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Composer
     */
    protected $_composer;

    /**
     * @var IOInterface
     */
    protected $_io;

    public function setUp()
    {
        $this->_composer = new Composer();
        $this->_composer->setConfig(new Config());

        $this->_io = new NullIO();
    }

    public function testSupports()
    {
        $installer = new Zend_Composer_Installer($this->_io, $this->_composer);

        $this->assertTrue($installer->supports('zend1-module'));
        $this->assertFalse($installer->supports('library'));
    }

    public function testInstallPath()
    {
        $installer = new Zend_Composer_Installer($this->_io, $this->_composer);
        $package = new Package('alpha-trion/orion-pax', '1.0.0', '1.0.0');

        $this->assertEquals('application/modules/orion-pax', $installer->getInstallPath($package));
    }

    public function testInstallPathFromExtraInstallerName()
    {
        $installer = new Zend_Composer_Installer($this->_io, $this->_composer);
        $package = new Package('alpha-trion/orion-pax', '1.0.0', '1.0.0');
        $package->setExtra(array('installer-name' => 'optimus-prime'));

        $this->assertEquals('application/modules/optimus-prime', $installer->getInstallPath($package));
    }

    public function testInstallPathFromExtraInstallerPaths()
    {
        $installer = new Zend_Composer_Installer($this->_io, $this->_composer);

        $consumerPackage = new RootPackage('root-package', '1.0.0', '1.0.0');
        $consumerPackage->setExtra(array('installer-paths' => array(
            'decepticons/{$name}' => array('unicron/galvatron'),
        )));
        $this->_composer->setPackage($consumerPackage);

        $package = new Package('unicron/galvatron', '1.0.0', '1.0.0');
        $this->assertEquals('decepticons/galvatron', $installer->getInstallPath($package));
    }

    public function testInstallPathFromExtraInstallerPathsByVendor()
    {
        $installer = new Zend_Composer_Installer($this->_io, $this->_composer);

        $consumerPackage = new RootPackage('root-package', '1.0.0', '1.0.0');
        $consumerPackage->setExtra(array('installer-paths' => array(
            'decepticons/unicron/{$name}' => array('vendor:unicron'),
        )));
        $this->_composer->setPackage($consumerPackage);

        $package = new Package('unicron/cyclonus', '1.0.0', '1.0.0');
        $this->assertEquals('decepticons/unicron/cyclonus', $installer->getInstallPath($package));
    }

    public function testInstallPathFromExtraInstallerPathsByType()
    {
        $installer = new Zend_Composer_Installer($this->_io, $this->_composer);

        $consumerPackage = new RootPackage('root-package', '1.0.0', '1.0.0');
        $consumerPackage->setExtra(array('installer-paths' => array(
            'decepticons/{$name}' => array('type:sweeps'),
        )));
        $this->_composer->setPackage($consumerPackage);

        $package = new Package('unicron/scourge', '1.0.0', '1.0.0');
        $package->setType('sweeps');
        $this->assertEquals('decepticons/scourge', $installer->getInstallPath($package));
    }
}
