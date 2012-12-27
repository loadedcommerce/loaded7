<?php
/*
  $Id: application_top.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
// start the timer for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());
define('LC_IN_ADMIN', true);

// include server parameters
require('../includes/config.php');

// set the level of error reporting to E_ALL
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);
ini_set('log_errors', true);
ini_set('error_log', DIR_FS_WORK . 'php_errors.log');

// set the type of request (secure or not)
$request_type = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on')) ? 'SSL' : 'NONSSL';
if ($request_type == 'NONSSL') {
  define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
} else {
  define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
}

// compatibility work-around logic for PHP4
require('../includes/functions/compatibility.php');
require('includes/functions/compatibility.php');

// include the list of project filenames
require('includes/filenames.php');

// include the list of project database tables
require('../includes/database_tables.php');

// include the utility class
require('../includes/classes/utility.php');

// instantiate the cache class
require('../includes/classes/cache.php');
$lC_Cache = new lC_Cache();

// include the administrators log class
if ( file_exists('includes/applications/administrators_log/classes/administrators_log.php') ) {
  include('includes/applications/administrators_log/classes/administrators_log.php');
}

// include the database class
require('../includes/classes/database.php');
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
require('../includes/functions/general.php');
require('includes/functions/general.php');
require('../includes/functions/html_output.php');
require('includes/functions/html_output.php');

// include session class
require('../includes/classes/session.php');
$lC_Session = lC_Session::load('osCAdminID');
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

require('includes/classes/directory_listing.php');
require('includes/classes/access.php');
require('../includes/classes/address.php');
require('../includes/classes/weight.php');
require('../includes/classes/xml.php');
require('../includes/classes/datetime.php');

// set the language
require('includes/classes/language.php');
$lC_Language = new lC_Language_Admin();

if (isset($_GET['language']) && !empty($_GET['language'])) {
  $lC_Language->set($_GET['language']);
}
$lC_Language->loadIniFile();

//header('Content-Type: text/html; charset=' . $lC_Language->getCharacterSet());
lc_setlocale(LC_TIME, explode(',', $lC_Language->getLocale()));

// define our localization functions
require('includes/functions/localization.php');

// initialize the message stack for output messages
require('../includes/classes/message_stack.php');
$lC_MessageStack = new lC_MessageStack();

// entry/item info classes
require('includes/classes/object_info.php');

// email class
require('../includes/classes/mail.php');

// file uploading class
require('includes/classes/upload.php');

// api class
require('includes/classes/api.php');
$lC_Api = new lC_Api();

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
