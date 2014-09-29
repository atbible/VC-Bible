Bible Reading Application [![Build Status](https://travis-ci.org/thanhkinh/vcbible.svg?branch=v0.1)](https://travis-ci.org/thanhkinh/vcbible)  [![License](https://poser.pugx.org/andytruong/vcbible/license.png)](https://packagist.org/packages/andytruong/vcbible)
====

### Install

```bash
# install composer
curl -sS https://getcomposer.org/installer | php mv composer.phar

# create code base using composer
composer.phar create-project andytruong/vcbible:0.1.*@dev vcbible

# change directory
cd vcbible

# create database structure
php public/index.php orm:schema-tool:create

# create data importing commands
php public/index.php bible:import --restart=1

# start importing
for i in {1..1000}; do php public/index.php bible:import; sleep 5; done;

# Run server, then web application can be found at http://localhost:8888/
php public/index.php server
```

In production site, you need update `./config/default.php`.

### Features

1. Multiple translation
2. Inline edit interface
3. Responsive UI

### Screenshot

![](https://raw.githubusercontent.com/v3kwip/vcbible/v0.1/resources/responsive.png)
