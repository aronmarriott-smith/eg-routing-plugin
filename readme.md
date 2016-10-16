#EG Routing Plugin

[![Build Status](https://api.travis-ci.org/aronmarriott-smith/eg-routing-plugin.png)](https://travis-ci.org/aronmarriott-smith/eg-routing-plugin)
[![Code Climate](https://codeclimate.com/github/aronmarriott-smith/eg-routing-plugin/badges/gpa.svg)](https://codeclimate.com/github/aronmarriott-smith/eg-routing-plugin)

This plugin is a demonstration of how to create a reusable *Laravel like* router for WordPress.

##Requirements

* To run this plugin you will need a copy of WordPress 4.4 or grater - you can download the latest version from [wordpress.org](https://wordpress.org/download/).
* The recommended version of PHP to run this plugin is 5.6 or grater. However our plugin is backwards compatible to PHP 5.3.

##Quick Start

###1. Download The Plugin

First you will need to download this plugin - currently the best way is via Git.

####Via Git:

Go to your WordPress plugins directory.
Then clone the repository using Git:

```
git clone git@github.com:aronmarriott-smith/eg-routing-plugin.git eg-routing-plugin
```

###2. Activate the plugin

Now you can active this plugin from the WordPress admin plugins area.

###3. Test

To test this plugin simply go to the following URL endpoint on your website:

```
/wp-json/eg_routing/api/say-hello
```
You should see the string `'Hello world'`. If you see a 404 page instead check you have [pretty permalinks](https://codex.wordpress.org/Using_Permalinks#Choosing_your_permalink_structure) turned on.

##Development

This plugin is in active development and all contributions are welcome. If you are sending a pull request please try to stick to the [WordPress PHP coding standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/) where possible. If you find a bug or wish to report an issue please [do that here](https://github.com/aronmarriott-smith/eg-routing-plugin/issues).

###To Do
* Allow closures to be passed in the route callbacks
* Allow for strings to be passed into routes e.g. `'MyController@method'`
* Add support for required route parameters e.g. `'posts/{post}/comments/{comment}'`
* Add support for optional route parameters e.g. `'user/{name?}'`
* Allow for multiple HTTP methods for one route
* Allow user to override the location of their routes file
* Allow user to override the location of their controllers directory
* ~~Unit test the router class~~
* ~~Hook our repo up to [Travis CI](https://travis-ci.org/)~~
* ~~Rewrite plugin to use WordPress Rest API~~
* ~~Setup unit tests for this plugin~~
* ~~Create plugin~~

###Not Planning On Doing
* Route Groups
* Route Model Binding
* Resourceful routing

###Development Log
* 2016-09-04 - Added unit tests for the router class.
* 2016-09-03 - Hooked our repo up to [Travis CI](https://travis-ci.org/aronmarriott-smith/eg-routing-plugin)
* 2016-09-03 - Rewrote plugin to use the WordPress Rest API
* 2016-09-01 - Added unit tests
* 2016-08-30 - Initial commit
