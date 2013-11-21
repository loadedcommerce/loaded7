<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/zone_groups/classes/zone_groups.php'));

class lC_Zone_groups_Admin_rpc {
 /*
  * Returns the zone groups datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Zone_groups_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the zone groups data used on the dialog forms
  *
  * @param integer $_GET['zid'] The zone group id
  * @access public
  * @return json
  */
  public static function getData() {
    $result = lC_Zone_groups_Admin::get($_GET['zid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Save the zone group
  *
  * @param string $_GET['zid'] The zone group id on update, null on insert
  * @param array $_GET The zone group data
  * @access public
  * @return json
  */
  public static function saveGroup() {
    $result = array();
    $saved = lC_Zone_groups_Admin::save($_GET['zid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the zone group
  *
  * @param string $_GET['zid'] The zone group id
  * @access public
  * @return json
  */
  public static function deleteGroup() {
    $result = array();
    $deleted = lC_Zone_groups_Admin::delete($_GET['zid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete zone groups
  *
  * @param array $_GET['batch'] The zone group id's to delete
  * @access public
  * @return json
  */
  public static function batchDelete() {
    $result = lC_Zone_groups_Admin::batchDeleteGroups($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Returns the zone groups entries datatable data for listings
  *
  * @param string $_GET[$_module] The admin module
  * @access public
  * @return json
  */
  public static function getAllEntries() {
    global $_module;

    $result = lC_Zone_groups_Admin::getAllEntries($_GET[$_module]);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the geo zones data for a country
  *
  * @param integer $_GET['country_id'] The country id
  * @access public
  * @return json
  */
  public static function getZones() {
    $result = lC_Zone_groups_Admin::getZones($_GET['country_id']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the zone group entries data used on the dialog forms
  *
  * @param integer $_GET['zaid'] The geo zone id
  * @access public
  * @return json
  */
  public static function getEntryFormData() {
    global $_module;

    $result = lC_Zone_groups_Admin::getEntryFormData($_GET['zaid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Save the zone group entry
  *
  * @param string $_GET['zaid'] The geo zone group entry id on update, null on insert
  * @param array $_GET The zone group entry data
  * @access public
  * @return json
  */
   public static function saveEntry() {
    $result = array();
    $saved = lC_Zone_groups_Admin::saveEntry($_GET['zaid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the zone group entry
  *
  * @param string $_GET['zaid'] The geo zone group entry id on update, null on insert
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Zone_groups_Admin::deleteEntry($_GET['zaid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete zone group entries
  *
  * @param array $_GET['batch'] The zone group entry id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Zone_groups_Admin::batchDeleteEntries($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>