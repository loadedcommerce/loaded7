<?php
/*
  $Id: save.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Application_Products_Actions_save extends lC_Application_Products {
    public function __construct() {
      global $lC_Language, $lC_MessageStack;

      parent::__construct();
    
      $this->_page_contents = 'edit.php';
      
      if ( (lc_empty(CFG_APP_IMAGEMAGICK_CONVERT) || !@file_exists(CFG_APP_IMAGEMAGICK_CONVERT)) && !lC_Image_Admin::hasGDSupport() ) {
        $_SESSION['error'] = true;                                                                                            
        $_SESSION['errmsg'] = $lC_Language->get('ms_warning_image_processor_not_available');
      }

      if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
        
        //echo "<pre>";
        //print_r($_POST);
        //echo "</pre>";
        //die('before data');
                  
        $error = false;

        $has_variants = (isset($_POST['has_variants']) && $_POST['has_variants'] == 'on') ? true : false;   

        $data = array('id' => (isset($_POST['products_id']) && $has_variants === false) ? $_POST['products_id'] : 0,
                      'quantity' => (isset($_POST['products_quantity']) && $has_variants === false) ? $_POST['products_quantity'] : 0,
                      'cost' => (is_numeric($_POST['products_cost']) && $has_variants === false) ? $_POST['products_cost'] : 0,
                      'price' => (is_numeric($_POST['products_price']) && $has_variants === false) ? $_POST['products_price'] : 0,
                      'msrp' => (is_numeric($_POST['products_msrp']) && $has_variants === false) ? $_POST['products_msrp'] : 0,
                      'weight' => (isset($_POST['products_weight']) && $has_variants === false) ? $_POST['products_weight'] : 0,
                      'weight_class' => (isset($_POST['products_weight_class']) && $has_variants === false) ? $_POST['products_weight_class'] : '',
                      'status' => (isset($_POST['products_status']) && $_POST['products_status'] == 'on' && $has_variants === false) ? true : false,
                      'model' => (isset($_POST['products_model']) && $has_variants === false) ? $_POST['products_model'] : '',
                      'sku' => (isset($_POST['products_sku']) && $has_variants === false) ? $_POST['products_sku'] : '',
                      'tax_class_id' => (isset($_POST['products_tax_class_id']) && $has_variants === false) ? $_POST['products_tax_class_id'] : 0,
                      'products_name' => $_POST['products_name'],
                      'products_description' => $_POST['products_description'],
                      'products_keyword' => $_POST['products_keyword'],
                      'products_tags' => $_POST['products_tags'],
                      'products_url' => $_POST['products_url']);

        if ( isset($_POST['attributes']) ) $data['attributes'] = $_POST['attributes'];
        if ( isset($_POST['categories']) ) $data['categories'] = $_POST['categories'];
        if ( isset($_POST['localimages']) ) $data['localimages'] = $_POST['localimages'];

        // simple options
        if ( isset($_POST['simple_options_group_name']) ) $data['simple_options_group_name'] = $_POST['simple_options_group_name'];
        if ( isset($_POST['simple_options_group_type']) ) $data['simple_options_group_type'] = $_POST['simple_options_group_type'];
        if ( isset($_POST['simple_options_group_sort_order']) ) $data['simple_options_group_sort_order'] = $_POST['simple_options_group_sort_order'];
        if ( isset($_POST['simple_options_group_status']) ) $data['simple_options_group_status'] = $_POST['simple_options_group_status'];
        if ( isset($_POST['simple_options_entry']) ) $data['simple_options_entry'] = $_POST['simple_options_entry'];
        if ( isset($_POST['simple_options_entry_price_modifier']) ) $data['simple_options_entry_price_modifier'] = $_POST['simple_options_entry_price_modifier'];
        
        // specials
        if ( isset($_POST['specials_pricing_switch']) && $_POST['specials_pricing_switch'] == 'on' ) {
          $data['specials_pricing_switch'] = 1;
          if ( isset($_POST['products_special_pricing_enable1']) ) $data['products_special_pricing_enable1'] = ($_POST['products_special_pricing_enable1'] == 'on' ? 1 : 0);
          if ( isset($_POST['products_special_price'][1]) ) $data['products_special_price1'] = $_POST['products_special_price'][1];
          if ( isset($_POST['products_special_start_date'][1]) ) $data['products_special_start_date1'] = $_POST['products_special_start_date'][1];
          if ( isset($_POST['products_special_expires_date'][1]) ) $data['products_special_expires_date1'] = $_POST['products_special_expires_date'][1];
        }

        // multi SKU sub-products
        if (isset($_POST['sub_products_name']) && $_POST['sub_products_name'] != NULL) {
          $data['has_subproducts'] = '1';
          $data['sub_products_name'] = $_POST['sub_products_name'];
          if (isset($_POST['sub_products_default']) && $_POST['sub_products_default'] != NULL) $data['sub_products_default'] = $_POST['sub_products_default'];
          if (isset($_POST['sub_products_status']) && $_POST['sub_products_status'] != NULL) $data['sub_products_status'] = $_POST['sub_products_status'];
          if (isset($_POST['sub_products_weight']) && $_POST['sub_products_weight'] != NULL) $data['sub_products_weight'] = $_POST['sub_products_weight'];
          if (isset($_POST['sub_products_sku']) && $_POST['sub_products_sku'] != NULL) $data['sub_products_sku'] = $_POST['sub_products_sku'];
          if (isset($_POST['sub_products_qoh']) && $_POST['sub_products_qoh'] != NULL) $data['sub_products_qoh'] = $_POST['sub_products_qoh'];
          if (isset($_POST['sub_products_id']) && $_POST['sub_products_id'] != NULL) $data['sub_products_id'] = $_POST['sub_products_id'];
          if (isset($_POST['sub_products_cost']) && $_POST['sub_products_cost'] != NULL) $data['sub_products_cost'] = $_POST['sub_products_cost'];
          if (isset($_POST['sub_products_price']) && $_POST['sub_products_price'] != NULL) $data['sub_products_price'] = $_POST['sub_products_price'];
        }                                         
        
        // multi SKU combo
        if ($has_variants === true) {
          if ( isset($_POST['variants_status']) ) {
            $data['variants_status'] = $_POST['variants_status'];
          }

          if ( isset($_POST['variants_cost']) ) {
            $data['variants_cost'] = $_POST['variants_cost'];
          }

          if ( isset($_POST['variants_price']) ) {
            $data['variants_price'] = $_POST['variants_price'];
          }

          if ( isset($_POST['variants_msrp']) ) {
            $data['variants_msrp'] = $_POST['variants_msrp'];
          }

          if ( isset($_POST['variants_model']) ) {
            $data['variants_model'] = $_POST['variants_model'];
          }

          if ( isset($_POST['variants_sku']) ) {
            $data['variants_sku'] = $_POST['variants_sku'];
          }

          if ( isset($_POST['variants_tax_class_id']) ) {
            $data['variants_tax_class_id'] = $_POST['variants_tax_class_id'];
          }

          if ( isset($_POST['variants_quantity']) ) {
            $data['variants_quantity'] = $_POST['variants_quantity'];
          }

          if ( isset($_POST['variants_combo']) ) {
            $data['variants_combo'] = $_POST['variants_combo'];
          }

          if ( isset($_POST['variants_combo_db']) ) {
            $data['variants_combo_db'] = $_POST['variants_combo_db'];
          }

          if ( isset($_POST['variants_weight']) ) {
            $data['variants_weight'] = $_POST['variants_weight'];
          }

          if ( isset($_POST['variants_weight_class']) ) {
            $data['variants_weight_class'] = $_POST['variants_weight_class'];
          }

          if ( isset($_POST['variants_default_combo']) ) {
            $data['variants_default_combo'] = $_POST['variants_default_combo'];
          }
        } 
          
        if ( isset($_POST['price_breaks']) ) {
          $data['price_breaks'] = $_POST['price_breaks'];         
        }
        
//echo "<pre>";
//print_r($data);
//echo "</pre>";
//die('end run');

        if ( $error === false ) {
          if ( lC_Products_Admin::save((isset($_GET[$this->_module]) && is_numeric($_GET[$this->_module]) ? $_GET[$this->_module] : null), $data) ) {
          } else {
            $_SESSION['error'] = true;                                                                                            
            $_SESSION['errmsg'] = $lC_Language->get('ms_error_action_not_performed');
          }

          lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '&cID=' . $_GET['cID']));
        }
      }
    }
  }
?>