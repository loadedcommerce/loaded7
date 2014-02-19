<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-12-01 resultsonlyweb $
*/
global $lC_Vqmod;
require_once($lC_Vqmod->modCheck('includes/applications/products_import_export/classes/products_import_export.php'));

class lC_Products_import_export_Admin_rpc {
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
  * Get Option Groups data set
  *
  * @param $_GET['ofilter'] An string filter type
  * @param $_GET['ogformat'] An string export type
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
  * Get Option Variants data set
  *
  * @param $_GET['ofilter'] An string filter type
  * @param $_GET['ogformat'] An string export type
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
  * Get Options to Products data set
  *
  * @param $_GET['ofilter'] An string filter type
  * @param $_GET['ogformat'] An string export type
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
  * Import Products from Import File
  *
  * @param $_GET['pwizard'] An array of mapping wizard data for mapping columns
  * @param $_GET['ptype'] A string of the type of import
  * @param $_GET['pbackkup'] An boolean whether to backup the products tables first
  * @access public
  * @return json
  */ 
  public static function importProducts() {
    $result = lC_Products_import_export_Admin::importProducts($_GET['pfilename'], $_GET['pwizard'], $_GET['ptype'], $_GET['pbackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Import Categores from Import File
  *
  * @param $_GET['cwizard'] An array of mapping wizard data for mapping columns
  * @param $_GET['ctype'] A string of the type of import
  * @param $_GET['cbackkup'] An boolean whether to backup the categories tables first
  * @access public
  * @return json
  */ 
  public static function importCategories() {
    $result = lC_Products_import_export_Admin::importCategories($_GET['cfilename'], $_GET['cwizard'], $_GET['ctype'], $_GET['cbackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Import Option Groups from Import File
  *
  * @param $_GET['owizard'] An array of mapping wizard data for mapping columns
  * @param $_GET['otype'] A string of the type of import
  * @param $_GET['obackkup'] An boolean whether to backup the products tables first
  * @access public
  * @return json
  */ 
  public static function importOptionGroups() {
    $result = lC_Products_import_export_Admin::importOptionGroups($_GET['ogfilename'], $_GET['owizard'], $_GET['otype'], $_GET['obackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Import Option Variants from Import File
  *
  * @param $_GET['owizard'] An array of mapping wizard data for mapping columns
  * @param $_GET['otype'] A string of the type of import
  * @param $_GET['obackkup'] An boolean whether to backup the products tables first
  * @access public
  * @return json
  */ 
  public static function importOptionVariants() {
    $result = lC_Products_import_export_Admin::importOptionVariants($_GET['ovfilename'], $_GET['owizard'], $_GET['otype'], $_GET['obackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  
 /*
  * Import Option to Products from Import File
  *
  * @param $_GET['owizard'] An array of mapping wizard data for mapping columns
  * @param $_GET['otype'] A string of the type of import
  * @param $_GET['obackkup'] An boolean whether to backup the products tables first
  * @access public
  * @return json
  */ 
  public static function importOptionProducts() {
    $result = lC_Products_import_export_Admin::importOptionProducts($_GET['opfilename'], $_GET['owizard'], $_GET['otype'], $_GET['obackup']);
    if (isset($result['total']) && $result['total'] != null) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } else {
    }

    echo json_encode($result);
  }
  public static function fileUpload() {
    global $lC_Database, $lC_Vqmod, $_module;
     
    require_once($lC_Vqmod->modCheck('includes/classes/ajax_upload.php'));

    // list of valid extensions, ex. array("jpeg", "xml", "bmp")
    $allowedExtensions = array('gif', 'jpg', 'jpeg', 'png', 'txt', 'csv');
    
    // max file size in bytes
    $sizeLimit = 10 * 1024 * 1024;

    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
    
    $import_file = $uploader->handleUpload('../includes/work/products_import_export/imports/'); 

    if ( $import_file['exists'] == true ) {
      if ( isset($import_file['filename']) && $import_file['filename'] != null ) {
		    $success = true;
      }
    }

    $result = array('result' => 1,
                    'success' => $success,
                    'rpcStatus' => RPC_STATUS_SUCCESS,
					          'filename' => $import_file['filename']
                    );

    echo json_encode($result);
  }   
}
?>