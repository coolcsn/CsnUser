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
* Forgotten password.

In addition, the passwords have two levels of protection protection - a dynamic and static salt.

### What's the use again? ###

An alternative to ZfcUser with more functionality added.

Installation
------------
1. Installation via composer is supported, simply run: `php composer.phar require coolcsn/csn-user:dev-master`. The installed module is located in `./vendor/coolcsn/csn-user`.

2. Add `CsnUser` in your application configuration at: `./config/application.config.php`. An example configuration may look like the following :

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
CsnUser requires setting up a Connection for Doctrine and a simple Mail configuration.

1. Doctrine configuration example is located in `./vendor/coolcsn/csn-user/config/doctrineorm.local.php.dist`. Move it to `./config/autoload/doctrineorm.local.php` replacing the tokens.

2. Mail Configuration example is located in `./vendor/coolcsn/csn-user/config/mail.config.local.php.dist`. Move it to `./config/autoload/mail.config.local.php` replacing the tokens.

3. Import the SQL schema located in `./vendor/coolcsn/CsnUser/data/csnDatabase.sql` (by using for example *phpMyAdmin*). Another option is to use the doctrine-module tool, but this way you will have to import sample Roles and Languages manually in your database.

- You can change your 'static_salt' in `module.config.php`.

- If you have a problem with time and timezone open: `./public/index.php` and add this line: `date_default_timezone_set('Europe/Sofia');` or for example: `date_default_timezone_set('America/Los_Angeles');`

Options
-------

The CsnUser module has some options to allow you to quickly customize the basic
functionality. After installing CsnUser, copy
`./vendor/coolcsn/CsnUser/config/csnuser.global.php.dist` to
`./config/autoload/csnuser.global.php` and change the values as desired.

The following options are available:

- **login_redirect_route** String value, name of a route in the application
  which the user will be redirected to after a successful login.
- **logout_redirect_route** String value, name of a route in the application which
  the user will be redirected to after logging out.

When you finish Installation and Configuration
----------------------------------------------

http://hostname/csn-user/ - to view different options in CsnUser.

http://hostname/csn-user/login - to Login in the system.

http://hostname/csn-user/registration - to Register in the system.

http://hostname/csn-user/forgotten-password - to receive a new password on your email.

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