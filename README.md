RECRUITMENT TASK XSOLVE
=======================

This is PHP app written in symfony framework 3.3 and PHP 5.6.
The main goeal is benchmark loading time of the website in comparison to the other websites.

Installation
------------

  * Download or clone with git this project and install it like any other [Symfony application][1]

  * Configure email client in app/config/config.yml file

  * Run local webserwer from console command php bin/console server:start and check http://127.0.0.1:8000


What's inside?
--------------

* Controler

src: src/AppBundle/Controler/DefaultControler.php
It controls requests for website simple form, call BenchamrkService if form is send, and return results.

* Form

src: src/AppBundle/Form/WebisteForm.php
It is form with website ulr and website competitors as collection field.

* Model

src: src/AppBundle/Model/Webiste.php
It is model of website with domain time and error values.

* Service

src: src/AppBundle/Service/BenchmarkService.php
It is service which handles benchmark of each website send by a WebisteForm. It also sends an email if a website is loading slow.
The main function in this service is benchmark(), which checks loading time on each Website, by file_get_contents function.
It also sorts results with compareWebsites() static function.

* Resorces

src: src/AppBundle/Resorces/views/default/index.html.twig
It is twig template for WebisteForm which handles fomr's collection by javasript functions.
This twig temaplete uses bootstrap.css and jquery.js hosting in cdn.

src: src/AppBundle/Resorces/views/Emails/slower.html.twig
It is email temple which presents information about slow website.

* Tests

src: tests/AppBundle/Controller/DefaultControllerTest.php

It is simple WebTestCase class with 2 funcional tests testIndex() and testSubmit().
It tests if site is working correctly and form is returning correct data.

Enjoy!

[1]:  https://symfony.com/doc/3.3/setup.html

