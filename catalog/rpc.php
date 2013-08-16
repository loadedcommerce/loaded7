<?php
/**
  @package    catalog::core
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

$_SERVER['SCRIPT_FILENAME'] = 'index.php';

require('includes/application_top.php');

$lC_Language->load('index');

$lC_Template = lC_Template::setup('index');

define('RPC_STATUS_SUCCESS', 1);
define('RPC_STATUS_NO_SESSION', -10);
define('RPC_STATUS_NO_MODULE', -20);
define('RPC_STATUS_NO_ACCESS', -50);
define('RPC_STATUS_CLASS_NONEXISTENT', -60);
define('RPC_STATUS_METHOD_NONEXISTENT', -65);
define('RPC_STATUS_NO_ACTION', -70);
define('RPC_STATUS_ACTION_NONEXISTENT', -71);

$class = 'rpc';

$first_array = array_slice($_GET, 0, 1);
$module = lc_sanitize_string(basename(key($first_array)));

if (empty($module)) $module = $lC_Template->getCode();

$action = (isset($_GET['action']) && !empty($_GET['action'])) ? lc_sanitize_string(basename($_GET['action'])) : '';

if ( file_exists('templates/' . $module . '/classes/' . $class . '.php')) {
  include('templates/' . $module . '/classes/' . $class . '.php');
  if ( method_exists('lC_' . ucfirst($module) . '_' . $class, $action) ) {
    call_user_func(array('lC_' . ucfirst($module) . '_' . $class, $action));
    exit;
  } else {
    echo json_encode(array('rpcStatus' => RPC_STATUS_METHOD_NONEXISTENT . ': lC_' . ucfirst($module) . '_' . $class . ' ' . $stat));
    exit;
  }
} else if ( file_exists('includes/rpc/' . $module . '/' . $class . '.php')) { 
  include('includes/rpc/' . $module . '/' . $class . '.php');
  if ( method_exists('lC_' . ucfirst($module) . '_' . $class, $action) ) {
    call_user_func(array('lC_' . ucfirst($module) . '_' . $class, $action));
    exit;
  } else {
    echo json_encode(array('rpcStatus' => RPC_STATUS_METHOD_NONEXISTENT . ': lC_' . ucfirst($module) . '_' . $class . ' ' . $stat));
    exit;
  }  
} else {
  echo json_encode(array('rpcStatus' => RPC_STATUS_CLASS_NONEXISTENT . ': templates/' . $module . '/classes/' . $class . '.php'));
  exit;
}
?>