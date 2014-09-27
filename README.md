Bible Reading Application
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
```

In production site, you need update `./config/default.php`.
