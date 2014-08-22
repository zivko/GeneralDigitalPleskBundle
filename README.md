GeneralDigitalPleskBundle
=========================

Symfony2 bundle for [Parallels Plesk v 12](http://www.parallels.com/plesk/) API.

GeneralDigitalPleskBundle is licensed under the MIT License - see the `Resources/meta/LICENSE` file for details.

**Plesk API Method Supported**



## Setup

### Step 1: Download GeneralDigitalPleskBundle using composer

Add GeneralDigitalPleskBundle in your composer.json:

```js
{
    "require": {
        "gd/plesk-bundle": "dev-master"
    }
}
```
Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update gd/plesk-bundle
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new GeneralDigital\PleskBundle\GeneralDigitalPleskBundle(),
    );
}
```
### Step 3: Add configuration

``` yml
# app/config/config.yml
general_digital_plesk:
    host: #plesk host
    user: #plesk login user
    password: #plesk password
```

## Usage

**Using service**

``` php
<?php
        $plesk = $this->get('general_digital_plesk.api');
```

**Plesk [API] (http://download1.parallels.com/Plesk/PP12/12.0/Doc/en-US/online/plesk-api-rpc/index.htm) add new FTP user in a controller**

``` php
<?php
         $plesk = $this->get('general_digital_plesk.api');
         $api->addFTPUser($ftpUsername, $ftpPassword);
```


