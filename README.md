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

In addition, passwords are encrypted using Bcrypt algorithm.

### What's the use again? ###

An alternative to ZfcUser with more functionality added.

Installation
------------
1. Installation via composer is supported, simply run: `php composer.phar require coolcsn/csn-user:dev-master`. The installed module is located in *./vendor/coolcsn/csn-user*.

2. Create Doctrine Proxy cache directory in APP_ROOT/data/DoctrineORMModule/Proxy. Be shure to grant write permissions.

3. Add `CsnUser`, `DoctrineModule` and `DoctrineORMModule` in your application configuration at: `./config/application.config.php`. An example configuration may look like the following :

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

3. Run `./vendor/bin/doctrine-module orm:schema-tool:create` to generate the database schema. Import the sample SQL data (for some default data) located in `./vendor/coolcsn/CsnUser/data/SampleData.sql`. You can easily do that with *PhpMyAdmin* for instance.

4. Copy the sample Mail configuration from `./vendor/coolcsn/csn-user/config/mail.config.local.php.dist` to `./config/autoload` renaming it to **mail.config.local.php**. Edit the file, replacing the values (*host*, *username*, etc) with your SMTP server parameters.

5. Append the contents of `./vendor/coolcsn/CsnUser/data/CsnUser.css` to APP_ROOT/public/css/styles.css or include `CsnUser.css` into your app.

Options
-------

The CsnUser module has some options to allow you to quickly customize the basic
functionality. After installing CsnUser, copy
`./vendor/coolcsn/CsnUser/config/csnuser.global.php.dist` to
`./config/autoload`, renaming it to **csnuser.global.php** and change the values as desired, following the instructions.

The following options are available:

- **login_redirect_route** String value, name of a route in the application
  which the user will be redirected to after a successful login.
- **logout_redirect_route** String value, name of a route in the application which
  the user will be redirected to after logging out.
- **sender_email_adress** String value, email address to set From field of generated
  emails from module
- **nav_menu** Bool value, show or hide navigation menu.
- **captcha_char_num** Integer Value, number of captcha characters to display.
- **display_exceptions** Boolean true/false value, set this to true to view possible
  exceptions details. If you are in production, then set it to false so exceptions get
  less verbose.

>### It is ready? ###
Navigate to *[hostname]/user* in your browser to view different options for login, registration, forgotten password, etc.

Routes
------------
The following routes are available:

- **user** Welcome view.
- **user/login** User login view.
- **user/register** User register view.
- **user/register/reset-password** User resep password view.
- **user/register/edit-profile** User edit profile view.
- **user/register/change-password** User change password view.
- **user/register/change-email** User change email view.
- **user/register/change-security-question** User change security question view.
- **user/admin** Users admin view.
- **user/admin/create** User admin create view.

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


