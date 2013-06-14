<?php
/**
  $Id: application_top.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
// start the timer for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());
define('LC_IN_ADMIN', true);

// include server parameters
require('../includes/config.php');

// set the level of error reporting to E_ALL
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors", 1);
ini_set('log_errors', true);
ini_set('error_log', DIR_FS_WORK . 'php_errors.log');

// virtual hook system
require_once('external/vqmod/vqmod.php');
$lC_Vqmod = new VQMod();
  
// set the type of request (secure or not)
$request_type = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on')) ? 'SSL' : 'NONSSL';
if ($request_type == 'NONSSL') {
  define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
} else {
  define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
}

// compatibility work-around logic for PHP4 
require($lC_Vqmod->modCheck('../includes/functions/compatibility.php'));
require($lC_Vqmod->modCheck('includes/functions/compatibility.php'));

// include the list of project filenames
require($lC_Vqmod->modCheck('includes/filenames.php'));

// include the list of project database tables
require($lC_Vqmod->modCheck('../includes/database_tables.php'));

// include the utility class
require($lC_Vqmod->modCheck('../includes/classes/utility.php'));

// instantiate the cache class
require($lC_Vqmod->modCheck('../includes/classes/cache.php'));
$lC_Cache = new lC_Cache();

// initally set the language and template cache
if (! file_exists('includes/work/cache/langusges.cache') ) lC_Cache::clear('languages');
if (! file_exists('includes/work/cache/templates.cache') ) lC_Cache::clear('templates');

// include the administrators log class
if ( file_exists('includes/applications/administrators_log/classes/administrators_log.php') ) {
  include($lC_Vqmod->modCheck('includes/applications/administrators_log/classes/administrators_log.php'));
}

// include the database class
require($lC_Vqmod->modCheck('../includes/classes/database.php'));
$lC_Database = lC_Database::connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
$lC_Database->selectDatabase(DB_DATABASE);

// set application wide parameters
$Qcfg = $lC_Database->query('select configuration_key as cfgKey, configuration_value as cfgValue from :table_configuration');
$Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
$Qcfg->setCache('configuration');
$Qcfg->execute();

while ($Qcfg->next()) {
  define($Qcfg->value('cfgKey'), $Qcfg->value('cfgValue'));
}

$Qcfg->freeResult();

// define our general functions used application-wide
require($lC_Vqmod->modCheck('../includes/functions/general.php'));
require($lC_Vqmod->modCheck('includes/functions/general.php'));
require($lC_Vqmod->modCheck('../includes/functions/html_output.php'));
require($lC_Vqmod->modCheck('includes/functions/html_output.php'));

// include session class
require($lC_Vqmod->modCheck('../includes/classes/session.php'));
$lC_Session = lC_Session::load('lCAdminID');
$lC_Session->start();

if ( !isset($_SESSION['admin']) && (basename($_SERVER['PHP_SELF']) != FILENAME_RPC) ) {
  $redirect = false;
  if ( empty($_GET) ) {
    $redirect = true;
  } else {
    $first_array = array_slice($_GET, 0, 1);
    $_module = lc_sanitize_string(basename(key($first_array)));
    if ( $_module != 'login' ) {
      if ( !isset($_SESSION['redirect_origin']) ) {
        $_SESSION['redirect_origin'] = array('module' => $_module,
                                             'get' => $_GET);
      }
      $redirect = true;
    }
  }
  if ( $redirect === true ) {
    lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, 'login'));
  }
  unset($redirect);
}

require($lC_Vqmod->modCheck('includes/classes/access.php'));
require($lC_Vqmod->modCheck('../includes/classes/directory_listing.php'));
require($lC_Vqmod->modCheck('../includes/classes/address.php'));
require($lC_Vqmod->modCheck('../includes/classes/weight.php'));
require($lC_Vqmod->modCheck('../includes/classes/xml.php'));
require($lC_Vqmod->modCheck('../includes/classes/datetime.php'));

// set the language
require($lC_Vqmod->modCheck('includes/classes/language.php'));
$lC_Language = new lC_Language_Admin();

if (isset($_GET['language']) && !empty($_GET['language'])) {
  $lC_Language->set($_GET['language']);
}
$lC_Language->loadIniFile();

//header('Content-Type: text/html; charset=' . $lC_Language->getCharacterSet());
lc_setlocale(LC_TIME, explode(',', $lC_Language->getLocale()));

// define our localization functions
require($lC_Vqmod->modCheck('includes/functions/localization.php'));

// initialize the message stack for output messages
require($lC_Vqmod->modCheck('includes/classes/message_stack.php'));
$lC_MessageStack = new lC_MessageStack_Admin();

// entry/item info classes
require($lC_Vqmod->modCheck('includes/classes/object_info.php'));

// email class
require($lC_Vqmod->modCheck('../includes/classes/mail.php'));

// file uploading class
require($lC_Vqmod->modCheck('includes/classes/upload.php'));

// api class
require($lC_Vqmod->modCheck('includes/classes/api.php'));
$lC_Api = new lC_Api();

// QR code class
require('../includes/classes/BarcodeQR.php');
$BarcodeQR = new BarcodeQR();

// templates general class
require($lC_Vqmod->modCheck('templates/default/classes/general.php'));

// instantiate the addons class
require('includes/classes/addons.php');
$lC_Addons = new lC_Addons_Admin();

// check if a default currency is set
if (!defined('DEFAULT_CURRENCY')) {
  $lC_MessageStack->add('header', $lC_Language->get('ms_error_no_default_currency'), 'error');
}

// check if a default language is set
if (!defined('DEFAULT_LANGUAGE')) {
  $lC_MessageStack->add('header', ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
}

if (function_exists('ini_get') && ((bool)ini_get('file_uploads') == false) ) {
  $lC_MessageStack->add('header', $lC_Language->get('ms_warning_uploads_disabled'), 'warning');
}
?>