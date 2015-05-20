<?php

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Installer\InstallationManager;

class Zend_Composer_PluginTest extends PHPUnit_Framework_TestCase
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
        $this->_composer->setInstallationManager(new InstallationManager());

        $this->_io = new NullIO();
    }

    public function testPluginActivate()
    {
        $plugin = new Zend_Composer_Plugin();
        $plugin->activate($this->_composer, $this->_io);

        $this->assertInstanceOf(
            'Zend_Composer_Installer',
            $this->_composer->getInstallationManager()->getInstaller('zend1-module')
        );
    }
}
