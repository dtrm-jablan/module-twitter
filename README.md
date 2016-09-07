## Twitter Integration Module
This module can be used stand-alone or as a [Composer package](https://getcomposer.org/doc/00-intro.md) in your own applications. 

## Usage as a Module
Clone this repository into your `/path/to/apps` directory.

### Enabling the Module
Once installed, the module must be enabled for use within the platform. If you're using the Composer package in your own project, this is not necessary. 

To enable for a *tenant*, add a module mapping (`twitter`) to the `/path/to/appli/<tenant>/config.inc` file:

    $appconf['mod_mapping']['twitter'] = ['path' => 'apps/twitter'];

To enable for all tenants, add the module mapping to `/path/to/appli/config.inc` file instead:

    $appconf['mod_mapping']['twitter'] = ['path' => 'apps/twitter'];

## Usage as a Package
Because this is a private repository, you'll need to add a repository section to your `composer.json`: 

```
"repositories":      [
    {
        "type": "vcs",
        "url":  "https://github.com/Determine-Corp/module-twitter.git"
    }
],
```

Then add the following requirement for the latest stable release: 

```
"require":           {
    ...
    "determine/module-twitter": "1.0.*"
},
```

If you'd like to play with the current unreleased version, use the development version:

```
"require":           {
    ...
    "determine/module-twitter": "dev-develop as dev-master"
},
```

or

```
"require":           {
    ...
    "determine/module-twitter": "1.0.*@dev"
},
```

Update Composer (`composer update`) and the `vendor/autoload.php` file will be created. The module classes will now be available through the autoloader.
