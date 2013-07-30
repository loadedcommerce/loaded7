<?php
/**  
*  $Id: rpc.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

$_SERVER['SCRIPT_FILENAME'] = 'index.php';

require('includes/application_top.php');

$lC_Language->load('index');

$lC_Template = lC_Template::setup('index');

define('RPC_STATUS_SUCCESS', 1);
define('RPC_STATUS_NO_SESSION', -10);
define('RPC_STATUS_CLASS_NONEXISTENT', -60);
define('RPC_STATUS_METHOD_NONEXISTENT', -65);


$module = $lC_Template->getCode();
$class = 'rpc';
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
} else {
  echo json_encode(array('rpcStatus' => RPC_STATUS_CLASS_NONEXISTENT . ': templates/' . $module . '/classes/' . $class . '.php'));
  exit;
}
?>