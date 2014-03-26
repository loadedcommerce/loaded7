<?php
/**
  @package    admin
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

ini_set('display_errors', 0);
ini_set('error_reporting', 0);

require('includes/application_top.php');
require($lC_Vqmod->modCheck('includes/classes/template.php'));

$_SESSION['module'] = 'index';

require_once($lC_Vqmod->modCheck('../includes/classes/currencies.php'));
$lC_Currencies = new lC_Currencies();

$lC_Language->loadIniFile($_SESSION['module'] . '.php');

require($lC_Vqmod->modCheck('includes/applications/' . $_SESSION['module'] . '/' . $_SESSION['module'] . '.php'));

$lC_Template = lC_Template_Admin::setup($_SESSION['module']);
$lC_Template->set('default');

define('RPC_STATUS_SUCCESS', 1);
define('RPC_STATUS_NO_SESSION', -10);
define('RPC_STATUS_NO_MODULE', -20);
define('RPC_STATUS_NO_ACCESS', -50);
define('RPC_STATUS_CLASS_NONEXISTENT', -60);
define('RPC_STATUS_METHOD_NONEXISTENT', -65);
define('RPC_STATUS_NO_ACTION', -70);
define('RPC_STATUS_ACTION_NONEXISTENT', -71); 

if ( !isset($_SESSION['admin']) ) {
  if ( // the following need no session to proceed
      isset($_GET['action']) && $_GET['action'] == 'validateLogin' || 
      isset($_GET['action']) && $_GET['action'] == 'lostPasswordConfirmEmail' || 
      isset($_GET['action']) && $_GET['action'] == 'lostPasswordConfirmKey' ||  
      isset($_GET['action']) && $_GET['action'] == 'passwordChange' ||  
      isset($_GET['action']) && $_GET['action'] == 'apiHealthCheck' ||  
      isset($_GET['action']) && $_GET['action'] == 'validateSerial'
      ) {
  } else {     
    echo json_encode(array('rpcStatus' => RPC_STATUS_NO_SESSION));
    exit;
  }
}

$module = null;
$class = null;

if ( empty($_GET) && $_GET['action'] != 'validateLogin') {
  echo json_encode(array('rpcStatus' => RPC_STATUS_NO_MODULE));
  exit;
} else {
  $first_array = array_slice($_GET, 0, 1);
  $_module = lc_sanitize_string(basename(key($first_array)));

  
  if ( !lC_Access::hasAccess($_module) && $_GET['action'] != 'validateLogin' && !isset($_GET['addon'])) {
    echo json_encode(array('rpcStatus' => RPC_STATUS_NO_ACCESS));
    exit;
  }

  $class = (isset($_GET['class']) && !empty($_GET['class'])) ? lc_sanitize_string(basename($_GET['class'])) : 'rpc';
  $action = (isset($_GET['action']) && !empty($_GET['action'])) ? lc_sanitize_string(basename($_GET['action'])) : '';

  if ( empty($action) ) {
    echo json_encode(array('rpcStatus' => RPC_STATUS_NO_ACTION));
    exit;
  }  
  
  if ($action != 'search') {
    
    if ( file_exists('includes/applications/' . $_module . '/classes/' . $class . '.php')) {
      include($lC_Vqmod->modCheck('includes/applications/' . $_module . '/classes/' . $class . '.php'));
      if ( method_exists('lC_' . ucfirst($_module) . '_Admin_' . $class, $action) ) {
        call_user_func(array('lC_' . ucfirst($_module) . '_Admin_' . $class, $action));
        exit;
      } else {
        echo json_encode(array('rpcStatus' => RPC_STATUS_METHOD_NONEXISTENT . ': lC_' . ucfirst($_module) . '_Admin_' . $class . ' ' . $stat));
        exit;
      }
    } else if (isset($_GET['addon']) && empty($_GET['addon']) === false) { //addons
    
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $_GET['addon'] . '/admin/applications/' . $_module . '/classes/' . $class . '.php')) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/' . $_GET['addon'] . '/admin/applications/' . $_module . '/classes/' . $class . '.php'));
        if ( method_exists('lC_' . ucfirst($_module) . '_Admin_' . $class, $action) ) {
          call_user_func(array('lC_' . ucfirst($_module) . '_Admin_' . $class, $action));
          exit;
        } else {
          echo json_encode(array('rpcStatus' => RPC_STATUS_METHOD_NONEXISTENT . ': lC_' . ucfirst($_module) . '_Admin_' . $class . ' ' . $stat));
          exit;
        }
      } else {
        echo json_encode(array('rpcStatus' => RPC_STATUS_ACTION_NONEXISTENT . ': includes/applications/' . $_module . '/classes/' . $class . '.php'));
        exit;
      }    
    
    } else {
      echo json_encode(array('rpcStatus' => RPC_STATUS_ACTION_NONEXISTENT . ': includes/applications/' . $_module . '/classes/' . $class . '.php'));
      exit;
    }  
    
  } else {
    $_module = 'general';
    if ( file_exists('templates/default/classes/' . $class . '.php')) {
      include($lC_Vqmod->modCheck('templates/default/classes/' . $class . '.php'));
      if ( method_exists('lC_' . ucfirst($_module) . '_Admin_' . $class, $action) ) {
        call_user_func(array('lC_' . ucfirst($_module) . '_Admin_' . $class, $action));
        exit;
      } else {
        echo json_encode(array('rpcStatus' => RPC_STATUS_METHOD_NONEXISTENT . ': lC_' . ucfirst($_module) . '_Admin_' . $class . ' ' . $stat));
        exit;
      }
    } else {
      echo json_encode(array('rpcStatus' => RPC_STATUS_ACTION_NONEXISTENT . ': includes/applications/' . $_module . '/classes/' . $class . '.php'));
      exit;
    }        
  }    
}
?>
