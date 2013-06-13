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

// set the local configuration parameters - mainly for developers
if ( file_exists('includes/local/config.php') ) {
  include('includes/local/config.php');
}

// check for config, include server parameters or go to install
if (is_file('includes/config.php')) {  
  require('includes/config.php');
}

// redirect to the installation module if DB_SERVER is empty
if (!defined(DB_SERVER)) {
  if (is_dir('install')) {
    header('Location: install/index.php');
  } else {
    echo 'Your install directory does not exist!';
    die();
  }
}

// set the level of error reporting
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
//ini_set("display_errors", 1);
ini_set('log_errors', true);
ini_set('error_log', DIR_FS_WORK . 'php_errors.log');

// virtual hook system
require_once('ext/vqmod/vqmod.php');
$lC_Vqmod = new VQMod();
  
// set the type of request (secure or not)
$request_type = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on')) ? 'SSL' : 'NONSSL';

if ($request_type == 'NONSSL') {
  define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
} else {
  define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
}

// compatibility work-around logic for PHP4
require($lC_Vqmod->modCheck('includes/functions/compatibility.php'));

// include the list of project filenames
require($lC_Vqmod->modCheck('includes/filenames.php'));

// include the list of project database tables
require($lC_Vqmod->modCheck('includes/database_tables.php'));

// include the utility class
require($lC_Vqmod->modCheck('includes/classes/utility.php'));     

// initialize the message stack for output messages
require($lC_Vqmod->modCheck('includes/classes/message_stack.php'));
$lC_MessageStack = new lC_MessageStack();

// initialize the cache class
require($lC_Vqmod->modCheck('includes/classes/cache.php'));
$lC_Cache = new lC_Cache();

// include the database class
require($lC_Vqmod->modCheck('includes/classes/database.php'));

// make a connection to the database... now
$lC_Database = lC_Database::connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
$lC_Database->selectDatabase(DB_DATABASE);

// set the application parameters
$Qcfg = $lC_Database->query('select configuration_key as cfgKey, configuration_value as cfgValue from :table_configuration');
$Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
$Qcfg->setCache('configuration');
$Qcfg->execute();

while ($Qcfg->next()) {
  define($Qcfg->value('cfgKey'), $Qcfg->value('cfgValue'));
}

$Qcfg->freeResult();

// include functions
require($lC_Vqmod->modCheck('includes/functions/general.php'));
require($lC_Vqmod->modCheck('includes/functions/html_output.php'));

// include and start the services
require($lC_Vqmod->modCheck('includes/classes/services.php'));
$lC_Services = new lC_Services();
$lC_Services->startServices();
?>