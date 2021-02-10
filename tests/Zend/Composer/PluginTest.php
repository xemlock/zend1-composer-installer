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

        $this->_io = new NullIO();

        if (method_exists('Composer\Installer\InstallationManager', '__construct')) {
            $loop = $this->getMockBuilder('Composer\Util\Loop')
                ->disableOriginalConstructor()
                ->getMock();
            $installationManager = new InstallationManager($loop, $this->_io);
        } else {
            $installationManager = new InstallationManager();
        }

        $this->_composer->setInstallationManager($installationManager);
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
