<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Statistics_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/statistics/classes/statistics.php'));

class lC_Statistics_Admin_rpc {
 /*
  * Returns the statistics modules datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Statistics_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the module statsistics datatable data for listings
  *
  * @param string $_GET['module'] The statistics module name
  * @access public
  * @return json
  */
  public static function getListing() {
    $result = lC_Statistics_Admin::getData($_GET['module']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
}
?>