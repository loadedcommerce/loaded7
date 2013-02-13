<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  require('includes/application_top.php');
  require('includes/classes/template.php');

  $_SESSION['module'] = 'index';

  require_once('../includes/classes/currencies.php');
  $lC_Currencies = new lC_Currencies();

  $lC_Language->loadIniFile($_SESSION['module'] . '.php');

  require('includes/applications/' . $_SESSION['module'] . '/' . $_SESSION['module'] . '.php');

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
    if (isset($_GET['action']) && $_GET['action'] == 'validateLogin') {
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

    
    if ( !lC_Access::hasAccess($_module) && $_GET['action'] != 'validateLogin' ) {
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
        include('includes/applications/' . $_module . '/classes/' . $class . '.php');
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
      $_module = 'general';
      if ( file_exists('templates/default/classes/' . $class . '.php')) {
        include('templates/default/classes/' . $class . '.php');
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