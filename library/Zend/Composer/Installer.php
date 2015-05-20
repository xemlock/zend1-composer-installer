<?php

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

class Zend_Composer_Installer extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return $packageType === 'zend1-module';
    }

    /**
     * Return the install path based on package type.
     *
     * @param  PackageInterface $package
     * @return string
     */
    public function getInstallPath(PackageInterface $package)
    {
        $type = $package->getType();

        // Both prettyName and name consists of vendor and name, module name
        // requires vendor prefix to be stripped
        $prettyName = $package->getPrettyName();

        if (strpos($prettyName, '/') !== false) {
            list($vendor, $name) = explode('/', $prettyName);
        } else {
            $vendor = '';
            $name = $prettyName;
        }

        // Naming conventions for ZF1 modules require that the camel cased
        // module name must match the class prefix - so it's up to module
        // author or publisher to ensure that module name is correct.
        // If an installer-name extra is specified in module composer.json
        // it will be used as a name part of the installation path.
        $extra = $package->getExtra();
        if (!empty($extra['installer-name'])) {
            $name = $extra['installer-name'];
        }

        $availableVars = compact('name', 'vendor', 'type');

        if ($this->composer->getPackage()) {
            $extra = $this->composer->getPackage()->getExtra();
            if (!empty($extra['installer-paths'])) {
                $customPath = $this->_mapCustomInstallPaths($extra['installer-paths'], $prettyName, $type, $vendor);
                if ($customPath !== false) {
                    return $this->_templatePath($customPath, $availableVars);
                }
            }
        }

        return $this->_templatePath('application/modules/{$name}', $availableVars);
    }

    /**
     * Replace vars in a path
     *
     * @param  string $path
     * @param  array  $vars
     * @return string
     */
    protected function _templatePath($path, array $vars = array())
    {
        if (strpos($path, '{') !== false) {
            preg_match_all('#\{\$([_a-z0-9]*)\}#i', $path, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $var) {
                    $path = str_replace('{$' . $var . '}', isset($vars[$var]) ? $vars[$var] : null, $path);
                }
            }
        }
        return $path;
    }

    /**
     * Search through a passed paths array for a custom install path.
     *
     * @param  array  $paths
     * @param  string $name
     * @param  string $type
     * @param  string $vendor
     * @return string
     */
    protected function _mapCustomInstallPaths($paths, $name, $type, $vendor = null)
    {
        foreach ((array) $paths as $path => $names) {
            $names = (array) $names;
            if (in_array($name, $names) || in_array('type:' . $type, $names) || in_array('vendor:' . $vendor, $names)) {
                return $path;
            }
        }
        return false;
    }
}
