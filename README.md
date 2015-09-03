
	      ____                  __          ____
	     / __ \____  _____ ____/ /_  ____  / / /_
	    / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/
	   / ____/ /_/ (__  |__  ) /_/ / /_/ / / /_
	  /_/    \__,_/____/____/_,___/\____/_/\__/
	
	The open-source password management solution for teams
	(c) 2012-2015 passbolt.com



Getting started
===============

Prerequisite
------------

You will need browser with the passbolt plugin

You will need a webserver with SSL enabled

You will need to install php5 and the following modules directly or using pear/pecl:
- mod_rewrite 	http://book.cakephp.org/2.0/en/installation/url-rewriting.html
- Imagick		﻿http://php.net/manual/en/book.imagick.php
- gnupg 		http://php.net/manual/en/gnupg.installation.php
- Composer 		﻿https://getcomposer.org/

For testing, code styling and coverage:
- Phpunit 		﻿https://phpunit.de/
- Curl 			http://php.net/manual/en/curl.installation.php
- PhpCS			﻿https://pear.php.net/manual/en/package.php.php-codesniffer.php
- XDebug		﻿http://xdebug.org/

Getting the code
----------------

Clone the repository and associated submodules
```
	git clone git@github.com:passbolt/passbolt
	cd passbolt
	git submodule update --init
```

Configuration
-------------

Copy the core configuration file, change the cypher seed and salt
```
	cp app/Config/core.php.default app/Config/core.php
```

Copy the database configuration file and edit the database name and credentials
```
	cp app/Config/database.php.default app/Config/database.php
	nano app/Config/database.php
```

Copy the app configuration file and check the settings
```
	cp app/Config/app.php.default app/Config/app.php
```

Installation script
-------------------

Install Composer app files (to install vendor and plugin dependencies).
```
	cd app
	composer install --no-dev
```

Run the install script from the cakephp root with the data flag set
if you want to install the test data add the relevant parameter.
```
  cd ..
	./app/Console/cake install [--data=1]
```

Check if it works!


Emails settings
---------------
Emails are placed in a queue that needs to be processed by a CakePhp Shell.
To do so, execute the following command from your app folder :
```
	Console/cake EmailQueue.sender
```

You can also see the corresponding documentation here:
https://github.com/lorenzo/cakephp-email-queue

Or launch it at regular intervals through cron. For example in :
```
	﻿crontab -e
```

You can add a call to the script to run every minutes:
```
	* * * * * /var/www/passbolt/app/Console/cake EmailQueue.sender > /var/log/passbolt.log
```

See more: ﻿https://en.wikipedia.org/wiki/Cron


Frequently Asked Questions
===========================

Why am I getting a segmentation fault at install?
-------------------------------------------------

It is possible that your $GNUPGHOME is not set or not available to either the php CLI or Apache users thus causing
a segmentation fault.
- Check app.php if you don't have ssh access, it can be set at run time.
- Make sure the directory is accessible and writable for these users


Why are images not displayed in the emails?
--------------------------------------------

For images that are send in emails, we need to tell cakephp what is the base url.
To fix this, add/uncomment this line in Config/core.php
```
	Configure::write('App.fullBaseUrl', 'http://{your domain without slash}');
```

How to edit the LESS/CSS files?
-------------------------------

Install grunt and grunt
```
	npm install -g grunt-cli
```

Install the needed modules defined in the grunt config
```
	npm install
```

Install the styleguide
```
	npm install -g bower
	bower install
	grunt styleguide-deploy
```

Make sure Grunt watch for less changes and compile them into CSS
```
	grunt watch
```

Edit one LESS file to see if it works!
Make sure that if you need to make change to the styleguide to fork or request changes to be included upstream.


How do I run the unit tests?
----------------------------

To execute the test suite, you will need to install phpunit.
The simplest way is to do it through composer.
```
composer install
```

Make sure Debug is set to at least 1 in Config/app.php
You can then go to test.php and run the tests from there.
For example: ﻿http://localhost/passbolt/test.php

Selenium tests are available on separate project:
https://github.com/passbolt/passbolt_selenium


How to regenerate the fixtures?
-------------------------------

The fixtures are generated from the DataExtra plugin. It is better you change the data there
and rexport the content as fixtures.
```
./app/Console/cake DataExtras.data export
```

Note that the tests are tightly coupled with the extra data set.
If you change it you may need to change the tests. You can add more record safely of course.


How do I recompile the Javascript?
----------------------------------

Install grunt if it hasn't yet been installed
```
	npm install -g grunt-cli
```

Install the needed modules defined in the grunt config
```
	npm install
```

Prepare the production release
```
	grunt production
```

CSS minified files should have been generated as the Javascript minified file.

How do I check the code standards?
----------------------------------

To run the sniffs for CakePHP coding standards:
```
    phpcs -p --extensions=php --standard=CakePHP ./lib/Cake
```

Credits
=========

Passbolt Team Rocket
--------------------

Kevin, Cedric, Remy, Aurelie, Ismael & Myriam


CakePHP
--------

CakePHP is a rapid development framework for PHP which uses commonly known design patterns like Active Record,
Association Data Mapping, Front Controller and MVC.Our primary goal is to provide a structured framework that
 enables PHP users at all levels to rapidly develop robust web applications, without any loss to flexibility.

[CakePHP](http://www.cakephp.org) - The rapid development PHP framework

[Cookbook](http://book.cakephp.org) - THE Cake user documentation; start learning here!

[Plugins](http://plugins.cakephp.org/) - A repository of extensions to the framework

[The Bakery](http://bakery.cakephp.org) - Tips, tutorials and articles

[API](http://api.cakephp.org) - A reference to Cake's classes

[CakePHP TV](http://tv.cakephp.org) - Screen casts from events and video tutorials

[The Cake Software Foundation](http://cakefoundation.org/) - promoting development related to CakePHP

[Our Google Group](http://groups.google.com/group/cake-php) - community mailing list and forum

[#cakephp](http://webchat.freenode.net/?channels=#cakephp) on irc.freenode.net - Come chat with us, we have cake.

[Q & A](http://ask.cakephp.org/) - Ask questions here, all questions welcome

[Lighthouse](http://cakephp.lighthouseapp.com/) - Got issues? Please tell us!

[![Bake Status](https://secure.travis-ci.org/cakephp/cakephp.png?branch=master)](http://travis-ci.org/cakephp/cakephp)

![Cake Power](https://raw.github.com/cakephp/cakephp/master/lib/Cake/Console/Templates/skel/webroot/img/cake.power.gif)
