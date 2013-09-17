<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  
  @function The lC_Products_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/products/classes/products.php'));
require_once($lC_Vqmod->modCheck('includes/classes/category_tree.php'));
require_once($lC_Vqmod->modCheck('includes/classes/image.php'));
require_once($lC_Vqmod->modCheck('../includes/classes/currencies.php'));

class lC_Products_Admin_rpc { 
 /*
  * Returns the templates modules layout datatable data for listings
  *
  * @param string $_GET['filter'] The category id 
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Products_Admin::getAll($_GET['cid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Returns the available product categories
  *
  * @param string $_GET['filter'] The category id 
  * @access public
  * @return json
  */
  public static function getCategoriesArray() {
    global $lC_CategoryTree;
    
    $result = lC_Products_Admin::getCategoriesArray($_GET['cid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }  
 /*
  * Returns the data used on the dialog forma
  *
  * @param string $_GET['cid'] The category id 
  * @access public
  * @return json
  */
  public static function getProductFormData() {
    $result = lC_Products_Admin::getProductFormData($_GET['pid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Return the data used on the preview dialog form
  *
  * @param integer $_GET['pid'] The product id
  * @access public
  * @return json
  */
  public static function getPreview() {
    $result = lC_Products_Admin::preview($_GET['pid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }     
 /*
  * Copy a product
  *
  * @param integer $_GET['pid'] The product id 
  * @param integer $_GET['new_category_id'] The new category id 
  * @param string $_GET['copy_as'] The product id 
  * @access public
  * @return json
  */
  public static function copyProduct() {
    $result = array();
    $copied = lC_Products_Admin::copy($_GET['pid'], $_GET['new_category_id'], $_GET['copy_as']);
    if ($copied) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
 /*
  * Batch copy products
  *
  * @param array $_GET['batch'] The product id 
  * @param integer $_GET['new_category_id'] The new category id 
  * @param string $_GET['copy_as'] The product id 
  * @access public
  * @return json
  */
  public static function batchCopy() {
    $result = array();
    $copied = lC_Products_Admin::batchCopy($_GET['batch'], $_GET['new_category_id'], $_GET['copy_as']);
    if ($copied) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
 /*
  * Delete a product
  *
  * @param integer $_GET['pid'] The product id to delete
  * @param array $_GET['categories'] The category id's to unlink from
  * @access public
  * @return json
  */
  public static function deleteProduct() {
    $result = array();
    $deleted = lC_Products_Admin::delete($_GET['pid'], $_GET['categories']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
 /*
  * Batch delete products
  *
  * @param array $_GET['batch'] The product id's to delete 
  * @access public
  * @return json
  */
  public static function batchDelete() {
    $result = array();
    $copied = lC_Products_Admin::batchDelete($_GET['batch']);
    if ($copied) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Check product permalink
  *
  * @param array $_GET['products_keyword'] The product permalink to validate 
  * @access public
  * @return json
  */
  public static function validatePermalink() {
    $data = str_replace('%5B', '[', $_GET);
    $data = str_replace('%5D', ']', $data);
    
    $validated = lC_Products_Admin::validatePermalink($data['products_keyword'], $data['iid'], $data['type']);

    echo json_encode($validated);
  }
  

  public static function getImages() {
    global $lC_Database, $_module;

    $lC_Image = new lC_Image_Admin();

    $result = array('entries' => array());

    $Qimages = $lC_Database->query('select id, image, default_flag from :table_products_images where products_id = :products_id order by sort_order');
    $Qimages->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qimages->bindInt(':products_id', $_GET[$_module]);
    $Qimages->execute();

    while ( $Qimages->next() ) {
      foreach ( $lC_Image->getGroups() as $group ) {
        $pass = true;

        if ( isset($_GET['filter']) && (($_GET['filter'] == 'originals') && ($group['id'] != '1')) ) {
          $pass = false;
        } elseif ( isset($_GET['filter']) && (($_GET['filter'] == 'others') && ($group['id'] == '1')) ) {
          $pass = false;
        }

        if ( $pass === true ) {
          $result['entries'][] = array($Qimages->valueInt('id'),
                                       $group['id'],
                                       $Qimages->value('image'),
                                       $group['code'],
                                       lc_href_link($lC_Image->getAddress($Qimages->value('image'), $group['code']), null, 'NONSSL', false, false, true),
                                       number_format(@filesize(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $group['code'] . '/' . $Qimages->value('image'))),
                                       $Qimages->valueInt('default_flag'),
                                       $lC_Image->getWidth($group['code']),
                                       $lC_Image->getHeight($group['code']));
        }
      }
    }

    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }

  public static function getLocalImages() {
    $lC_DirectoryListing = new lC_DirectoryListing('../images/products/_upload', true);
    $lC_DirectoryListing->setCheckExtension('gif');
    $lC_DirectoryListing->setCheckExtension('jpg');
    $lC_DirectoryListing->setCheckExtension('png');
    $lC_DirectoryListing->setIncludeDirectories(false);

    $result = array('entries' => array());

    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      $result['entries'][] = $file['name'];
    }

    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }

  public static function assignLocalImages() {
    global $lC_Database, $_module;

    $lC_Image = new lC_Image_Admin();

    if ( is_numeric($_GET[$_module]) && isset($_GET['files']) ) {
      $default_flag = 1;

      $Qcheck = $lC_Database->query('select id from :table_products_images where products_id = :products_id and default_flag = :default_flag limit 1');
      $Qcheck->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qcheck->bindInt(':products_id', $_GET[$_module]);
      $Qcheck->bindInt(':default_flag', 1);
      $Qcheck->execute();

      if ( $Qcheck->numberOfRows() === 1 ) {
        $default_flag = 0;
      }

      foreach ( $_GET['files'] as $file ) {
        $file = basename($file);

        if ( file_exists('../images/products/_upload/' . $file) ) {
          copy('../images/products/_upload/' . $file, '../images/products/originals/' . $file);
          @unlink('../images/products/_upload/' . $file);

          if ( is_numeric($_GET[$_module]) ) {
            $Qimage = $lC_Database->query('insert into :table_products_images (products_id, image, default_flag, sort_order, date_added) values (:products_id, :image, :default_flag, :sort_order, :date_added)');
            $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
            $Qimage->bindInt(':products_id', $_GET[$_module]);
            $Qimage->bindValue(':image', $file);
            $Qimage->bindInt(':default_flag', $default_flag);
            $Qimage->bindInt(':sort_order', 0);
            $Qimage->bindRaw(':date_added', 'now()');
            $Qimage->setLogging($_SESSION['module'], $_GET[$_module]);
            $Qimage->execute();

            foreach ( $lC_Image->getGroups() as $group ) {
              if ( $group['id'] != '1' ) {
                $lC_Image->resize($file, $group['id']);
              }
            }
          }
        }
      }
    }

    $result = array('result' => 1,
                    'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }

  public static function setDefaultImage() {
    $lC_Image = new lC_Image_Admin();

    if ( isset($_GET['image']) ) {
      $lC_Image->setAsDefault($_GET['image']);
    }

    $result = array('result' => 1,
                    'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }

  public static function deleteProductImage() {
    $lC_Image = new lC_Image_Admin();

    if ( isset($_GET['image']) ) {
      $lC_Image->delete($_GET['image']);
    }

    $result = array('result' => 1,
                    'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }

  public static function reorderImages() {
    $lC_Image = new lC_Image_Admin();

    if ( isset($_GET['image']) ) {
      $lC_Image->reorderImages($_GET['image']);
    }

    $result = array('result' => 1,
                    'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }

  public static function fileUpload() {
    global $lC_Database, $lC_Vqmod, $_module;

    $lC_Image = new lC_Image_Admin();

    if ( is_numeric($_GET[$_module]) ) {
     
      require_once($lC_Vqmod->modCheck('includes/classes/ajax_upload.php'));

      // list of valid extensions, ex. array("jpeg", "xml", "bmp")
      $allowedExtensions = array('gif', 'jpg', 'jpeg', 'png');
      // max file size in bytes
      $sizeLimit = 10 * 1024 * 1024;

      $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
      
      $products_image = $uploader->handleUpload('../images/products/originals/'); 

      if ( $products_image['exists'] == true ) {
        if ( isset($products_image['filename']) && $products_image['filename'] != null ) {
          $default_flag = 1;

          $Qcheck = $lC_Database->query('select id from :table_products_images where products_id = :products_id and default_flag = :default_flag limit 1');
          $Qcheck->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
          $Qcheck->bindInt(':products_id', $_GET[$_module]);
          $Qcheck->bindInt(':default_flag', 1);
          $Qcheck->execute();

          if ( $Qcheck->numberOfRows() === 1 ) {
            // is default image uploaded, remove the old default image first.
            if (isset($_GET['default']) && $_GET['default'] == '1') {
              $lC_Image->delete($Qcheck->value('id'));
            } else {
              $default_flag = 0;
            }
          }

          $Qimage = $lC_Database->query('insert into :table_products_images (products_id, image, default_flag, sort_order, date_added) values (:products_id, :image, :default_flag, :sort_order, :date_added)');
          $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
          $Qimage->bindInt(':products_id', $_GET[$_module]);
          $Qimage->bindValue(':image', $products_image['filename']);
          $Qimage->bindInt(':default_flag', $default_flag);
          $Qimage->bindInt(':sort_order', 0);
          $Qimage->bindRaw(':date_added', 'now()');
          $Qimage->setLogging($_SESSION['module'], $_GET[$_module]);
          $Qimage->execute();

          foreach ( $lC_Image->getGroups() as $group ) {
            if ( $group['id'] != '1' ) {
              $lC_Image->resize($products_image['filename'], $group['id']);
            }
          }
        }
      }
    }

    $result = array('result' => 1,
                    'success' => true,
                    'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }
  
 /*
  * Return the variant group data for use on simple options modal
  *
  * @access public
  * @return json
  */
  public static function getSimpleOptionData() {
    $result = lC_Products_Admin::getSimpleOptionData();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Return the variant entry data for use on simple options modal
  *
  * @access public
  * @return json
  */
  public static function getSimpleOptionEntryData() {
    $result = lC_Products_Admin::getSimpleOptionEntryData($_GET);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }     
  
}
?>
