CsnUser
=======

**What is CsnUser?**

CsnUser is a Module for Authentication based on DoctrineORMModule

**What exactly does CsnUser?**

CsnUser has been created with educational purposes to demonstrate how Authentication can be done. It is fully functional.
CsnUser module have a Registration with Confirmation email and Captcha code that you can identify that real human insert a fields.
And in registration form have many protections - like a dynamic salt and static salt.
Also have a Forgotten password - to recover a your password to account.

So in this module have Authentication - to Login like as your registered account.

**What's the use again?**

Nothing but yet another Authentication Module like ZfcUser and more functionality added.

Installation
============

* 1.Installation via composer is supported, simply add the following line to your ```composer.json``` file;

```
"repositories": [
	{
		"type": "vcs",
		"url": "https://github.com/coolcsn/CsnUser"
	}
],
"require" : {
    "coolcsn/csn-user": "dev-master"
}
```

----
Or 

* Simply via ``composer`` INSERT in Console -> installation method :

```sh
php composer.phar require coolcsn/CsnUser:"dev-master"
```
----
Or 

* After adding to the composer's packagist.org (not ready yet)

```
"require" : {
    "coolcsn/csn-user": "dev-master"
}
```
----

* 2.And you can find the installed module in ``./vendor/coolcsn/csn-user``.


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

1. Doctrine configuration example have in ``./vendor/CsnUser/config/doctrineorm.local.php.dist`` move in ``./config/autoload/doctrineorm.local.php`` 
and change to your dissered fields(database name, host and etc.).

2. Mail Configuration example have in ``./vendor/CsnUser/config/mail.config.local.php.dist`` and you should move in ``./config/autoload/mail.config.local.php``
and change to your dissered fields(mail host, username, password).

3.Import the SQL schema and needed data located in ``./vendor/CsnUser/data/csnDatabase.sql``.

*You can change to your 'static_salt' (other protection for registration with encryption in sha1) in ``module.config.php``.


Dependencies
============

This Module depends on the following Modules:

 - [DoctrineORMModule] (https://github.com/doctrine/DoctrineORMModule) - DoctrineORMModule integrates Doctrine 2 ORM with Zend Framework 2 quickly and easily.

 Recommends
 ============
 * [coolcsn/CsnAuthorization] (https://github.com/coolcsn/CsnAuthorization) - Authorization compatible for this Registration and Logging.
 
 * [coolcsn/CsnNavigation] (https://github.com/coolcsn/CsnNavigation) - Navigation module;
 
 * [coolcsn/CsnCms] (https://github.com/coolcsn/CsnCms) - Content management system;
 
 