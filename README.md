CsnUser
=======

**What is CsnUser?**

CsnUser is a Module for Authentication based on DoctrineORMModule

**What exactly does CsnUser?**

CsnUser has been created with educational purposes to demonstrate how Authentication can be done. It is fully functional.

**What's the use again?**

Nothing but yet another Authentication Module like ZfcUser.

Installation
============

Installation via composer is supported, simply add the following line to your ```composer.json```

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

After adding to the composer's packagist.org (not ready yet)

```
"require" : {
    "coolcsn/csn-user": "dev-master"
}
```

An example application configuration ``mainZendApp/config/application.config.php`` could look like the following :

```
'modules' => array(
    'Application',
    'DoctrineModule',
    'DoctrineORMModule',
    'CsnUser'
)
```

Configuration
=============

This Module doesn't require any special configuration. All that's needed is to set up a Connection for Doctrine.
https://github.com/doctrine/DoctrineORMModule -> See how to set up a Connection for Doctrine.
Doctrine configuration example have in ``CsnUser/config/doctrineorm.local.php.dist`` move in ``mainZendApp/config/autoload/doctrineorm.local.php`` 
and fix your fields.

Mail Configuration example have in ``CsnUser/config/mail.config.local.php.dist`` and you should move in ``mainZendApp/config/autoload/mail.config.local.php``.

You can change to your 'static_salt' (other protection for registration with encryption in sha1) in ``module.config.php``.

You need to import a SQL file that you can find in ``CsnUser/data/csnDatabase.sql`` in your database to be created schema and needed data;

Dependencies
============

This Module depends on the following Modules:

 - DoctrineORMModule

 Reccommends
 ============
 - CsnUser/Authorisation - Authorisation compatible for this Registration and Logging.
 - CsnUser/Navigation - Navigation panel;
 - CsnUser/Cms - Content management system;