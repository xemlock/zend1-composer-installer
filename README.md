# Composer installer for Zend Framework 1 modules

[![Build Status](https://travis-ci.org/xemlock/zend1-composer-installer.svg?branch=master)](https://travis-ci.org/xemlock/zend1-composer-installer)

This is an installer plugin for Zend Framework 1 modules.

## Usage

To use this installer set the type of your module to `zend1-module` and
add this plugin to dependencies in your package's `composer.json` file.
For example:

```json
{
    "name": "foo/bar",
    "type": "zend1-module",
    "require": {
        "xemlock/zend1-composer-installer": "*"
    }
}
```

By default the modules will be installed in `application/modules`
directory.

## Custom install paths

This plugin follows the same rules for customizing package install paths
as [Composer Installers plugin](https://github.com/composer/installers).

## License

MIT License
