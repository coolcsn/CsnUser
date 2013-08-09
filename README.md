CsnUser
=======

**What is CsnUser?**

CsnUser is a Module for Authentication based on DoctrineORMModule

**What exactly does CsnUser?**

CsnUser has been created with educational purposes to demonstrate how Authentication can be done. It is fully functional.
CsnUser module consists of:
* Login with remember me
* Registration with Captcha and Confirmation email
* Forgotten password.
Also registration form has many protections - like a dynamic salt and static salt.

**What's the use again?**

Nothing but yet another Authentication Module like ZfcUser and more functionality added.

Installation
============

* 1.Installation via composer is supported, simply add the following line to your ```composer.json``` file;

```
"require" : {
    "coolcsn/csn-user": "dev-master"
}
```

``php composer.phar update`` (to download module).

----
Or 

* Other installation method: Simply via ``Console with composer``. Insert the following lines to the console of the main directory:

```sh
php composer.phar require coolcsn/csn-user:"dev-master"
```

``php composer.phar update`` (to download module).

----
Or

* Other installation method: Without composer:

* Go [coolcsn/CsnUser] (https://github.com/coolcsn/CsnUser) and Download as "ZIP" file.
Save it and extract it in ``./vendor/`` or ``./module/`` folder.

----

* 2.The installed module is located in ``./vendor/coolcsn/csn-user``.


* 3.An example application configuration ``./config/application.config.php`` could look like the following :

```
'modules' => array(
	...	//previous your modules
    'Application',
    'DoctrineModule',
    'DoctrineORMModule',
    'CsnUser'
)
```


Configuration
=============

This Module doesn't require any special configuration. All that's needed is to set up a Connection for Doctrine and simple Mail configuration.

1. Doctrine configuration example is located in ``./vendor/coolcsn/csn-user/data/doctrineorm.local.php.dist``. Move it to``./config/autoload/doctrineorm.local.php`` 
and change your dissered fields(database name, host and etc.).

2. Mail Configuration example is located in ``./vendor/coolcsn/csn-user/data/mail.config.local.php.dist``. Move it to ``./config/autoload/mail.config.local.php``
and change your dissered fields(mail host, username, password).

3. Import the SQL schema and needed data located in ``./vendor/CsnUser/data/csnDatabase.sql``.

*You can change your 'static_salt' (other protection for registration with encryption in sha1) in ``module.config.php``.

*If you have a problem with time and timezone go in application: ``./public/index.php`` and add this line:
``date_default_timezone_set('Europe/Sofia');`` -> GMT +2;

OR

``date_default_timezone_set('America/Los_Angeles');``

When you finish Installation and Configuration
=============

http://hostname/csn-user/ - to view Module links in Csn User.

http://hostname/csn-user/index/login - to Login in the system.

http://hostname/csn-user/registration/index - to Register in the system.

http://hostname/csn-user/registration/forgotten-password - if already loose your password.

Dependencies
============

This Module depends on the following Modules:

 - [Zend Framework 2](https://github.com/zendframework/zf2) 

 - [DoctrineORMModule] (https://github.com/doctrine/DoctrineORMModule) - DoctrineORMModule integrates Doctrine 2 ORM with Zend Framework 2 quickly and easily.

 Recommends
 ============
 * [coolcsn/CsnAuthorization] (https://github.com/coolcsn/CsnAuthorization) - Authorization compatible for this Registration and Logging.
 
 * [coolcsn/CsnNavigation] (https://github.com/coolcsn/CsnNavigation) - Navigation module;
 
 * [coolcsn/CsnCms] (https://github.com/coolcsn/CsnCms) - Content management system;
 
 License
 ============
 Released under the MIT License. See file LICENSE included with the source code for this project for a copy of the licensing terms.
 
 
