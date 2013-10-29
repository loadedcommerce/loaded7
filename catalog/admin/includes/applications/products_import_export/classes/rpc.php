<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Product_variants_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/products_import_export/classes/products_import_export.php'));

class lC_Products_import_export_Admin_rpc {
 /*
  * Returns the entries data used on the dialog forms
  *
  * @param integer $_GET['pveid'] The product variant group entry id
  * @access public
  * @return json
  */
  public static function getEntryFormData() {
    $result = lC_Products_import_export_Admin::getEntryFormData($_GET['pveid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
  
 /*
  * Get total number of products to export after filter
  *
  * @param $_GET['filter'] An string filter type
  * @param $_GET['type'] An string export type
  * @access public
  * @return json
  */ 
  public static function getFilterTotal() {
    $result = lC_Products_import_export_Admin::getFilterTotal($_GET['filter'], $_GET['type']);
    if (isset($result['total']) && $result['total'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
  
 /*
  * Get products data set
  *
  * @param $_GET['pfilter'] An string filter type
  * @param $_GET['pgtype'] An string export type
  * @param $_GET['pgformat'] An string export format
  * @access public
  * @return json
  */ 
  public static function getProducts() {
    $result = lC_Products_import_export_Admin::getProducts($_GET['pfilter'], $_GET['pgtype'], $_GET['pgformat']);
    if (isset($result['url']) && $result['url'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Get categories data set
  *
  * @param $_GET['cfilter'] An string filter type
  * @param $_GET['cgformat'] An string export type
  * @access public
  * @return json
  */ 
  public static function getCategories() {
    $result = lC_Products_import_export_Admin::getCategories($_GET['cfilter'], $_GET['cgformat']);
    if (isset($result['url']) && $result['url'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Get categories data set
  *
  * @param $_GET['cfilter'] An string filter type
  * @param $_GET['cgformat'] An string export type
  * @access public
  * @return json
  */ 
  public static function getOptionGroups() {
    $result = lC_Products_import_export_Admin::getOptionGroups($_GET['ofilter'], $_GET['ogformat']);
    if (isset($result['url']) && $result['url'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Get categories data set
  *
  * @param $_GET['cfilter'] An string filter type
  * @param $_GET['cgformat'] An string export type
  * @access public
  * @return json
  */ 
  public static function getOptionVariants() {
    $result = lC_Products_import_export_Admin::getOptionVariants($_GET['ofilter'], $_GET['ogformat']);
    if (isset($result['url']) && $result['url'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Get categories data set
  *
  * @param $_GET['cfilter'] An string filter type
  * @param $_GET['cgformat'] An string export type
  * @access public
  * @return json
  */ 
  public static function getOptionProducts() {
    $result = lC_Products_import_export_Admin::getOptionProducts($_GET['ofilter'], $_GET['ogformat']);
    if (isset($result['url']) && $result['url'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Get products data set
  *
  * @param $_GET['filter'] An string filter type
  * @param $_GET['pgtype'] An string export type
  * @access public
  * @return json
  */ 
  public static function importProducts() {
    $result = lC_Products_import_export_Admin::importProducts($_GET['pwizard'], $_GET['ptype'], $_GET['pbackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Get products data set
  *
  * @param $_GET['filter'] An string filter type
  * @param $_GET['pgtype'] An string export type
  * @access public
  * @return json
  */ 
  public static function importCategories() {
    $result = lC_Products_import_export_Admin::importCategories($_GET['cwizard'], $_GET['ctype'], $_GET['cbackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Get products data set
  *
  * @param $_GET['filter'] An string filter type
  * @param $_GET['pgtype'] An string export type
  * @access public
  * @return json
  */ 
  public static function importOptionGroups() {
    $result = lC_Products_import_export_Admin::importOptionGroups($_GET['owizard'], $_GET['otype'], $_GET['obackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Get products data set
  *
  * @param $_GET['filter'] An string filter type
  * @param $_GET['pgtype'] An string export type
  * @access public
  * @return json
  */ 
  public static function importOptionVariants() {
    $result = lC_Products_import_export_Admin::importOptionVariants($_GET['owizard'], $_GET['otype'], $_GET['obackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Get products data set
  *
  * @param $_GET['filter'] An string filter type
  * @param $_GET['pgtype'] An string export type
  * @access public
  * @return json
  */ 
  public static function importOptionProducts() {
    $result = lC_Products_import_export_Admin::importOptionProducts($_GET['owizard'], $_GET['otype'], $_GET['obackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
}
?>