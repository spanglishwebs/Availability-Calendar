<?php
/*
Author: Spanglish Webs
Author URI: http://www.spanglishwebs.com
Description: Availability Calendar and Pricing Table Plugin allows you to create an availability calendar for any post, page or custom post on Wordpress along with a pricing table
Version: V.1.6
License: GPL 2.0
Plugin Name: Availability Calendar and Pricing Table
*/

define('AC_TEXTDOMAIN', 'availability-calendar');

define('AC_AVAILABILITY_CALENDAR_PLUGIN', __FILE__);

define('AC_ROOT', dirname(__FILE__) . '/');

define('AC_SRC', AC_ROOT . 'src/');
define('AC_SRC_PHP', AC_SRC . 'php/');

require_once AC_SRC_PHP . 'constants.php';
require_once AC_SRC_PHP . 'functions.php';
require_once AC_SRC_PHP . 'wordpress.php';
