<?php

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class Zend_Composer_Plugin implements PluginInterface
{
    protected $installer;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->installer = new Zend_Composer_Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($this->installer);
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        $composer->getInstallationManager()->removeInstaller($this->installer);
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}
