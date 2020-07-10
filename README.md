<p align="center">
        <img src="pizza.png" width="250" alt="Pizza Delivery App Backend" />
</p>


## Pizza Delivery App Restful API

[![Twitter](https://img.shields.io/twitter/follow/alimmirzaee.svg?style=social&label=Follow)](https://twitter.com/intent/follow?screen_name=alimmirzaee)

## Installation

1. clone the project
2. run composer install
3. Once the project creation procedure completed, edit db config and run the `./yii migrate` command to create the required tables.
4. Edit `config/params.php` and set your own JWT Key and etc. 
5. run `./yii rbac/init` for initializing roles and permissions. This will also create the initial user with username: `root` and password: `123456` (You can change these in RbacController.php)
6. enjoy your coffee ☕

## API Documentation

- <a href="https://documenter.getpostman.com/view/1900475/T17M767u?version=latest">Restful API Documentation</a>

## Configuration

You can find all the boilerplate specific settings in the `config/params.php` config file.

```php
<?php

return [
    'adminEmail' => 'admin@example.com',
    'TokenEncryptionKey' => '234234rdfedcecrfcf',
    'TokenID' => 'Ssdfkm0c42c2r24crr2',
    'JwtIssuer' => 'ChangeThisToIssuer',
    'JwtAudience' => 'ChangeThisToAudience',
    'JwtExpire' => 3600,
    'DefaultSignupRole' => 'member',
];

```

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=pizza',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.


TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).
By default there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run -- --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit -- --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit -- --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
