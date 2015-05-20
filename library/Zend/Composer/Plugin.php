<?php

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class Zend_Composer_Plugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new Zend_Composer_Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}
