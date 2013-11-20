<?php
/**
  @package    catalog::install
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: application_top.php v1.0 2013-08-08 datazen $
*/
if(isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
  header('Location: http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
}
// Set the level of error reporting
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
//ini_set("display_errors", 1);

define('DEFAULT_LANGUAGE', 'en_US');
define('HTTP_COOKIE_PATH', '');
define('HTTPS_COOKIE_PATH', '');
define('HTTP_COOKIE_DOMAIN', '');
define('HTTPS_COOKIE_DOMAIN', '');

// virtual hook system
require_once('../ext/vqmod/vqmod.php');
$lC_Vqmod = new VQMod();

require('../includes/functions/compatibility.php');

require('../includes/functions/general.php');
require('functions/general.php');
require('../includes/functions/html_output.php');

require('../includes/classes/database.php');

require('../includes/classes/xml.php');

session_start();

require('../includes/classes/directory_listing.php');

require('includes/classes/language.php');
$lC_Language = new lC_LanguageInstall();

header('Content-Type: text/html; charset=' . $lC_Language->getCharacterSet());
?>
