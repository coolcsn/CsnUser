CsnUser
=======
Zend Framework 2 Module

### What is CsnUser? ###

CsnUser is a Module for Authentication based on DoctrineORMModule

### What exactly does CsnUser do? ###

CsnUser has been created with educational purposes to demonstrate how Authentication can be done. It is fully functional.

CsnUser module consists of:

* Login with remember me
* Registration with Captcha and Confirmation email
* Forgotten password with confirmation email.

In addition, the passwords have two levels of protection - a dynamic and static salt.

### What's the use again? ###

An alternative to ZfcUser with more functionality added.

Installation
------------
1. Installation via composer is supported, simply run: `php composer.phar require coolcsn/csn-user:dev-master`. The installed module is located in *./vendor/coolcsn/csn-user*.

2. Add `CsnUser`, `DoctrineModule` and `DoctrineORMModule` in your application configuration at: `./config/application.config.php`. An example configuration may look like the following :

```
'modules' => array(
    'Application',
    'DoctrineModule',
    'DoctrineORMModule',
    'CsnUser'
)
```

Configuration
-------------
CsnUser requires setting up a Connection for Doctrine, a simple Mail configuration and importing a database schema.

1. Create a new database (or use an existing one, dedicated to your application).

2. Copy the sample Doctrine configuration from `./vendor/coolcsn/csn-user/config/doctrineorm.local.php.dist` to `./config/autoload` renaming it to **doctrineorm.local.php**. Edit the file, replacing the values (*username*, *password*, etc) with your personal database parameters.

3. Run `./vendor/bin/doctrine-module orm:schema-tool:create` to generate the database schema. Import the sample SQL data (for default roles and languages) located in `./vendor/coolcsn/CsnUser/data/SampleData.sql`. You can easily do that with *PhpMyAdmin* for instance.

4. Copy the sample Mail configuration from `./vendor/coolcsn/csn-user/config/mail.config.local.php.dist` to `./config/autoload` renaming it to **mail.config.local.php**. Edit the file, replacing the values (*host*, *username*, etc) with your SMTP server parameters.

Options
-------

The CsnUser module has some options to allow you to quickly customize the basic
functionality. After installing CsnUser, copy
`./vendor/coolcsn/CsnUser/config/csnuser.global.php.dist` to
`./config/autoload`, renaming it to **csnuser.global.php** and change the values as desired, following the instructions.

The following options are available:

- **STATIC_SALT** Constant string value, prepended to the password before hashing
- **login_redirect_route** String value, name of a route in the application
  which the user will be redirected to after a successful login.
- **logout_redirect_route** String value, name of a route in the application which
  the user will be redirected to after logging out.
- **nav_menu** Bool value, show or hide navigation menu.

>### It is ready? ###
Navigate to *[hostname]/user* in your browser to view different options for login, registration, forgotten password, etc.

Dependencies
------------

This Module depends on the following Modules:

 - [Zend Framework 2](https://github.com/zendframework/zf2) 

 - [DoctrineORMModule] (https://github.com/doctrine/DoctrineORMModule) - DoctrineORMModule integrates Doctrine 2 ORM with Zend Framework 2 quickly and easily.

Recommends
----------
- [coolcsn/CsnAuthorization](https://github.com/coolcsn/CsnAuthorization) - Authorization compatible for this Registration and Logging.
 
- [coolcsn/CsnNavigation](https://github.com/coolcsn/CsnNavigation) - Navigation module;
 
- [coolcsn/CsnCms](https://github.com/coolcsn/CsnCms) - Content management system;
