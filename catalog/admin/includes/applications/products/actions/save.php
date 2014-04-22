<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: save.php v1.0 2013-08-08 datazen $
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
      
      $error = false;
      $has_variants = ((isset($_POST['variants']))) ? true : false;

      $data = array('id' => (isset($_POST['products_id'])) ? $_POST['products_id'] : 0,
                    'quantity' => (isset($_POST['products_quantity']) && $has_variants === false) ? $_POST['products_quantity'] : 0,
                    'cost' => (is_numeric($_POST['products_cost'])) ? $_POST['products_cost'] : 0,
                    'price' => number_format($_POST['products_price'], DECIMAL_PLACES),
                    'msrp' => (is_numeric($_POST['products_msrp'])) ? $_POST['products_msrp'] : 0,
                    'weight' => $_POST['products_weight'],
                    'weight_class' => (isset($_POST['products_weight_class'])) ? $_POST['products_weight_class'] : '',
                    'status' => (isset($_POST['products_status']) && $_POST['products_status'] == 'on') ? true : false,
                    'model' => (isset($_POST['products_model'])) ? $_POST['products_model'] : '',
                    'sku' => (isset($_POST['products_sku'])) ? $_POST['products_sku'] : '',
                    'tax_class_id' => (isset($_POST['products_tax_class_id'])) ? $_POST['products_tax_class_id'] : 0,
                    'products_name' => $_POST['products_name'],
                    'products_description' => $_POST['products_description'],
                    'products_keyword' => $_POST['products_keyword'],
                    'products_tags' => $_POST['products_tags'],
                    'products_url' => $_POST['products_url'],
                    'has_children' => $has_variants);

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

      // sub-products
      if (is_array($_POST['sub_products_name']) && count($_POST['sub_products_name']) > 1) {
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
      
      // qpb
      if (is_array($_POST['products_qty_break_point']) && $_POST['products_qty_break_point'][1] != NULL) $data['products_qty_break_point'] = $_POST['products_qty_break_point'];
      if (is_array($_POST['products_qty_break_price']) && $_POST['products_qty_break_price'][1] != NULL) $data['products_qty_break_price'] = $_POST['products_qty_break_price'];
      
      // multi SKU options
      if (isset($_POST['variants'])) $data['variants'] = $_POST['variants'];
      
      // access levels
      if (isset($_POST['access_levels'])) $data['access_levels'] = $_POST['access_levels'];

      if ( $error === false ) {
        // the line below is used as a hook match point - do not not modify or remove
        $id = lC_Products_Admin::save((isset($_GET[$this->_module]) && is_numeric($_GET[$this->_module]) ? $_GET[$this->_module] : null), $data);
        
        if ( is_numeric($id) ) {
          if (empty($_POST['save_close'])) {
            lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $id . '&action=save&cID=' . $_GET['cID']));
          } else {
            lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '&cID=' . $_GET['cID']));
          }
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