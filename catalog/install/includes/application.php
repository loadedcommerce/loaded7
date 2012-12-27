<?php
/*
  $Id: application_top.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

// Set the level of error reporting
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

define('DEFAULT_LANGUAGE', 'en_US');
define('HTTP_COOKIE_PATH', '');
define('HTTPS_COOKIE_PATH', '');
define('HTTP_COOKIE_DOMAIN', '');
define('HTTPS_COOKIE_DOMAIN', '');

require('../includes/functions/compatibility.php');

require('../includes/functions/general.php');
require('functions/general.php');
require('../includes/functions/html_output.php');

require('../includes/classes/database.php');

require('../includes/classes/xml.php');

session_start();

require('../admin/includes/classes/directory_listing.php');

require('includes/classes/language.php');
$lC_Language = new lC_LanguageInstall();

header('Content-Type: text/html; charset=' . $lC_Language->getCharacterSet());
?>
