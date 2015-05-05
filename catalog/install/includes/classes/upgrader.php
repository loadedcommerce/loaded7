<?php
/**
  @package    catalog::install::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: upgrader.php v1.0 2013-08-08 datazen $
*/

class UpgraderFactory {
  public static function create($type) {
    switch ($type) {
      case 'R': // remote 
          return new lC_RemoteUpgrader();
        break;
      default:
          return new lC_LocalUpgrader();
        break;
    }
  }
}

abstract class lC_Upgrader {
  /*
  *  function name : isSourceAccessible()
  *
  *  description : check that there is access to data source
  *
  *  returns : true or false  
  *
  */
  protected $_msg, $_zip_errmsg;

  protected $_source_data; // array of loaded source data 
  
  protected $_data_mapping; // array matching source data table fields with loaded7 data table fields 
  
  protected $_languages_id_default = 1; 
  
  public function __construct() {
  }

  public function printDataMap() {
    print_r($this->_data_mapping);
  }
  
  public function displayMessage() { 
    return $this->_msg; 
  }

  /* creates a compressed zip file */
  public function extract_zip($zip_file, $destination) {
    global $lC_Language;
  
    $zip = new ZipArchive;
    $res = $zip->open($zip_file);
    if ($res === TRUE) {
      $zip->extractTo($destination);
      $zip->close();
      return true;
    } else {
      $this->_zip_errmsg = $lC_Language->get('upgrade_step4_zipextracterror');
      return false;
    }    
  }
    
  /* creates a compressed zip file */
  public function create_zip($files = array(), $destination = '', $overwrite = false) {
    global $lC_Language;
    
    //if the zip file already exists and overwrite is false, return false
    if (file_exists($destination) && !$overwrite) { 
      $this->_zip_errmsg = $lC_Language->get('upgrade_step4_zipoverrideerror');
      return false; 
    }
    //vars
    $valid_files = array();
    //if files were passed in...
    if (is_array($files)) {
      //cycle through each file
      foreach ($files as $file) {
        //make sure the file exists
        if (file_exists($file)) {
          $valid_files[] = $file;
        }
      }
    }
  
    //if we have good files...
    if (count($valid_files)) {
      //create the archive
      $zip = new ZipArchive();
      if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
        $this->_zip_errmsg = $lC_Language->get('upgrade_step4_zipopenerror');
        return false;
      }
      //add the files
      foreach ($valid_files as $file) {
        $zip->addFile($file, end(explode('/', $file)));
      }
      //debug
      //echo 'The zip archive contains ', $zip->numFiles,' files with a status of ', $zip->status;
      
      //close the zip -- done!
      $zip->close();
      
      //check to make sure the file exists
      return file_exists($destination);
    } else {
      return false;
    }
  }

  public function chmod_r($Path) {
    $dp = opendir($Path);
    while ($File = readdir($dp)) {
      if ($File != "." AND $File != "..") {
        if (is_dir($File)){
          chmod($File, 0777);
          $this->chmod_r($Path . "/" . $File);
        } else {
          chmod($Path . "/" . $File, 0777);
        }
      }
    }
    closedir($dp);
  }
  
  public function rrmdir($dir) {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (filetype($dir . "/" . $object) == "dir") { 
            $this->rrmdir($dir . "/" . $object); 
          } else {
            unlink($dir . "/" . $object);
          }
        }
      }
      reset($objects);
      rmdir($dir);
    }
  }   
}

class lC_LocalUpgrader extends lC_Upgrader {

  protected $_sDB ; // source database connection parameters
  private $_tDB ; // target database connection parameters
    
  public function __construct() {  
    $this->_data_mapping = array(
                                   'address_book'                   => array(
                                                                               'address_book_id'      => 'address_book_id'
                                                                             , 'customers_id'         => 'customers_id'
                                                                             , 'entry_gender'         => 'entry_gender'
                                                                             , 'entry_company'        => 'entry_company'
                                                                             , 'entry_firstname'      => 'entry_firstname'
                                                                             , 'entry_lastname'       => 'entry_lastname'
                                                                             , 'entry_street_address' => 'entry_street_address'
                                                                             , 'entry_suburb'         => 'entry_suburb'
                                                                             , 'entry_postcode'       => 'entry_postcode'
                                                                             , 'entry_city'           => 'entry_city'
                                                                             , 'entry_state'          => 'entry_state'
                                                                             , 'entry_country_id'     => 'entry_country_id'
                                                                             , 'entry_zone_id'        => 'entry_zone_id'
                                                                             , 'entry_telephone'      => 'entry_telephone'
                                                                             , 'entry_fax'            => 'entry_fax'
                                                                              )
                                 , 'administrators'                 => array(
                                                                               'id'              => 'admin_id'
                                                                             , 'user_name'       => 'admin_email_address'
                                                                             , 'user_password'   => 'admin_password'
                                                                             , 'first_name'      => 'admin_firstname'
                                                                             , 'last_name'       => 'admin_lastname'
                                                                             , 'image'           => 'image'
                                                                             , 'access_group_id' => 'admin_groups_id'
                                                                              )
                                 , 'administrators_groups'          => array(
                                                                               'id'            => 'admin_groups_id'
                                                                             , 'name'          => 'admin_groups_name'
                                                                             , 'date_added'    => 'date_added'
                                                                             , 'last_modified' => 'last_modified'
                                                                              )
                                 , 'banners'                        => array(
                                                                               'banners_id'          => 'banners_id'
                                                                             , 'banners_title'       => 'banners_title'
                                                                             , 'banners_url'         => 'banners_url'
                                                                             , 'banners_target'      => 'banners_target'
                                                                             , 'banners_image'       => 'banners_image'
                                                                             , 'banners_group'       => 'banners_group'
                                                                             , 'banners_html_text'   => 'banners_html_text'
                                                                             , 'expires_impressions' => 'expires_impressions'
                                                                             , 'expires_date'        => 'expires_date'
                                                                             , 'date_scheduled'      => 'date_scheduled'
                                                                             , 'date_added'          => 'date_added'
                                                                             , 'date_status_change'  => 'date_status_change'
                                                                             , 'status'              => 'status'
                                                                              )
                                 , 'banners_history'                => array(
                                                                               'banners_history_id'   => 'banners_history_id'
                                                                             , 'banners_id'           => 'banners_id'
                                                                             , 'banners_shown'        => 'banners_shown'
                                                                             , 'banners_clicked'      => 'banners_clicked'
                                                                             , 'banners_history_date' => 'banners_history_date'
                                                                              )
                                 , 'categories'                     => array(
                                                                               'categories_id'             => 'categories_id'
                                                                             , 'categories_image'          => 'categories_image'
                                                                             , 'parent_id'                 => 'parent_id'
                                                                             , 'sort_order'                => 'sort_order'
                                                                             , 'categories_mode'           => 'categories_mode'
                                                                             , 'categories_link_target'    => 'categories_link_target'
                                                                             , 'categories_custom_url'     => 'categories_custom_url'
                                                                             , 'categories_status'         => 'categories_status'
                                                                             , 'categories_visibility_nav' => 'categories_visibility_nav'
                                                                             , 'categories_visibility_box' => 'categories_visibility_box'
                                                                             , 'date_added'                => 'date_added'
                                                                             , 'last_modified'             => 'last_modified'
                                                                              )
                                 , 'categories_desc'                => array(
                                                                               'categories_id'          => 'categories_id'
                                                                             , 'language_id'            => 'language_id'
                                                                             , 'categories_name'        => 'categories_name'
                                                                             , 'categories_menu_name'   => 'categories_menu_name'
                                                                             , 'categories_blurb'       => 'categories_blurb'
                                                                             , 'categories_description' => 'categories_description'
                                                                             , 'categories_keyword'     => 'categories_keywords'
                                                                             , 'categories_tags'        => 'categories_keywords_tags'
                                                                              )
                                 , 'page_categories'                => array(
                                                                               'categories_id'             => 'categories_id'
                                                                             , 'categories_image'          => 'categories_image'
                                                                             , 'parent_id'                 => 'categories_parent_id'
                                                                             , 'sort_order'                => 'categories_sort_order'
                                                                             , 'categories_mode'           => 'categories_mode'
                                                                             , 'categories_link_target'    => 'categories_url_override_target'
                                                                             , 'categories_custom_url'     => 'categories_url_override'
                                                                             , 'categories_status'         => 'categories_status'
                                                                             , 'categories_visibility_nav' => 'categories_in_menu'
                                                                             , 'categories_visibility_box' => 'pages_in_menu'
                                                                             , 'date_added'                => 'categories_date_added'
                                                                             , 'last_modified'             => 'categories_date_modified'
                                                                             , 'language_id'               => 'language_id'
                                                                             , 'categories_name'           => 'categories_name'
                                                                             , 'categories_menu_name'      => 'categories_menu_name'
                                                                             , 'categories_blurb'          => 'categories_blurb'
                                                                             , 'categories_description'    => 'categories_description'
                                                                             , 'categories_keyword'        => 'categories_keyword'
                                                                             , 'categories_tags'           => 'categories_meta_keywords'
                                                                              )
                                 , 'page_pages'                     => array(
                                                                               'categories_id'             => 'pages_id'
                                                                             , 'categories_image'          => 'pages_image'
                                                                             , 'parent_id'                 => 'categories_id'
                                                                             , 'sort_order'                => 'pages_sort_order'
                                                                             , 'categories_mode'           => 'categories_mode'
                                                                             , 'categories_link_target'    => 'categories_link_target'
                                                                             , 'categories_custom_url'     => 'categories_custom_url'
                                                                             , 'categories_status'         => 'pages_status'
                                                                             , 'categories_visibility_nav' => 'categories_visibility_nav'
                                                                             , 'categories_visibility_box' => 'pages_in_menu'
                                                                             , 'date_added'                => 'pages_date_added'
                                                                             , 'last_modified'             => 'pages_date_modified'
                                                                             , 'language_id'               => 'language_id'
                                                                             , 'categories_name'           => 'pages_title'
                                                                             , 'categories_menu_name'      => 'pages_menu_name'
                                                                             , 'categories_blurb'          => 'pages_blurb'
                                                                             , 'categories_description'    => 'pages_body'
                                                                             , 'categories_keyword'        => 'categories_name'
                                                                             , 'categories_tags'           => 'pages_meta_keywords'
                                                                              )
                                 , 'configuration'                  => array(
                                                                               'configuration_id'          => 'configuration_id'
                                                                             , 'configuration_title'       => 'configuration_title'
                                                                             , 'configuration_key'         => 'configuration_key'
                                                                             , 'configuration_value'       => 'configuration_value'
                                                                             , 'configuration_description' => 'configuration_description'
                                                                             , 'configuration_group_id'    => 'configuration_group_id'
                                                                             , 'sort_order'                => 'sort_order'
                                                                             , 'last_modified'             => 'last_modified'
                                                                             , 'date_added'                => 'date_added'
                                                                             , 'use_function'              => 'use_function'
                                                                             , 'set_function'              => 'set_function'
                                                                              )                            
                                 , 'configuration_group'            => array(
                                                                               'configuration_group_id'          => 'configuration_group_id'
                                                                             , 'configuration_group_title'       => 'configuration_group_title'
                                                                             , 'configuration_group_description' => 'configuration_group_description'
                                                                             , 'sort_order'                      => 'sort_order'
                                                                             , 'visible'                         => 'visible'
                                                                              )
                                 , 'coupons'                        => array(
                                                                               'coupons_id'             => 'coupon_id'
                                                                             , 'type'                   => 'coupon_type'
                                                                             , 'mode'                   => 'mode'
                                                                             , 'code'                   => 'coupon_code'
                                                                             , 'reward'                 => 'coupon_amount'
                                                                             , 'purchase_over'          => 'coupon_minimum_order'
                                                                             , 'start_date'             => 'coupon_start_date'
                                                                             , 'expires_date'           => 'coupon_expire_date'
                                                                             , 'uses_per_coupon'        => 'uses_per_coupon'
                                                                             , 'uses_per_customer'      => 'uses_per_user'
                                                                             , 'restrict_to_products'   => 'restrict_to_products'
                                                                             , 'restrict_to_categories' => 'restrict_to_categories'
                                                                             , 'restrict_to_customers'  => 'restrict_to_customers'
                                                                             , 'status'                 => 'coupon_active'
                                                                             , 'date_created'           => 'date_created'
                                                                             , 'date_modified'          => 'date_modified'
                                                                             , 'sale_exclude'           => 'coupon_sale_exclude'
                                                                             , 'notes'                  => 'notes'
                                                                              )
                                 , 'coupons_description'            => array(
                                                                               'coupons_id'  => 'coupon_id'
                                                                             , 'language_id' => 'language_id'
                                                                             , 'name'        => 'coupon_description'
                                                                              )
                                 , 'coupons_redeemed'               => array(
                                                                               'id'           => 'unique_id'
                                                                             , 'coupons_id'   => 'coupon_id'
                                                                             , 'customers_id' => 'customer_id'
                                                                             , 'redeem_date'  => 'redeem_date'
                                                                             , 'redeem_ip'    => 'redeem_ip'
                                                                             , 'order_id'     => 'order_id'
                                                                              )
                                 , 'currencies'                     => array(
                                                                               'currencies_id'  => 'currencies_id'
                                                                             , 'title'          => 'title'
                                                                             , 'code'           => 'code'
                                                                             , 'symbol_left'    => 'symbol_left'
                                                                             , 'symbol_right'   => 'symbol_right'
                                                                             , 'decimal_places' => 'decimal_places'
                                                                             , 'value'          => 'value'
                                                                             , 'last_updated'   => 'last_updated'
                                                                              )
                                 , 'customers'                      => array(
                                                                               'customers_id'                 => 'customers_id'
                                                                             , 'customers_group_id'           => 'customers_group_id'
                                                                             , 'customers_gender'             => 'customers_gender'
                                                                             , 'customers_firstname'          => 'customers_firstname'
                                                                             , 'customers_lastname'           => 'customers_lastname'
                                                                             , 'customers_dob'                => 'customers_dob'
                                                                             , 'customers_email_address'      => 'customers_email_address'
                                                                             , 'customers_default_address_id' => 'customers_default_address_id'
                                                                             , 'customers_telephone'          => 'customers_telephone'
                                                                             , 'customers_fax'                => 'customers_fax'
                                                                             , 'customers_password'           => 'customers_password'
                                                                             , 'customers_newsletter'         => 'customers_newsletter'
                                                                             , 'customers_status'             => 'customers_status'
                                                                             , 'customers_ip_address'         => 'customers_ip_address'
                                                                             , 'date_last_logon'              => 'customers_info_date_of_last_logon'
                                                                             , 'number_of_logons'             => 'customers_info_number_of_logons'
                                                                             , 'date_account_created'         => 'customers_info_date_account_created'
                                                                             , 'date_account_last_modified'   => 'customers_info_date_account_last_modified'
                                                                             , 'global_product_notifications' => 'global_product_notifications'
                                                                              )
                                 , 'customers_groups'               => array(
                                                                               'customers_group_id'   => 'customers_group_id'
                                                                             , 'language_id'          => 'language_id'
                                                                             , 'customers_group_name' => 'customers_group_name'
                                                                              )
                                 , 'manufacturers'                  => array(
                                                                               'manufacturers_id'    => 'manufacturers_id'
                                                                             , 'manufacturers_name'  => 'manufacturers_name'
                                                                             , 'manufacturers_image' => 'manufacturers_image'
                                                                             , 'date_added'          => 'date_added'
                                                                             , 'last_modified'       => 'last_modified'
                                                                              )
                                 , 'manufacturers_info'             => array(
                                                                               'manufacturers_id'  => 'manufacturers_id'
                                                                             , 'languages_id'      => 'languages_id'
                                                                             , 'manufacturers_url' => 'manufacturers_url'
                                                                             , 'url_clicked'       => 'url_clicked'
                                                                             , 'date_last_click'   => 'date_last_click'
                                                                              )
                                 , 'newsletters'                    => array(
                                                                               'newsletters_id' => 'newsletters_id'
                                                                             , 'title'          => 'title'
                                                                             , 'content'        => 'content'
                                                                             , 'module'         => 'module'
                                                                             , 'date_added'     => 'date_added'
                                                                             , 'date_sent'      => 'date_sent'
                                                                             , 'status'         => 'status'
                                                                             , 'locked'         => 'locked'
                                                                              )
                                 , 'orders'                         => array(
                                                                               'orders_id'                => 'orders_id'
                                                                             , 'customers_id'             => 'customers_id'
                                                                             , 'customers_name'           => 'customers_name'
                                                                             , 'customers_company'        => 'customers_company'
                                                                             , 'customers_street_address' => 'customers_street_address'
                                                                             , 'customers_suburb'         => 'customers_suburb'
                                                                             , 'customers_city'           => 'customers_city'
                                                                             , 'customers_postcode'       => 'customers_postcode'
                                                                             , 'customers_state'          => 'customers_state'
                                                                             , 'customers_state_code'     => 'customers_state_code'
                                                                             , 'customers_country'        => 'customers_country'
                                                                             , 'customers_country_iso2'   => 'customers_country_iso2'
                                                                             , 'customers_country_iso3'   => 'customers_country_iso3' 
                                                                             , 'customers_telephone'      => 'customers_telephone'
                                                                             , 'customers_email_address'  => 'customers_email_address'
                                                                             , 'customers_address_format' => 'customers_address_format_id'
                                                                             , 'customers_ip_address'     => 'ipaddy'
                                                                             , 'delivery_name'            => 'delivery_name'
                                                                             , 'delivery_company'         => 'delivery_company'
                                                                             , 'delivery_street_address'  => 'delivery_street_address'
                                                                             , 'delivery_suburb'          => 'delivery_suburb'
                                                                             , 'delivery_city'            => 'delivery_city'
                                                                             , 'delivery_postcode'        => 'delivery_postcode'
                                                                             , 'delivery_state'           => 'delivery_state'
                                                                             , 'delivery_state_code'      => 'delivery_state_code'
                                                                             , 'delivery_country'         => 'delivery_country'
                                                                             , 'delivery_country_iso2'    => 'delivery_country_iso2'
                                                                             , 'delivery_country_iso3'    => 'delivery_country_iso3'
                                                                             , 'delivery_address_format'  => 'delivery_address_format_id'
                                                                             , 'billing_name'             => 'billing_name'
                                                                             , 'billing_company'          => 'billing_company'
                                                                             , 'billing_street_address'   => 'billing_street_address'
                                                                             , 'billing_suburb'           => 'billing_suburb'
                                                                             , 'billing_city'             => 'billing_city'
                                                                             , 'billing_postcode'         => 'billing_postcode'
                                                                             , 'billing_state'            => 'billing_state'
                                                                             , 'billing_state_code'       => 'billing_state_code'
                                                                             , 'billing_country'          => 'billing_country'
                                                                             , 'billing_country_iso2'     => 'billing_country_iso2'
                                                                             , 'billing_country_iso3'     => 'billing_country_iso3'
                                                                             , 'billing_address_format'   => 'billing_address_format_id'
                                                                             , 'payment_method'           => 'payment_method'
                                                                             , 'payment_module'           => 'payment_info'
                                                                             , 'last_modified'            => 'last_modified'
                                                                             , 'date_purchased'           => 'date_purchased'
                                                                             , 'orders_status'            => 'orders_status'
                                                                             , 'orders_date_finished'     => 'orders_date_finished'
                                                                             , 'currency'                 => 'currency'
                                                                             , 'currency_value'           => 'currency_value'  
                                                                              )
                                 , 'orders_products'                => array(
                                                                               'orders_products_id'                => 'orders_products_id'
                                                                             , 'orders_id'                         => 'orders_id'
                                                                             , 'products_id'                       => 'products_id'
                                                                             , 'products_model'                    => 'products_model'
                                                                             , 'products_name'                     => 'products_name'
                                                                             , 'products_price'                    => 'products_price'
                                                                             , 'products_tax'                      => 'products_tax'
                                                                             , 'products_quantity'                 => 'products_quantity'
                                                                             , 'products_simple_options_meta_data' =>  'products_simple_options_meta_data'
                                                                              )
                                 , 'orders_products_download'       => array(
                                                                               'orders_products_download_id' => 'orders_products_download_id'
                                                                             , 'orders_id'                   => 'orders_id'
                                                                             , 'orders_products_id'          => 'orders_products_id'
                                                                             , 'orders_products_filename'    => 'orders_products_filename'
                                                                             , 'download_maxdays'            => 'download_maxdays'
                                                                             , 'download_count'              => 'download_count'
                                                                              )
                                 , 'orders_products_variants'       => array(
                                                                               'id'                 => 'id'
                                                                             , 'orders_id'          => 'orders_id'
                                                                             , 'orders_products_id' => 'orders_products_id'
                                                                             , 'group_title'        => 'group_title'
                                                                             , 'value_title'        => 'value_title'
                                                                              )
                                 , 'orders_status'                  => array(
                                                                               'orders_status_id'   => 'orders_status_id'
                                                                             , 'language_id'        => 'language_id'
                                                                             , 'orders_status_name' => 'orders_status_name'
                                                                              )
                                 , 'orders_status_history'          => array(
                                                                               'orders_status_history_id' => 'orders_status_history_id'
                                                                             , 'orders_id'                => 'orders_id'
                                                                             , 'orders_status_id'         => 'orders_status_id'
                                                                             , 'date_added'               => 'date_added'
                                                                             , 'customer_notified'        => 'customer_notified'
                                                                             , 'comments'                 => 'comments'
                                                                              )
                                 , 'orders_total'                   => array(
                                                                               'orders_total_id' => 'orders_total_id'
                                                                             , 'orders_id'       => 'orders_id'
                                                                             , 'title'           => 'title'
                                                                             , 'text'            => 'text'
                                                                             , 'value'           => 'value'
                                                                             , 'class'           => 'class'
                                                                             , 'sort_order'      => 'sort_order'
                                                                              )
                                 , 'permalink'                      => array(
                                                                               'permalink_id' => 'permalink_id'
                                                                             , 'item_id'      => 'products_id'
                                                                             , 'language_id'  => 'language_id'
                                                                             , 'type'         => 'type'
                                                                             , 'query'        => 'query'
                                                                             , 'permalink'    => 'products_name'
                                                                              )
                                 , 'products'                       => array(
                                                                               'products_id'            => 'products_id'
                                                                             , 'parent_id'              => 'products_parent_id'
                                                                             , 'products_quantity'      => 'products_quantity'
                                                                             , 'products_price'         => 'products_price'
                                                                             , 'products_cost'          => 'products_cost'
                                                                             , 'products_msrp'          => 'products_msrp'
                                                                             , 'products_model'         => 'products_model'
                                                                             , 'products_sku'           => 'products_sku'
                                                                             , 'products_date_added'    => 'products_date_added'
                                                                             , 'products_last_modified' => 'products_last_modified'
                                                                             , 'products_weight'        => 'products_weight'
                                                                             , 'products_weight_class'  => 'products_weight_class'
                                                                             , 'products_status'        => 'products_status'
                                                                             , 'products_tax_class_id'  => 'products_tax_class_id'
                                                                             , 'manufacturers_id'       => 'manufacturers_id'
                                                                             , 'products_ordered'       => 'products_ordered'
                                                                             , 'has_children'           => 'has_children'
                                                                              )                                                        
                                 , 'products_desc'                  => array(
                                                                               'products_id'            => 'products_id'
                                                                             , 'language_id'            => 'language_id'
                                                                             , 'products_name'          => 'products_name'
                                                                             , 'products_description'   => 'products_description'
                                                                             , 'products_keyword'       => 'products_keyword'
                                                                             , 'products_tags'          => 'products_tags'
                                                                             , 'products_meta_title'    => 'products_meta_title'
                                                                             , 'products_meta_keywords' => 'products_meta_keywords'
                                                                             , 'products_url'           => 'products_url'
                                                                             , 'products_viewed'        => 'products_viewed'
                                                                              )
                                 , 'products_images'                => array(
                                                                               'id'           => 'id'
                                                                             , 'products_id'  => 'products_id'
                                                                             , 'image'        => 'image'
                                                                             , 'default_flag' => 'default_flag'
                                                                             , 'sort_order'   => 'sort_order'
                                                                             , 'date_added'   => 'date_added'
                                                                              )
                                 , 'products_notif'                 => array(
                                                                               'products_id'  => 'products_id'
                                                                             , 'customers_id' => 'customers_id'
                                                                             , 'date_added'   => 'date_added'
                                                                              )
                                 , 'products_simple_options'        => array(
                                                                               'id'          => 'id'
                                                                             , 'options_id'  => 'options_id'
                                                                             , 'products_id' => 'products_id'
                                                                             , 'sort_order'  => 'products_options_sort_order'
                                                                             , 'status'      => 'status'
                                                                              )
                                 , 'products_simple_options_values' => array(
                                                                               'id'                 => 'id'
                                                                             , 'products_id'        => 'products_id'
                                                                             , 'customers_group_id' => 'customers_group_id'
                                                                             , 'values_id'          => 'options_values_id'
                                                                             , 'options_id'         => 'options_id'
                                                                             , 'price_modifier'     => 'options_values_price'
                                                                             , 'price_prefix'       => 'price_prefix'
                                                           )
                                 , 'products_to_categs'             => array(
                                                                               'products_id'   => 'products_id'
                                                                             , 'categories_id' => 'categories_id'
                                                                              )
                                 , 'products_variants_groups'       => array(
                                                                               'id'           => 'products_options_text_id'
                                                                             , 'languages_id' => 'language_id'
                                                                             , 'title'        => 'products_options_name'
                                                                             , 'sort_order'   => 'sort_order'
                                                                             , 'module'       => 'module'
                                                                              )
                                 , 'products_variants_values'       => array(
                                                                               'id'                          => 'id'
                                                                             , 'languages_id'                => 'language_id'
                                                                             , 'products_variants_groups_id' => 'products_options_id'
                                                                             , 'title'                       => 'products_options_values_name'
                                                                             , 'sort_order'                  => 'sort_order'
                                                                              )
                                 , 'images'                         => array(
                                                                               'products_id'         => 'products_id'
                                                                             , 'products_image'      => 'products_image'
                                                                             , 'products_image_med'  => 'products_image_med'
                                                                             , 'products_image_lrg'  => 'products_image_lrg'
                                                                             , 'products_image_xl_1' => 'products_image_xl_1'
                                                                             , 'products_image_xl_2' => 'products_image_xl_2'
                                                                             , 'products_image_xl_3' => 'products_image_xl_3'
                                                                             , 'products_image_xl_4' => 'products_image_xl_4'
                                                                             , 'products_image_xl_5' => 'products_image_xl_5'
                                                                             , 'products_image_xl_6' => 'products_image_xl_6'
                                                                              )
                                 , 'images_groups'                  => array(
                                                                               'id'          => 'id'
                                                                             , 'language_id' => 'language_id'
                                                                             , 'title'       => 'title'
                                                                             , 'code'        => 'code'
                                                                             , 'size_width'  => 'size_width'
                                                                             , 'size_height' => 'size_height'
                                                                             , 'force_size'  => 'force_size'
                                                                              )
                                 , 'reviews'                        => array(              
                                                                               'reviews_id'     => 'reviews_id'
                                                                             , 'products_id'    => 'products_id'
                                                                             , 'customers_id'   => 'customers_id'
                                                                             , 'customers_name' => 'customers_name'
                                                                             , 'reviews_rating' => 'reviews_rating'
                                                                             , 'languages_id'   => 'languages_id'
                                                                             , 'reviews_text'   => 'reviews_text'
                                                                             , 'date_added'     => 'date_added'
                                                                             , 'last_modified'  => 'last_modified'
                                                                             , 'reviews_read'   => 'reviews_read'
                                                                             , 'reviews_status' => 'reviews_status'
                                                                              )
                                 , 'specials'                       => array(
                                                                               'specials_id'                 => 'specials_id'
                                                                             , 'products_id'                 => 'products_id'
                                                                             , 'specials_new_products_price' => 'specials_new_products_price'
                                                                             , 'specials_date_added'         => 'specials_date_added'
                                                                             , 'specials_last_modified'      => 'specials_last_modified'
                                                                             , 'start_date'                  => 'start_date'
                                                                             , 'expires_date'                => 'expires_date'
                                                                             , 'date_status_change'          => 'date_status_change'
                                                                             , 'status'                      => 'status'
                                                                              )
                                 , 'tax_class'                      => array(
                                                                               'tax_class_id'          => 'tax_class_id'
                                                                             , 'tax_class_title'       => 'tax_class_title'
                                                                             , 'tax_class_description' => 'tax_class_description'
                                                                             , 'last_modified'         => 'last_modified'
                                                                             , 'date_added'            => 'date_added'
                                                                              )
                                 , 'tax_rates'                      => array(
                                                                               'tax_rates_id'    => 'tax_rates_id'
                                                                             , 'tax_zone_id'     => 'tax_zone_id'
                                                                             , 'tax_class_id'    => 'tax_class_id'
                                                                             , 'tax_priority'    => 'tax_priority'
                                                                             , 'tax_rate'        => 'tax_rate'
                                                                             , 'tax_description' => 'tax_description'
                                                                             , 'last_modified'   => 'last_modified'
                                                                             , 'date_added'      => 'date_added'
                                                                              )
                                  );
  }    

  public function isAccessible() { 
    return 1; 
  }  
  
  public function setConnectDetails($params) {
    
    $this->_sDB['DB_CLASS']           = (isset($params['DB_DATABASE_CLASS'])) ? $params['DB_DATABASE_CLASS'] : 'mysqli';
    $this->_sDB['DB_SERVER']          = $params['SOURCE_SERVER'];
    $this->_sDB['DB_SERVER_USERNAME'] = $params['SOURCE_USER'];
    $this->_sDB['DB_SERVER_PASSWORD'] = $params['SOURCE_PASS'];
    $this->_sDB['DB_DATABASE']        = $params['SOURCE_DB'];
    $this->_sDB['INSTALL_PATH']       = $params['INSTALL_PATH'];
    $this->_sDB['IMAGE_PATH']         = $params['SOURCE_IMAGE_PATH'];    
    $this->_tDB['DB_CLASS']           = $params['DB_DATABASE_CLASS'];
    $this->_tDB['DB_SERVER']          = $params['DB_SERVER'];
    $this->_tDB['DB_SERVER_USERNAME'] = $params['DB_SERVER_USERNAME'];
    $this->_tDB['DB_SERVER_PASSWORD'] = $params['DB_SERVER_PASSWORD'];
    $this->_tDB['DB_DATABASE']        = $params['DB_DATABASE'];
    $this->_tDB['DB_PREFIX']          = $params['DB_TABLE_PREFIX'];

  }
  
  public function showConnectInfo() {
    echo '<pre>';
    print_r($this->_sDB);
    echo '</pre>';

    echo '<pre>';
    print_r($this->_tDB);
    echo '</pre>';
  }

  /*
  *  function name : importProducts()
  *
  *  description : load products, products_description, products_to_categories, products_notifications, manufacturers, reviews and specials from the source database to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importProducts($switch = null) {

    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
    $map = $this->_map;
          
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
    
    require_once('../includes/database_tables.php');  

    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
    
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
    
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
    
    // CONNNECT TO TARGET DB
                
    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
    
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
    
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB
    
    // TRUNCATE PRODUCTS TABLE IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS_DESCRIPTION);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS_PRICING);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS_NOTIFICATIONS);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS_TO_CATEGORIES);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_MANUFACTURERS);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_MANUFACTURERS_INFO);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_REVIEWS);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_SPECIALS);
    $tQry->execute();

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // END TRUNCATE PRODUCTS TABLE IN TARGET DB
    
    $map = $this->_data_mapping['products'];

    $sQry = $source_db->query('select * from products');
    $sQry->execute();

    // LOAD PRODUCTS FROM SOURCE DB
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $product  = array(
                            'products_id'            => $sQry->value($map['products_id'])
                          , 'parent_id'              => $sQry->value($map['parent_id'])
                          , 'products_quantity'      => $sQry->value($map['products_quantity'])
                          , 'products_price'         => $sQry->value($map['products_price'])
                          , 'products_cost'          => ($sQry->value($map['products_cost']) != '' || $sQry->value($map['products_cost']) != NULL) ? $sQry->value($map['products_cost']) : 0
                          , 'products_msrp'          => ($sQry->value($map['products_msrp']) != '' || $sQry->value($map['products_msrp']) != NULL) ? $sQry->value($map['products_msrp']) : 0
                          , 'products_model'         => $sQry->value($map['products_model'])
                          , 'products_sku'           => ($sQry->value($map['products_sku']) != '' || $sQry->value($map['products_sku']) != NULL) ? $sQry->value($map['products_sku']) : null
                          , 'products_date_added'    => ($sQry->value($map['products_date_added']) != '' || $sQry->value($map['products_date_added']) != NULL) ? $sQry->value($map['products_date_added']) : "0000-00-00 00:00:00"
                          , 'products_last_modified' => $sQry->value($map['products_last_modified'])
                          , 'products_weight'        => $sQry->value($map['products_weight'])
                          , 'products_weight_class'  => 4
                          , 'products_status'        => $sQry->value($map['products_status'])
                          , 'products_tax_class_id'  => $sQry->value($map['products_tax_class_id'])
                          , 'manufacturers_id'       => ($sQry->value($map['manufacturers_id']) != '' || $sQry->value($map['manufacturers_id']) != NULL) ? $sQry->value($map['manufacturers_id']) : 0
                          , 'products_ordered'       => $sQry->value($map['products_ordered'])
                          , 'products_price1'        => $sQry->value('products_price1')
                          , 'products_price2'        => $sQry->value('products_price2')
                          , 'products_price3'        => $sQry->value('products_price3')
                          , 'products_price4'        => $sQry->value('products_price4')
                          , 'products_price5'        => $sQry->value('products_price5')
                          , 'products_price6'        => $sQry->value('products_price6')
                          , 'products_price7'        => $sQry->value('products_price7')
                          , 'products_price8'        => $sQry->value('products_price8')
                          , 'products_price9'        => $sQry->value('products_price9')
                          , 'products_price10'       => $sQry->value('products_price10')
                          , 'products_price11'       => $sQry->value('products_price11')
                          , 'products_price1_qty'    => $sQry->value('products_price1_qty')
                          , 'products_price2_qty'    => $sQry->value('products_price2_qty')
                          , 'products_price3_qty'    => $sQry->value('products_price3_qty')
                          , 'products_price4_qty'    => $sQry->value('products_price4_qty')
                          , 'products_price5_qty'    => $sQry->value('products_price5_qty')
                          , 'products_price6_qty'    => $sQry->value('products_price6_qty')
                          , 'products_price7_qty'    => $sQry->value('products_price7_qty')
                          , 'products_price8_qty'    => $sQry->value('products_price8_qty')
                          , 'products_price9_qty'    => $sQry->value('products_price9_qty')
                          , 'products_price10_qty'   => $sQry->value('products_price10_qty')
                          , 'products_price11_qty'   => $sQry->value('products_price11_qty')
                          , 'has_children'           => 0
                           ); 
      
        $tQry = $target_db->query('INSERT INTO :table_products (products_id, 
                                                                parent_id, 
                                                                products_quantity, 
                                                                products_price, 
                                                                products_cost, 
                                                                products_msrp, 
                                                                products_model, 
                                                                products_sku, 
                                                                products_date_added, 
                                                                products_last_modified, 
                                                                products_weight, 
                                                                products_weight_class, 
                                                                products_status, 
                                                                products_tax_class_id, 
                                                                manufacturers_id, 
                                                                products_ordered, 
                                                                has_children,
                                                                is_subproduct) 
                                                        VALUES (:products_id, 
                                                                :parent_id, 
                                                                :products_quantity, 
                                                                :products_price, 
                                                                :products_cost, 
                                                                :products_msrp, 
                                                                :products_model, 
                                                                :products_sku, 
                                                                :products_date_added, 
                                                                :products_last_modified, 
                                                                :products_weight, 
                                                                :products_weight_class, 
                                                                :products_status, 
                                                                :products_tax_class_id, 
                                                                :manufacturers_id, 
                                                                :products_ordered, 
                                                                :has_children,
                                                                :is_subproduct)');

        $tQry->bindTable(':table_products', TABLE_PRODUCTS);
        $tQry->bindInt  (':products_id'           , $product['products_id']);
        $tQry->bindInt  (':parent_id'             , $product['parent_id']);
        $tQry->bindInt  (':products_quantity'     , $product['products_quantity']);
        $tQry->bindFloat(':products_price'        , $product['products_price']);
        $tQry->bindFloat(':products_cost'         , $product['products_cost']);
        $tQry->bindFloat(':products_msrp'         , $product['products_msrp']);
        $tQry->bindValue(':products_model'        , $product['products_model']);
        $tQry->bindValue(':products_sku'          , $product['products_sku']);
        $tQry->bindDate (':products_date_added'   , $product['products_date_added']);
        $tQry->bindDate (':products_last_modified', $product['products_last_modified']);
        $tQry->bindFloat(':products_weight'       , $product['products_weight']);
        $tQry->bindValue(':products_weight_class' , $product['products_weight_class']);
        $tQry->bindInt  (':products_status'       , 1);
        $tQry->bindInt  (':products_tax_class_id' , $product['products_tax_class_id']);
        $tQry->bindInt  (':manufacturers_id'      , $product['manufacturers_id']);
        $tQry->bindInt  (':products_ordered'      , $product['products_ordered']);
        $tQry->bindInt  (':has_children'          , $product['has_children']);
        $tQry->bindInt  (':is_subproduct'         , ($product['parent_id'] != 0) ? 1 : 0);
        $tQry->execute();
        
        // Added for qty price breaks START ////////////////////////////////////////////////////////////
        
        // get the lowest customers group id
        $lcgidQry = $source_db->query("SELECT MIN(customers_group_id) AS customers_group_id FROM customers_groups");
        $lcgidQry->execute();
        
        $customers_group_id = ($lcgidQry->numberOfRows() > 0) ? $lcgidQry->value('customers_group_id') : 1;
        
        if ($product['products_price1'] > 0 && $product['products_price1'] !== $product['products_price']) {
          $tp1Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                            group_id,
                                                                            tax_class_id,
                                                                            qty_break,
                                                                            price_break) 
                                                                    VALUES (:products_id,
                                                                            :group_id,
                                                                            :tax_class_id,
                                                                            :qty_break,
                                                                            :price_break)');
                                                                    
          $tp1Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp1Qry->bindInt(':products_id'             , $product['products_id']);
          $tp1Qry->bindInt(':group_id'                , $customers_group_id);
          $tp1Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp1Qry->bindInt(':qty_break'               , $product['products_price1_qty']);
          $tp1Qry->bindFloat(':price_break'           , $product['products_price1']);
          $tp1Qry->execute();
        }
        
        if ($product['products_price2'] > 0 && $product['products_price2'] !== $product['products_price']) {
          $tp2Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                            group_id,
                                                                            tax_class_id,
                                                                            qty_break,
                                                                            price_break) 
                                                                    VALUES (:products_id,
                                                                            :group_id,
                                                                            :tax_class_id,
                                                                            :qty_break,
                                                                            :price_break)');
                                                                    
          $tp2Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp2Qry->bindInt(':products_id'             , $product['products_id']);
          $tp2Qry->bindInt(':group_id'                , $customers_group_id);
          $tp2Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp2Qry->bindInt(':qty_break'               , $product['products_price2_qty']);
          $tp2Qry->bindFloat(':price_break'           , $product['products_price2']);
          $tp2Qry->execute();
        }
        
        if ($product['products_price3'] > 0 && $product['products_price3'] !== $product['products_price']) {
          $tp3Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                            group_id,
                                                                            tax_class_id,
                                                                            qty_break,
                                                                            price_break) 
                                                                    VALUES (:products_id,
                                                                            :group_id,
                                                                            :tax_class_id,
                                                                            :qty_break,
                                                                            :price_break)');
                                                                    
          $tp3Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp3Qry->bindInt(':products_id'             , $product['products_id']);
          $tp3Qry->bindInt(':group_id'                , $customers_group_id);
          $tp3Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp3Qry->bindInt(':qty_break'               , $product['products_price3_qty']);
          $tp3Qry->bindFloat(':price_break'           , $product['products_price3']);
          $tp3Qry->execute();
        }
        
        if ($product['products_price4'] > 0 && $product['products_price4'] !== $product['products_price']) {
          $tp4Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                            group_id,
                                                                            tax_class_id,
                                                                            qty_break,
                                                                            price_break) 
                                                                    VALUES (:products_id,
                                                                            :group_id,
                                                                            :tax_class_id,
                                                                            :qty_break,
                                                                            :price_break)');
                                                                    
          $tp4Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp4Qry->bindInt(':products_id'             , $product['products_id']);
          $tp4Qry->bindInt(':group_id'                , $customers_group_id);
          $tp4Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp4Qry->bindInt(':qty_break'               , $product['products_price4_qty']);
          $tp4Qry->bindFloat(':price_break'           , $product['products_price4']);
          $tp4Qry->execute();
        }
        
        if ($product['products_price5'] > 0 && $product['products_price5'] !== $product['products_price']) {
          $tp5Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                            group_id,
                                                                            tax_class_id,
                                                                            qty_break,
                                                                            price_break) 
                                                                    VALUES (:products_id,
                                                                            :group_id,
                                                                            :tax_class_id,
                                                                            :qty_break,
                                                                            :price_break)');
                                                                    
          $tp5Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp5Qry->bindInt(':products_id'             , $product['products_id']);
          $tp5Qry->bindInt(':group_id'                , $customers_group_id);
          $tp5Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp5Qry->bindInt(':qty_break'               , $product['products_price5_qty']);
          $tp5Qry->bindFloat(':price_break'           , $product['products_price5']);
          $tp5Qry->execute();
        }
        
        if ($product['products_price6'] > 0 && $product['products_price6'] !== $product['products_price']) {
          $tp6Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                            group_id,
                                                                            tax_class_id,
                                                                            qty_break,
                                                                            price_break) 
                                                                    VALUES (:products_id,
                                                                            :group_id,
                                                                            :tax_class_id,
                                                                            :qty_break,
                                                                            :price_break)');
                                                                    
          $tp6Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp6Qry->bindInt(':products_id'             , $product['products_id']);
          $tp6Qry->bindInt(':group_id'                , $customers_group_id);
          $tp6Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp6Qry->bindInt(':qty_break'               , $product['products_price6_qty']);
          $tp6Qry->bindFloat(':price_break'           , $product['products_price6']);
          $tp6Qry->execute();
        }
        
        if ($product['products_price7'] > 0 && $product['products_price7'] !== $product['products_price']) {
          $tp7Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                            group_id,
                                                                            tax_class_id,
                                                                            qty_break,
                                                                            price_break) 
                                                                    VALUES (:products_id,
                                                                            :group_id,
                                                                            :tax_class_id,
                                                                            :qty_break,
                                                                            :price_break)');
                                                                    
          $tp7Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp7Qry->bindInt(':products_id'             , $product['products_id']);
          $tp7Qry->bindInt(':group_id'                , $customers_group_id);
          $tp7Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp7Qry->bindInt(':qty_break'               , $product['products_price7_qty']);
          $tp7Qry->bindFloat(':price_break'           , $product['products_price7']);
          $tp7Qry->execute();
        }
        
        if ($product['products_price8'] > 0 && $product['products_price8'] !== $product['products_price']) {
          $tp8Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                            group_id,
                                                                            tax_class_id,
                                                                            qty_break,
                                                                            price_break) 
                                                                    VALUES (:products_id,
                                                                            :group_id,
                                                                            :tax_class_id,
                                                                            :qty_break,
                                                                            :price_break)');
                                                                    
          $tp8Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp8Qry->bindInt(':products_id'             , $product['products_id']);
          $tp8Qry->bindInt(':group_id'                , $customers_group_id);
          $tp8Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp8Qry->bindInt(':qty_break'               , $product['products_price8_qty']);
          $tp8Qry->bindFloat(':price_break'           , $product['products_price8']);
          $tp8Qry->execute();
        }
        
        if ($product['products_price9'] > 0 && $product['products_price9'] !== $product['products_price']) {
          $tp9Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                            group_id,
                                                                            tax_class_id,
                                                                            qty_break,
                                                                            price_break) 
                                                                    VALUES (:products_id,
                                                                            :group_id,
                                                                            :tax_class_id,
                                                                            :qty_break,
                                                                            :price_break)');
                                                                    
          $tp9Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp9Qry->bindInt(':products_id'             , $product['products_id']);
          $tp9Qry->bindInt(':group_id'                , $customers_group_id);
          $tp9Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp9Qry->bindInt(':qty_break'               , $product['products_price9_qty']);
          $tp9Qry->bindFloat(':price_break'           , $product['products_price9']);
          $tp9Qry->execute();
        }
        
        if ($product['products_price10'] > 0 && $product['products_price10'] !== $product['products_price']) {
          $tp10Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                             group_id,
                                                                             tax_class_id,
                                                                             qty_break,
                                                                             price_break) 
                                                                     VALUES (:products_id,
                                                                             :group_id,
                                                                             :tax_class_id,
                                                                             :qty_break,
                                                                             :price_break)');
                                                                    
          $tp10Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp10Qry->bindInt(':products_id'             , $product['products_id']);
          $tp10Qry->bindInt(':group_id'                , $customers_group_id);
          $tp10Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp10Qry->bindInt(':qty_break'               , $product['products_price10_qty']);
          $tp10Qry->bindFloat(':price_break'           , $product['products_price10']);
          $tp10Qry->execute();
        }
        
        if ($product['products_price11'] > 0 && $product['products_price11'] !== $product['products_price']) {
          $tp11Qry = $target_db->query('INSERT INTO :table_products_pricing (products_id,
                                                                             group_id,
                                                                             tax_class_id,
                                                                             qty_break,
                                                                             price_break) 
                                                                     VALUES (:products_id,
                                                                             :group_id,
                                                                             :tax_class_id,
                                                                             :qty_break,
                                                                             :price_break)');
                                                                    
          $tp11Qry->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $tp11Qry->bindInt(':products_id'             , $product['products_id']);
          $tp11Qry->bindInt(':group_id'                , $customers_group_id);
          $tp11Qry->bindInt(':tax_class_id'            , $product['products_tax_class_id']);
          $tp11Qry->bindInt(':qty_break'               , $product['products_price11_qty']);
          $tp11Qry->bindFloat(':price_break'           , $product['products_price11']);
          $tp11Qry->execute();
        }
        
        $lcgidQry->freeResult();
        
        // Added for qty price breaks END //////////////////////////////////////////////////////////////
        
        $mQry = $target_db->query('SELECT id FROM :table_templates_boxes WHERE code = "manufacturers"');
        $mQry->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
        $mQry->execute(); 
      
        $aQry = $target_db->query('INSERT INTO :table_product_attributes (id, 
                                                                          products_id, 
                                                                          languages_id,  
                                                                          value,
                                                                          value2) 
                                                                  VALUES (:id, 
                                                                          :products_id, 
                                                                          :languages_id, 
                                                                          :value,
                                                                          :value2)');

        $aQry->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
        $aQry->bindInt(':id'           , $mQry->value('id'));
        $aQry->bindInt(':products_id'  , $sQry->value($map['products_id']));
        $aQry->bindInt(':languages_id' , 0);
        $aQry->bindInt(':value'        , ($sQry->value($map['manufacturers_id']) != '' || $sQry->value($map['manufacturers_id']) != NULL) ? $sQry->value($map['manufacturers_id']) : 0);
        $aQry->bindInt(':value2'       , '');
        $aQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        $cnt++;
      }
      
      $sQry->freeResult();
      $mQry->freeResult();
    }
    
    // added for removing duplicate qty price break entries
    $clean = $target_db->query('ALTER IGNORE TABLE :table_products_pricing ADD UNIQUE INDEX (products_id, group_id, price_break)');
    $clean->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $clean->execute();
        
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }
    
    // END LOAD PRODUCTS FROM SOURCE DB

    // LOAD PRODUCTS DESCRIPTION FROM SOURCE DB

    $map = $this->_data_mapping['products_desc'];

    $products_desc = array();
    
    $sQry = $source_db->query('SELECT * FROM products_description');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $product  = array(
                            'products_id'               => $sQry->value($map['products_id'])
                          , 'language_id'               => $sQry->value($map['language_id'])
                          , 'products_name'             => $sQry->value($map['products_name'])
                          , 'products_description'      => $sQry->value($map['products_description'])
                          , 'products_keyword'          => $sQry->value($map['products_keyword'])
                          , 'products_tags'             => ($sQry->value($map['products_tags']) != '' || $sQry->value($map['products_tags']) != NULL) ? $sQry->value($map['products_tags']) : ""
                          , 'products_meta_title'       => ($sQry->value($map['products_meta_title']) != '' || $sQry->value($map['products_meta_title']) != NULL) ? $sQry->value($map['products_meta_title']) : ""
                          , 'products_meta_keywords'    => ($sQry->value($map['products_meta_keywords']) != '' || $sQry->value($map['products_meta_keywords']) != NULL) ? $sQry->value($map['products_meta_keywords']) : ""
                          , 'products_meta_description' => ($sQry->value($map['products_meta_description']) != '' || $sQry->value($map['products_meta_description']) != NULL) ? $sQry->value($map['products_meta_description']) : ""
                          , 'products_url'              => ($sQry->value($map['products_url']) != '' || $sQry->value($map['products_url']) != NULL) ? $sQry->value($map['products_url']) : ""
                          , 'products_viewed'           => ($sQry->value($map['products_viewed']) != '' || $sQry->value($map['products_viewed']) != NULL) ? $sQry->value($map['products_viewed']) : ""
                           ); 
                         
        $products_desc[] = $product;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD PRODUCTS DESCRIPTION FROM SOURCE DB

    // LOAD PRODUCTS DESCRIPTION TO TARGET DB
    
    $iCnt = 0;
    foreach ($products_desc as $product) {      
      $permalink = self::generateCleanPermalink($product['products_name']);
      
      // START Added for duplicate keyword/permalink
      $pkQry = $target_db->query('SELECT products_keyword FROM :table_products_description WHERE products_keyword = :products_keyword');
      $pkQry->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $pkQry->bindValue(':products_keyword', $permalink);
      $pkQry->execute();
      
      if ($pkQry->numberOfRows() > 0) {
        $permalink .= '-' . $product['products_id'];
      } 
      // END Added for duplicate keyword/permalink
      
      if (preg_match('/product$/i', $permalink)) {
        $permalink .= '-link';
      }
      
      
      $tQry = $target_db->query('INSERT INTO :table_products_description (products_id, 
                                                                   language_id, 
                                                                   products_name, 
                                                                   products_description, 
                                                                   products_keyword, 
                                                                   products_tags, 
                                                                   products_meta_title, 
                                                                   products_meta_keywords, 
                                                                   products_meta_description, 
                                                                   products_url, 
                                                                   products_viewed) 
                                                           VALUES (:products_id, 
                                                                   :language_id, 
                                                                   :products_name, 
                                                                   :products_description, 
                                                                   :products_keyword, 
                                                                   :products_tags, 
                                                                   :products_meta_title, 
                                                                   :products_meta_keywords, 
                                                                   :products_meta_description, 
                                                                   :products_url, 
                                                                   :products_viewed)');

      $tQry->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $tQry->bindInt  (':products_id'              , $product['products_id']);
      $tQry->bindInt  (':language_id'              , $product['language_id']);
      $tQry->bindValue(':products_name'            , $product['products_name']);
      $tQry->bindValue(':products_description'     , $product['products_description']);
      $tQry->bindValue(':products_keyword'         , $permalink);
      $tQry->bindValue(':products_tags'            , $product['products_tags']);
      $tQry->bindValue(':products_meta_title'      , $product['products_meta_title']);
      $tQry->bindValue(':products_meta_keywords'   , $product['products_meta_keywords']);
      $tQry->bindValue(':products_meta_description', $product['products_meta_description']);
      $tQry->bindValue(':products_url'             , $product['products_url']);
      $tQry->bindInt  (':products_viewed'          , $product['products_viewed']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }
    $tQry->freeResult();

    // END LOAD PRODUCTS DESCRIPTION TO TARGET DB
    
    // ##########
    
    // LOAD PRODUCTS NOTIFICATIONS FROM SOURCE DB
    
    $map = $this->_data_mapping['products_notif'];

    $products_notifs = array();
    
    $sQry = $source_db->query('SELECT * FROM products_notifications');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $product  = array(
                            'products_id'  => $sQry->value($map['products_id'])
                          , 'customers_id' => $sQry->value($map['customers_id'])
                          , 'date_added'   => ($sQry->value($map['date_added']) != '' || $sQry->value($map['date_added']) != NULL) ? $sQry->value($map['date_added']) : ""
                           ); 
                         
        $products_notifs[] = $product;

      
        $tQry = $target_db->query('INSERT INTO :table_products_notifs (products_id, 
                                                                       customers_id, 
                                                                       date_added) 
                                                               VALUES (:products_id, 
                                                                       :customers_id, 
                                                                       :date_added)');

        $tQry->bindTable(':table_products_notifs', TABLE_PRODUCTS_NOTIFICATIONS);
        $tQry->bindInt  (':products_id' , $product['products_id']);
        $tQry->bindInt  (':customers_id', $product['customers_id']);
        $tQry->bindDate (':date_added'  , $product['date_added']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }

        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD PRODUCTS DESCRIPTION FROM SOURCE DB

    // ##########
    
    // LOAD PRODUCTS TO CATEGORIES FROM SOURCE DB
    
    $map = $this->_data_mapping['products_to_categs'];

    $products_to_categs = array();
    
    $sQry = $source_db->query('SELECT * FROM products_to_categories');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $product  = array(
                            'products_id'   => $sQry->value('products_id')
                          , 'categories_id' => $sQry->value('categories_id')
                           ); 
                         
        $tQry = $target_db->query('INSERT INTO :table_products_to_categs (products_id, 
                                                                          categories_id) 
                                                                  VALUES (:products_id, 
                                                                          :categories_id)');

        $tQry->bindTable(':table_products_to_categs', TABLE_PRODUCTS_TO_CATEGORIES);
        $tQry->bindInt  (':products_id'  , $product['products_id']);
        $tQry->bindInt  (':categories_id', $product['categories_id']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }

        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD PRODUCTS TO CATEGORIES FROM SOURCE DB

    // ##########
    
    // LOAD PRODUCTS PERMALINK FROM SOURCE DB

    $map = $this->_data_mapping['permalink'];

    $permalink_array = array();
    
    $sQry = $source_db->query('SELECT p.products_id, language_id, products_name, categories_id FROM products as p, products_description AS pd, products_to_categories p2c WHERE p.products_id = pd.products_id AND p.products_id = p2c.products_id ORDER BY p.products_id, pd.language_id');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $cID = $sQry->value('categories_id') ;
        
        // getCPATH CODE         
        
        $cat_list = $cID;
        $catID = $cID;

        while ($catID != 0) {
          $the_categories_name_sql = "SELECT DISTINCT (c.categories_id), parent_id, categories_name, sort_order FROM categories AS c, categories_description AS cd WHERE c.categories_id = " . $catID . " AND c.categories_id = cd.categories_id AND language_id = " . $this->_languages_id_default;
                      
          $scQry = $source_db->query($the_categories_name_sql);
          $scQry->execute();
          $scQry->next();
          
          $catID = $scQry->value('parent_id');
          
          if ($catID == 0) { 
            break; 
          }
            
          $cat_list = $catID . "_" . $cat_list;
          
          $scQry->freeResult();
        }
        if (empty($cat_list)) { 
          $cat_list = '0'; 
        }
        $cat_list = "cPath=" . $cat_list;
        
        // END getCPATH CODE         
      
        $permatext = self::generateCleanPermalink($sQry->value('products_name'));
      
        // START Added for duplicate keyword/permalink
        $pkQry = $target_db->query('SELECT products_keyword FROM :table_products_description WHERE products_keyword = :products_keyword');
        $pkQry->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $pkQry->bindValue(':products_keyword', $permatext);
        $pkQry->execute();
        
        if ($pkQry->numberOfRows() > 0) {
          $permatext .= '-' . $sQry->value('products_id');
        } 
        // END Added for duplicate keyword/permalink
      
        if (preg_match('/product$/i', $permatext)) {
          $permatext .= '-link';
        }
        
        $permalink  = array(
                              'item_id'       => $sQry->value('products_id')
                            , 'categories_id' => $sQry->value('categories_id')
                            , 'language_id'   => $sQry->value('language_id')
                            , 'type'          => 2
                            , 'query'         => ""
                            , 'permalink'     => $permatext
                             ); 
        
        $tQry = $target_db->query('INSERT INTO :table_permalink (item_id, 
                                                                 language_id, 
                                                                 `type`, 
                                                                 query, 
                                                                 permalink) 
                                                         VALUES (:item_id, 
                                                                 :language_id, 
                                                                 :type, 
                                                                 :query, 
                                                                 :permalink)');
        
        $tQry->bindTable(':table_permalink', TABLE_PERMALINKS);
        $tQry->bindInt  (':item_id'    , $permalink['item_id']);
        $tQry->bindInt  (':language_id', $permalink['language_id']);
        $tQry->bindInt  (':type'        , $permalink['type']);
        $tQry->bindValue(':query'      , $cat_list);
        $tQry->bindValue(':permalink'  , $permalink['permalink']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD PRODUCTS PERMALINK FROM SOURCE DB

    // END LOAD PERMALINK TO TARGET DB

    // ##########
    
    // LOAD MANUFACTURERS FROM SOURCE DB
    $map = $this->_data_mapping['manufacturers'];

    $manufacturers = array();

    $sQry = $source_db->query('select * from manufacturers');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $manufacturer  = array(
                                 'manufacturers_id'    => $sQry->value($map['manufacturers_id'])
                               , 'manufacturers_name'  => $sQry->value($map['manufacturers_name'])
                               , 'manufacturers_image' => $sQry->value($map['manufacturers_image'])
                               , 'date_added'          => $sQry->value($map['date_added'])
                               , 'last_modified'       => $sQry->value($map['last_modified'])
                                ); 

        $tQry = $target_db->query('INSERT INTO :table_manufacturers (manufacturers_id, 
                                                                     manufacturers_name, 
                                                                     manufacturers_image, 
                                                                     date_added, 
                                                                     last_modified) 
                                                             VALUES (:manufacturers_id, 
                                                                     :manufacturers_name, 
                                                                     :manufacturers_image, 
                                                                     :date_added, 
                                                                     :last_modified)');

        $tQry->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
        $tQry->bindInt  (':manufacturers_id'   , $manufacturer['manufacturers_id']);
        $tQry->bindValue(':manufacturers_name' , $manufacturer['manufacturers_name']);
        $tQry->bindValue(':manufacturers_image', $manufacturer['manufacturers_image']);
        $tQry->bindDate (':date_added'         , $manufacturer['date_added']);
        $tQry->bindDate (':last_modified'      , $manufacturer['last_modified']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }

        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD MANUFACTURERS FROM SOURCE DB
    
    // #############

    // LOAD MANUFACTURERS INFO FROM SOURCE DB
    $map = $this->_data_mapping['manufacturers_info'];

    $manufacturers_infos = array();

    $sQry = $source_db->query('SELECT * FROM manufacturers_info');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $manufacturers_info  = array(
                                       'manufacturers_id'  => $sQry->value($map['manufacturers_id'])
                                     , 'languages_id'      => $sQry->value($map['languages_id'])
                                     , 'manufacturers_url' => $sQry->value($map['manufacturers_url'])
                                     , 'url_clicked'       => $sQry->value($map['url_clicked'])
                                     , 'date_last_click'   => $sQry->value($map['date_last_click'])
                                      );  
                    
        $manufacturers_infos[] = $manufacturers_info;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD MANUFACTURERS INFO FROM SOURCE DB

    // LOAD MANUFACTURERS INFO TO TARGET DB
      
    $iCnt = 0;
    foreach ($manufacturers_infos as $manufacturers_info) {
      
      $tQry = $target_db->query('INSERT INTO :table_manufacturers_info (manufacturers_id, 
                                                                        languages_id, 
                                                                        manufacturers_url, 
                                                                        url_clicked, 
                                                                        date_last_click) 
                                                                VALUES (:manufacturers_id, 
                                                                        :languages_id, 
                                                                        :manufacturers_url, 
                                                                        :url_clicked, 
                                                                        :date_last_click)');

      $tQry->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
      $tQry->bindInt  (':manufacturers_id' , $manufacturers_info['manufacturers_id']);
      $tQry->bindInt  (':languages_id'     , $manufacturers_info['languages_id']);
      $tQry->bindValue(':manufacturers_url', $manufacturers_info['manufacturers_url']);
      $tQry->bindInt  (':url_clicked'      , $manufacturers_info['url_clicked']);
      $tQry->bindDate (':date_last_click'  , $manufacturers_info['date_last_click']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD MANUFACTURERS INFO TO TARGET DB
    
    // ##########
    
    // LOAD REVIEWS FROM SOURCE DB
    
    $map = $this->_data_mapping['reviews'];

    $reviews = array();
    
    $sQry = $source_db->query('SELECT * FROM reviews r, reviews_description rd WHERE r.reviews_id = rd.reviews_id');
    $sQry->execute();

    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $review  = array(
                           'reviews_id'     => $sQry->value($map['reviews_id'])
                         , 'products_id'    => $sQry->value($map['products_id'])
                         , 'customers_id'   => $sQry->value($map['customers_id'])
                         , 'customers_name' => $sQry->value($map['customers_name'])
                         , 'reviews_rating' => $sQry->value($map['reviews_rating'])
                         , 'languages_id'   => $sQry->value($map['languages_id'])
                         , 'reviews_text'   => $sQry->value($map['reviews_text'])
                         , 'date_added'     => $sQry->value($map['date_added'])
                         , 'last_modified'  => $sQry->value($map['last_modified'])
                         , 'reviews_read'   => $sQry->value($map['reviews_read'])
                         , 'reviews_status' => $sQry->value($map['reviews_status'])
                          ); 
                         
        $reviews[] = $review;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD REVIEWS FROM SOURCE DB

    // LOAD REVIEWS TO TARGET DB
    
    $iCnt = 0;
    foreach ($reviews as $review) {
      
      $tQry = $target_db->query('INSERT INTO :table_reviews (reviews_id, 
                                                             products_id, 
                                                             customers_id, 
                                                             customers_name, 
                                                             reviews_rating, 
                                                             languages_id, 
                                                             reviews_text, 
                                                             date_added, 
                                                             last_modified, 
                                                             reviews_read, 
                                                             reviews_status) 
                                                     VALUES (:reviews_id, 
                                                             :products_id, 
                                                             :customers_id, 
                                                             :customers_name, 
                                                             :reviews_rating, 
                                                             :languages_id, 
                                                             :reviews_text, 
                                                             :date_added, 
                                                             :last_modified, 
                                                             :reviews_read, 
                                                             :reviews_status)');

      $tQry->bindTable(':table_reviews', TABLE_REVIEWS);
      $tQry->bindInt  (':reviews_id'    , $review['reviews_id']);
      $tQry->bindInt  (':products_id'   , $review['products_id']);
      $tQry->bindInt  (':customers_id'  , $review['customers_id']);
      $tQry->bindValue(':customers_name', $review['customers_name']);
      $tQry->bindInt  (':reviews_rating', $review['reviews_rating']);
      $tQry->bindInt  (':languages_id'  , $review['languages_id']);
      $tQry->bindValue(':reviews_text'  , $review['reviews_text']);
      $tQry->bindDate (':date_added'    , $review['date_added']);
      $tQry->bindDate (':last_modified' , $review['last_modified']);
      $tQry->bindInt  (':reviews_read'  , $review['reviews_read']);
      $tQry->bindInt  (':reviews_status', 1);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      
      $iCnt++;
    }
    $tQry->freeResult();

    // END LOAD REVIEWS FROM SOURCE DB
    
    // ##########
    
    // LOAD SPECIALS FROM SOURCE DB
    
    $map = $this->_data_mapping['specials'];

    $specials = array();
    
    $sQry = $source_db->query('SELECT * FROM specials');
    $sQry->execute();

    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $special  = array(
                            'specials_id'                 => $sQry->value($map['specials_id'])
                          , 'products_id'                 => $sQry->value($map['products_id'])
                          , 'specials_new_products_price' => $sQry->value($map['specials_new_products_price'])
                          , 'specials_date_added'         => $sQry->value($map['specials_date_added'])
                          , 'specials_last_modified'      => $sQry->value($map['specials_last_modified'])
                          , 'start_date'                  => $sQry->value($map['start_date'])
                          , 'expires_date'                => $sQry->value($map['expires_date'])
                          , 'date_status_change'          => $sQry->value($map['date_status_change'])
                          , 'status'                      => $sQry->value($map['status'])
                           ); 
                         
        $specials[] = $special;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD SPECIALS FROM SOURCE DB
    
    // LOAD SPECIALS TO TARGET DB
    
    $iCnt = 0;
    foreach ($specials as $special) {
      
      $tQry = $target_db->query('INSERT INTO :table_reviews (specials_id, 
                                                             products_id, 
                                                             specials_new_products_price, 
                                                             specials_date_added, 
                                                             specials_last_modified, 
                                                             start_date, 
                                                             expires_date, 
                                                             date_status_change, 
                                                             status) 
                                                     VALUES (:specials_id, 
                                                             :products_id, 
                                                             :specials_new_products_price, 
                                                             :specials_date_added, 
                                                             :specials_last_modified, 
                                                             :start_date, :expires_date, 
                                                             :date_status_change, 
                                                             :status)');

      $tQry->bindTable(':table_reviews', TABLE_SPECIALS);
      $tQry->bindInt  (':specials_id'                , $special['specials_id']);
      $tQry->bindInt  (':products_id'                , $special['products_id']);
      $tQry->bindInt  (':specials_new_products_price', $special['specials_new_products_price']);
      $tQry->bindDate (':specials_date_added'        , $special['specials_date_added']);
      $tQry->bindDate (':specials_last_modified'     , $special['specials_last_modified']);
      $tQry->bindDate (':start_date'                 , $special['start_date']);
      $tQry->bindDate (':expires_date'               , $special['expires_date']);
      $tQry->bindDate (':date_status_change'         , $special['date_status_change']);
      $tQry->bindInt  (':status'                     , $special['status']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      
      $iCnt++;
    }
    $tQry->freeResult();

    // END LOAD SPECIALS FROM SOURCE DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
          
    // DISCONNECT FROM SOURCE AND TARGET DBs
    
    $source_db->disconnect();  
    
    $target_db->disconnect();  

    // END DISCONNECT FROM SOURCE AND TARGET DBs
    
    return true;
      
  } // end importProducts

  /*
  *  function name : getcPath()
  *
  *  description : get the recursive parent cPath of a category from the source db
  *
  *  returns : string  
  *
  */
  public function getcPath($_cid, $_path = null) {
      
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
    $map = $this->_data_mapping['categories'];
          
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
    
    require_once('../includes/database_tables.php');
    
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS']; 
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
    
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
    
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
    
    // BEGIN GET CPATH FOR CATEGORY
    $pQry = $source_db->query("SELECT DISTINCT (categories_id), parent_id FROM categories WHERE categories_id = " . $_cid);
    $pQry->execute();
    
    while ($pQry->next()) {
      if ($pQry->value('parent_id') != 0) {
        if ($_path != '') {
          $_path = $pQry->value('parent_id') . "_" . $_path;
          $path = lC_LocalUpgrader::getcPath($pQry->value('parent_id'), $_path) . "_" . $_path;
        } else {
          $path = $pQry->value('parent_id') . "_" . $_cid;
        }
      } else {
        $path = $_cid;
      }
    }

    $pQry->freeResult();
    
    return $path;
      
  }

  /*
  *  function name : importCategories()
  *
  *  description : load categories and categories_description to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importCategories($switch = null) {
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
    $map = $this->_data_mapping['categories'];
    
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
    
    require_once('../includes/database_tables.php');
    
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS']; 
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
    
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
    
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
    
    // CONNNECT TO TARGET DB  

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
    
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
    
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB

    // TRUNCATE CATEGORIES TABLES IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . TABLE_CATEGORIES);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_CATEGORIES_DESCRIPTION);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_PERMALINKS);
    $tQry->execute();
    
    // END TRUNCATE CATEGORIES TABLES IN TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD CATEGORIES FROM SOURCE DB
    
    $sQry = $source_db->query('SELECT * FROM categories');
    $sQry->execute();

    $numrows = $sQry->numberOfRows();
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $category  = array(
                             'categories_id'               => $sQry->value('categories_id')
                           , 'categories_image'            => $sQry->value('categories_image')
                           , 'parent_id'                   => $sQry->value('parent_id')
                           , 'sort_order'                  => $sQry->value('sort_order')
                           , 'categories_mode'             => 'category'
                           , 'categories_link_target'      => 0
                           , 'categories_custom_url'       => ""
                           , 'categories_show_in_listings' => 1
                           , 'categories_status'           => 1
                           , 'categories_visibility_nav'   => 0
                           , 'categories_visibility_box'   => 1
                           , 'date_added'                  => $sQry->value('date_added')
                           , 'last_modified'               => $sQry->value('last_modified')
                            ); 

        $tQry = $target_db->query('INSERT INTO :table_categories (categories_id, 
                                                                  categories_image, 
                                                                  parent_id, 
                                                                  sort_order, 
                                                                  categories_mode, 
                                                                  categories_link_target, 
                                                                  categories_custom_url, 
                                                                  categories_status, 
                                                                  categories_visibility_nav, 
                                                                  categories_visibility_box, 
                                                                  date_added, 
                                                                  last_modified) 
                                                          VALUES (:categories_id, 
                                                                  :categories_image, 
                                                                  :parent_id, 
                                                                  :sort_order, 
                                                                  :categories_mode, 
                                                                  :categories_link_target, 
                                                                  :categories_custom_url, 
                                                                  :categories_status, 
                                                                  :categories_visibility_nav, 
                                                                  :categories_visibility_box, 
                                                                  :date_added, 
                                                                  :last_modified)');
  
        $tQry->bindTable(':table_categories', TABLE_CATEGORIES);
        $tQry->bindInt  (':categories_id'            , $category['categories_id']);
        $tQry->bindValue(':categories_image'         , $category['categories_image']);
        $tQry->bindInt  (':parent_id'                , $category['parent_id']);
        $tQry->bindInt  (':sort_order'               , $category['sort_order']);
        $tQry->bindValue(':categories_mode'          , $category['categories_mode']);
        $tQry->bindInt  (':categories_link_target'   , $category['categories_link_target']);
        $tQry->bindValue(':categories_custom_url'    , $category['categories_custom_url']);
        $tQry->bindInt  (':categories_status'        , $category['categories_status']);
        $tQry->bindInt  (':categories_visibility_nav', $category['categories_visibility_nav']);
        $tQry->bindInt  (':categories_visibility_box', $category['categories_visibility_box']);
        $tQry->bindDate (':date_added'               , $category['date_added']);
        $tQry->bindDate (':last_modified'            , $category['last_modified']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD CATEGORIES FROM SOURCE DB

    // ##########
    
    // LOAD CATEGORIES DESCRIPTION FROM SOURCE DB

    $map = $this->_data_mapping['categories_desc'];
    
    $sQry = $source_db->query('SELECT * FROM categories_description');
    $sQry->execute();

    $categories_desc = array();
      
    $numrows = $sQry->numberOfRows();
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
      
        $c_ID = $sQry->value('categories_id');
      
        $c_keyword = self::generateCleanPermalink($sQry->value('categories_name'));
      
        if (preg_match('/category$/i', $c_keyword)) {
          $c_keyword .= '-link';
        }
        
        $category  = array(
                             'categories_id'          => $c_ID
                           , 'language_id'            => $sQry->value('language_id')
                           , 'categories_name'        => $sQry->value('categories_name')
                           , 'categories_menu_name'   => ""
                           , 'categories_blurb'       => ""
                           , 'categories_description' => $sQry->value('categories_description')
                           , 'categories_keyword'     => $c_keyword
                           , 'categories_tags'        => $sQry->value('categories_head_keywords_tag')
                            ); 

        $tQry = $target_db->query('INSERT INTO :table_categories_desc (categories_id, 
                                                                       language_id, 
                                                                       categories_name, 
                                                                       categories_menu_name, 
                                                                       categories_blurb, 
                                                                       categories_description, 
                                                                       categories_keyword, 
                                                                       categories_tags) 
                                                               VALUES (:categories_id, 
                                                                       :language_id, 
                                                                       :categories_name, 
                                                                       :categories_menu_name, 
                                                                       :categories_blurb, 
                                                                       :categories_description, 
                                                                       :categories_keyword, 
                                                                       :categories_tags)');
        
        $tQry->bindTable(':table_categories_desc', TABLE_CATEGORIES_DESCRIPTION);
        $tQry->bindInt  (':categories_id'         , $category['categories_id']);
        $tQry->bindInt  (':language_id'           , $category['language_id']);
        $tQry->bindValue(':categories_name'       , $category['categories_name']);
        $tQry->bindValue(':categories_menu_name'  , $category['categories_menu_name']);
        $tQry->bindValue(':categories_blurb'      , $category['categories_blurb']);
        $tQry->bindValue(':categories_description', $category['categories_description']);
        $tQry->bindValue(':categories_keyword'    , $category['categories_keyword']);
        $tQry->bindValue(':categories_tags'       , substr($category['categories_tags'], 0, 255));
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }

        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD CATEGORIES DESCRIPTION FROM SOURCE DB

    // ##########

    // LOAD CATEGORY PERMALINKs TO TARGET DB
    
    $iCnt = 0;
    $sQry = $source_db->query('SELECT c.categories_id, c.parent_id, cd.language_id, cd.categories_name FROM categories as c, categories_description AS cd WHERE c.categories_id = cd.categories_id');
    $sQry->execute();
      
    $numrows = $sQry->numberOfRows();
    if ($numrows > 0) {      
      while ($sQry->next()) {

        $c_ID = $sQry->value('categories_id');

        //  #### getCPATH CODE
        
        $cat_list = "cPath=" . lC_LocalUpgrader::getcPath($c_ID); 
                
        //  #### END getCPATH CODE         
        
        $permatext = self::generateCleanPermalink($sQry->value('categories_name'));
      
        if (preg_match('/category$/i', $permatext)) {
          $permatext .= '-link';
        }
        
        $permalink  = array(
                              'item_id'     => $c_ID
                            , 'language_id' => $sQry->value('language_id')
                             ); 
        
        $tQry = $target_db->query('INSERT INTO :table_permalink (item_id, 
                                                                 language_id, 
                                                                 `type`, 
                                                                 query, 
                                                                 permalink) 
                                                         VALUES (:item_id, 
                                                                 :language_id, 
                                                                 :type, 
                                                                 :query, 
                                                                 :permalink)');
        
        $tQry->bindTable(':table_permalink', TABLE_PERMALINKS);
        $tQry->bindInt  (':item_id'    , $permalink['item_id']);
        $tQry->bindInt  (':language_id', $permalink['language_id']);
        $tQry->bindInt  (':type'       , 1);
        $tQry->bindValue(':query'      , $cat_list);
        $tQry->bindValue(':permalink'  , $permatext);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        $iCnt++;
      }

      $sQry->freeResult();
    }
    
    // END LOAD PERMALINK TO TARGET DB
    
    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET sql_mode = ""');
    $tQry->execute();

    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importCategories

  /*
  *  function name : getcdsPath()
  *
  *  description : get the recursive parent CDpath of a cds category or page from the source db and convert it to cPath
  *
  *  returns : string  
  *
  */
  public function getcdsPath($_cid, $_path) {
      
    $t_db = $this->_tDB;
          
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO TARGET DB
    
    require_once('../includes/database_tables.php');
    
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS']; 
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
    
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
    
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }
    // END CONNNECT TO TARGET DB
    
    // BEGIN GET CPATH FOR CDS CATEGORY/PAGE
    $tQry = $target_db->query("SELECT DISTINCT (categories_id), parent_id FROM " . $t_db['DB_PREFIX'] . "categories WHERE categories_id = " . $_cid);
    $tQry->execute();
    
    while ($tQry->next()) {
      if ($tQry->value('parent_id') != 0) {
        if ($_path != '') {
          $_path = $tQry->value('parent_id') . "_" . $_path;
          $path = lC_LocalUpgrader::getcdsPath($tQry->value('parent_id'), $_path) . "_" . $_path;
        } else {
          $path = $tQry->value('parent_id') . "_" . $_cid;
        }
      } else {
        $path = $_cid;
      }
    }

    $tQry->freeResult();
    
    return $path;
      
  }
  
  /*
  *  function name : importPages()
  *
  *  description : load pages and pages categories to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importPages($switch = null) {
  
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
    $map = $this->_data_mapping['page_categories'];
          
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
    
    require_once('../includes/database_tables.php');
                                   
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
    
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
    
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
    
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
    
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
    
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // END LOAD CATEGORY PAGES FROM SOURCE DB

    $page_categories = array();

    $sQry = $source_db->query('SELECT * FROM pages_categories');
    $sQry->execute();
      
    $numrows = $sQry->numberOfRows();
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
      
        $cID = $sQry->value('categories_id');

        $c_mode = 'category';
        
        $c_keyword = self::generateCleanPermalink($sQry->value($map['categories_name']));
      
        if (preg_match('/category$/i', $c_keyword)) {
          $c_keyword .= '-link';
        } 
        
        // added for category custom url (6.5 url override, only for external links and links to products) 
        if ($sQry->value('categories_url_override') != '') {
          $c_mode = 'override';
          $c_override = 'index.php?old_urloverride=' . $sQry->value('categories_url_override');
        }           
        
        $category  = array(
                             'categories_id'             => $cID
                           , 'categories_image'          => $sQry->value('categories_image')
                           , 'parent_id'                 => $sQry->value('categories_parent_id')
                           , 'sort_order'                => $sQry->value('categories_sort_order')
                           , 'categories_mode'           => $c_mode
                           , 'categories_link_target'    => ($sQry->value('categories_url_override_target') != '') ? 1 : 0
                           , 'categories_custom_url'     => $c_override
                           , 'categories_status'         => $sQry->value('categories_status')
                           , 'categories_visibility_nav' => 0
                           , 'categories_visibility_box' => $sQry->value('categories_in_menu')
                           , 'date_added'                => $sQry->value('categories_date_added')
                           , 'last_modified'             => $sQry->value('categories_last_modified')
                            );
        
        $c_mode = null;
        $c_override = null;
          
        $tQry = $target_db->query('INSERT INTO :table_categories (categories_image, 
                                                                  parent_id, 
                                                                  sort_order, 
                                                                  categories_mode, 
                                                                  categories_link_target, 
                                                                  categories_custom_url, 
                                                                  categories_status, 
                                                                  categories_visibility_nav, 
                                                                  categories_visibility_box, 
                                                                  date_added, 
                                                                  last_modified) 
                                                          VALUES (:categories_image, 
                                                                  :parent_id, 
                                                                  :sort_order, 
                                                                  :categories_mode, 
                                                                  :categories_link_target, 
                                                                  :categories_custom_url, 
                                                                  :categories_status, 
                                                                  :categories_visibility_nav, 
                                                                  :categories_visibility_box, 
                                                                  :date_added, 
                                                                  :last_modified)');
        
        $tQry->bindTable(':table_categories', TABLE_CATEGORIES);
        $tQry->bindValue(':categories_image'         , $category['categories_image']);
        $tQry->bindInt  (':parent_id'                , $category['parent_id']);
        $tQry->bindInt  (':sort_order'               , $category['sort_order']);
        $tQry->bindValue(':categories_mode'          , $category['categories_mode']);
        $tQry->bindInt  (':categories_link_target'   , $category['categories_link_target']);
        $tQry->bindValue(':categories_custom_url'    , $category['categories_custom_url']);
        $tQry->bindInt  (':categories_status'        , $category['categories_status']);
        $tQry->bindInt  (':categories_visibility_nav', $category['categories_visibility_nav']);
        $tQry->bindInt  (':categories_visibility_box', $category['categories_visibility_box']);
        $tQry->bindDate (':date_added'               , $category['date_added']);
        $tQry->bindDate (':last_modified'            , $category['last_modified']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        $insert_id = $target_db->nextID();
        $page_categories[$cID] = $insert_id;
          
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD CATEGORY PAGES FROM SOURCE DB
    
    // LOAD CATEGORY PAGES DESCRIPTION TO TARGET DB
    
    foreach ($page_categories as $cID => $insert_id) {

      $sQry = $source_db->query('SELECT * FROM pages_categories_description pcd WHERE pcd.categories_id = ' . $cID);
      $sQry->execute();
      
      $numrows = $sQry->numberOfRows();
      if ($numrows > 0) { 
        $cnt = 0;
        while ($sQry->next()) {
          $c_keyword = self::generateCleanPermalink($sQry->value('categories_name'));
      
          if (preg_match('/category$/i', $c_keyword)) {
            $c_keyword .= '-link';
          }
          
          $tQry = $target_db->query('INSERT INTO :table_categories_desc (categories_id, 
                                                                         language_id, 
                                                                         categories_name, 
                                                                         categories_menu_name, 
                                                                         categories_blurb, 
                                                                         categories_description, 
                                                                         categories_keyword, 
                                                                         categories_tags) 
                                                                 VALUES (:categories_id, 
                                                                         :language_id, 
                                                                         :categories_name, 
                                                                         :categories_menu_name, 
                                                                         :categories_blurb, 
                                                                         :categories_description, 
                                                                         :categories_keyword, 
                                                                         :categories_tags)');
          
          $tQry->bindTable(':table_categories_desc', TABLE_CATEGORIES_DESCRIPTION);
          $tQry->bindInt  (':categories_id'         , $insert_id);
          $tQry->bindInt  (':language_id'           , $sQry->value('language_id'));
          $tQry->bindValue(':categories_name'       , $sQry->value('categories_name'));
          $tQry->bindValue(':categories_menu_name'  , $sQry->value('categories_menu_name'));
          $tQry->bindValue(':categories_blurb'      , $sQry->value('categories_blurb'));
          $tQry->bindValue(':categories_description', $sQry->value('categories_description'));
          $tQry->bindValue(':categories_keyword'    , $c_keyword);
          $tQry->bindValue(':categories_tags'       , $sQry->value('categories_meta_description'));
          $tQry->execute();
        }
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD CATEGORY PAGES DESCRIPTION TO TARGET DB
    
    // LOAD PAGE PAGES FROM SOURCE DB
    
    $map = $this->_data_mapping['page_pages'];

    $page_pages = array();

    $sQry = $source_db->query('SELECT * FROM pages AS p, pages_to_categories AS pc WHERE p.pages_id = pc.pages_id');
    $sQry->execute();
      
    $numrows = $sQry->numberOfRows();
    if ($numrows > 0) { 
      while ($sQry->next()) {
        $pID = $sQry->value('pages_id');
        
        // get the original parent id from source db
        $cidQry = $source_db->query("SELECT categories_id FROM pages_to_categories WHERE pages_id = " . $sQry->value('pages_id'));
        $cidQry->execute();
        
        if ($cidQry->value('categories_id') != 0) {
          // get the original parent name from the source db
          $cnQry = $source_db->query("SELECT categories_name FROM pages_categories_description WHERE categories_id = " . $cidQry->value('categories_id'));
          $cnQry->execute();
          
          // get the new categories id from the target db
          $cnQry = $target_db->query("SELECT categories_id FROM " . $t_db['DB_PREFIX'] . "categories_description WHERE categories_name = '" . $cnQry->value('categories_name') . "'");
          $cnQry->execute();
          
          $cID = $cnQry->value('categories_id');
        } else {
          $cID = 0;
        }
        
        $c_keyword = self::generateCleanPermalink($sQry->value('pages_title'));
      
        if (preg_match('/category$/i', $c_keyword)) {
          $c_keyword .= '-link';
        }
        
        $page  = array(
                         'categories_id'             => $sQry->value('pages_id')
                       , 'categories_image'          => $sQry->value('pages_image')
                       , 'parent_id'                 => $cID
                       , 'sort_order'                => $sQry->value('pages_sort_order')
                       , 'categories_mode'           => 'page'
                       , 'categories_link_target'    => 0
                       , 'categories_custom_url'     => ""
                       , 'categories_status'         => $sQry->value('pages_status')
                       , 'categories_visibility_nav' => 0
                       , 'categories_visibility_box' => $sQry->value('pages_in_menu')
                       , 'date_added'                => $sQry->value('pages_date_added')
                       , 'last_modified'             => $sQry->value('pages_last_modified')
                        ); 
        
        $tQry = $target_db->query('INSERT INTO :table_categories (categories_image, 
                                                                  parent_id, 
                                                                  sort_order, 
                                                                  categories_mode, 
                                                                  categories_link_target, 
                                                                  categories_custom_url, 
                                                                  categories_status, 
                                                                  categories_visibility_nav, 
                                                                  categories_visibility_box, 
                                                                  date_added, 
                                                                  last_modified) 
                                                          VALUES (:categories_image, 
                                                                  :parent_id, 
                                                                  :sort_order, 
                                                                  :categories_mode, 
                                                                  :categories_link_target, 
                                                                  :categories_custom_url, 
                                                                  :categories_status, 
                                                                  :categories_visibility_nav, 
                                                                  :categories_visibility_box, 
                                                                  :date_added, 
                                                                  :last_modified)');
          
        $tQry->bindTable(':table_categories', TABLE_CATEGORIES);
        $tQry->bindValue(':categories_image'         , $page['categories_image']);
        $tQry->bindInt  (':parent_id'                , $page['parent_id']);
        $tQry->bindInt  (':sort_order'               , $page['sort_order']);
        $tQry->bindValue(':categories_mode'          , $page['categories_mode']);
        $tQry->bindInt  (':categories_link_target'   , $page['categories_link_target']);
        $tQry->bindValue(':categories_custom_url'    , $page['categories_custom_url']);
        $tQry->bindInt  (':categories_status'        , $page['categories_status']);
        $tQry->bindInt  (':categories_visibility_nav', $page['categories_visibility_nav']);
        $tQry->bindInt  (':categories_visibility_box', $page['categories_visibility_box']);
        $tQry->bindDate (':date_added'               , $page['date_added']);
        $tQry->bindDate (':last_modified'            , $page['last_modified']);
        $tQry->execute();
        
        $insert_id = $target_db->nextID();
        $page_pages[$pID] = $insert_id;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD PAGE PAGES FROM SOURCE DB

    // LOAD PAGE PAGES DESCRIPTION TO TARGET DB
    
    foreach ($page_pages as $pID => $insert_id) {
      
      $sQry = $source_db->query('SELECT * FROM pages_description WHERE pages_id = ' . $pID);
      $sQry->execute();        
    
      $numrows = $sQry->numberOfRows();
      if ($numrows > 0) { 
        while ($sQry->next()) {

          $c_keyword = self::generateCleanPermalink($sQry->value('pages_title'));
      
          if (preg_match('/category$/i', $c_keyword)) {
            $c_keyword .= '-link';
          }
          
          $tQry = $target_db->query('INSERT INTO :table_categories_desc (categories_id, 
                                                                         language_id, 
                                                                         categories_name, 
                                                                         categories_menu_name, 
                                                                         categories_blurb, 
                                                                         categories_description, 
                                                                         categories_keyword, 
                                                                         categories_tags) 
                                                                 VALUES (:categories_id, 
                                                                         :language_id, 
                                                                         :categories_name, 
                                                                         :categories_menu_name, 
                                                                         :categories_blurb, 
                                                                         :categories_description, 
                                                                         :categories_keyword, 
                                                                         :categories_tags)');
          
          $tQry->bindTable(':table_categories_desc', TABLE_CATEGORIES_DESCRIPTION);
          $tQry->bindInt  (':categories_id'         , $insert_id);
          $tQry->bindInt  (':language_id'           , $sQry->value('language_id') );
          $tQry->bindValue(':categories_name'       , $sQry->value('pages_title') );
          $tQry->bindValue(':categories_menu_name'  , $sQry->value('pages_menu_name') );
          $tQry->bindValue(':categories_blurb'      , $sQry->value('pages_blurb') );
          $tQry->bindValue(':categories_description', $sQry->value('pages_body') );
          $tQry->bindValue(':categories_keyword'    , $c_keyword);
          $tQry->bindValue(':categories_tags'       , $sQry->value('pages_meta_keywords') );
          $tQry->execute();
        }
      }        
    }
    
    // END LOAD PAGE PAGES DESCRIPTION TO TARGET DB
    
    // LOAD CATEGORY PAGES PERMALINK FROM TARGET DB

    $permalink_array = array();
    
    foreach ($page_categories as $cID => $insert_id) { 
      $tQry = $target_db->query("SELECT c.categories_id, cd.language_id, cd.categories_keyword FROM " . $t_db['DB_PREFIX'] . "categories AS c, " . $t_db['DB_PREFIX'] . "categories_description AS cd WHERE c.categories_id = " . $insert_id . " AND c.categories_id = cd.categories_id");
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      
      if ($tQry->numberOfRows() > 0) {
        while ($tQry->next()) {
          $permalink  = array(
                                'item_id'       => $tQry->value('categories_id')
                              , 'language_id'   => $tQry->value('language_id')
                              , 'type'          => 1
                              , 'query'         => ''
                              , 'permalink'     => $tQry->value('categories_keyword')
                               ); 
                           
          $permalink_array[] = $permalink;
        }
      }
    }
    
    // END LOAD CATEGORY PAGES PERMALINK FROM SOURCE DB

    // LOAD PAGES PAGES PERMALINK FROM TARGET DB
    
    foreach ($page_pages as $cID => $insert_id) {
      $tQry = $target_db->query("SELECT c.categories_id, cd.language_id, cd.categories_keyword FROM " . $t_db['DB_PREFIX'] . "categories AS c, " . $t_db['DB_PREFIX'] . "categories_description AS cd WHERE c.categories_id = " . $insert_id . " AND c.categories_id = cd.categories_id");
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      
      if ($tQry->numberOfRows() > 0) {
        while ($tQry->next()) {            
          $permalink  = array(
                                'item_id'       => $tQry->value('categories_id')
                              , 'language_id'   => $tQry->value('language_id')
                              , 'type'          => 1
                              , 'query'         => ''
                              , 'permalink'     => $tQry->value('categories_keyword')
                               ); 
          $permalink_array[] = $permalink;
        }
      }
      $tQry->freeResult();
    }

    // END LOAD PAGES PAGES PERMALINK FROM SOURCE DB
    
    // SAVE CATEGORY PAGES AND PAGE PAGES PERMALINKS TO TARGET DB

    $iCnt = 0;
    
    foreach ($permalink_array as $permalink) {

      //  #### getCPATH CODE

      $cat_list = "cPath=" . lC_LocalUpgrader::getcdsPath($permalink['item_id'], null);
      
      //  #### END getCPATH CODE         
      
      $tQry = $target_db->query('INSERT INTO :table_permalink (item_id, 
                                                               language_id, 
                                                               `type`, 
                                                               query, 
                                                               permalink) 
                                                       VALUES (:item_id, 
                                                               :language_id, 
                                                               :type, 
                                                               :query, 
                                                               :permalink)');
      
      $tQry->bindTable(':table_permalink', TABLE_PERMALINKS);
      $tQry->bindInt  (':item_id'    , $permalink['item_id']);
      $tQry->bindInt  (':language_id', $permalink['language_id']);
      $tQry->bindInt  (':type'       , $permalink['type']);
      $tQry->bindValue(':query'      , $cat_list);
      $tQry->bindValue(':permalink'  , self::generateCleanPermalink($permalink['permalink']));
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }

    }
    
    $tQry->freeResult();

    // END LOAD PERMALINK TO TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();

    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importPages
  
  /*
  *  function name : importCustomers()
  *
  *  description : load customers and address book data from the source database to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importCustomers($switch = null) {
  
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
    $map = $this->_data_mapping['customers'];
          
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
    
    require_once('../includes/database_tables.php');
                                                        
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
    
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
    
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
    
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
    
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
    
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB

    // TRUNCATE CUSTOMERS TABLES IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . TABLE_CUSTOMERS);
    $tQry->execute();
    
    $tQry = $target_db->query('truncate table ' . TABLE_ADDRESS_BOOK);
    $tQry->execute();

    // END TRUNCATE CUSTOMERS TABLES IN TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD CUSTOMERS FROM SOURCE DB

    $sQry = $source_db->query('SELECT * FROM customers c LEFT JOIN customers_info ci ON c.customers_id = ci.customers_info_id');
    $sQry->execute();
    
    $customers = array();  
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $customer  = array(
                             'customers_id'                 => $sQry->value($map['customers_id'])
                           , 'customers_group_id'           => ($sQry->value($map['customers_group_id']) != null) ? $sQry->value($map['customers_group_id']) : 1
                           , 'customers_gender'             => $sQry->value($map['customers_gender'])
                           , 'customers_firstname'          => $sQry->value($map['customers_firstname'])
                           , 'customers_lastname'           => $sQry->value($map['customers_lastname'])
                           , 'customers_dob'                => $sQry->value($map['customers_dob'])
                           , 'customers_email_address'      => $sQry->value($map['customers_email_address'])
                           , 'customers_default_address_id' => $sQry->value($map['customers_default_address_id'])
                           , 'customers_telephone'          => ($sQry->value($map['customers_telephone']) != '' || $sQry->value($map['customers_telephone']) != NULL) ? $sQry->value($map['customers_telephone']) : ""
                           , 'customers_fax'                => ($sQry->value($map['customers_fax']) != '' || $sQry->value($map['customers_fax']) != NULL) ? $sQry->value($map['customers_fax']) : ""
                           , 'customers_password'           => $sQry->value($map['customers_password'])
                           , 'customers_newsletter'         => $sQry->value($map['customers_newsletter'])
                           , 'customers_status'             => 1
                           , 'customers_ip_address'         => ($sQry->value($map['customers_ip_address']) != '' || $sQry->value($map['customers_ip_address']) != NULL) ? $sQry->value($map['customers_ip_address']) : ""
                           , 'date_last_logon'              => ($sQry->value($map['date_last_logon']) != '' || $sQry->value($map['date_last_logon']) != NULL) ? $sQry->value($map['date_last_logon']) : ""
                           , 'number_of_logons'             => ($sQry->value($map['number_of_logons']) != '' || $sQry->value($map['number_of_logons']) != NULL) ? $sQry->value($map['number_of_logons']) : ""
                           , 'date_account_created'         => ($sQry->value($map['date_account_created']) != '' || $sQry->value($map['date_account_created']) != NULL) ? $sQry->value($map['date_account_created']) : ""
                           , 'date_account_last_modified'   => ($sQry->value($map['date_account_last_modified']) != '' || $sQry->value($map['date_account_last_modified']) != NULL) ? $sQry->value($map['date_account_last_modified']) : ""
                           , 'global_product_notifications' => ($sQry->value($map['global_product_notifications']) != '' || $sQry->value($map['global_product_notifications']) != NULL) ? $sQry->value($map['global_product_notifications']) : 1
                            ); 

        $tQry = $target_db->query('INSERT INTO :table_customers (customers_id, 
                                                                 customers_group_id, 
                                                                 customers_gender, 
                                                                 customers_firstname, 
                                                                 customers_lastname, 
                                                                 customers_dob, 
                                                                 customers_email_address, 
                                                                 customers_default_address_id, 
                                                                 customers_telephone, 
                                                                 customers_fax, 
                                                                 customers_password, 
                                                                 customers_newsletter, 
                                                                 customers_status, 
                                                                 customers_ip_address, 
                                                                 date_last_logon, 
                                                                 number_of_logons, 
                                                                 date_account_created, 
                                                                 date_account_last_modified, 
                                                                 global_product_notifications) 
                                                         VALUES (:customers_id, 
                                                                 :customers_group_id, 
                                                                 :customers_gender, 
                                                                 :customers_firstname, 
                                                                 :customers_lastname, 
                                                                 :customers_dob, 
                                                                 :customers_email_address, 
                                                                 :customers_default_address_id, 
                                                                 :customers_telephone, 
                                                                 :customers_fax, 
                                                                 :customers_password, 
                                                                 :customers_newsletter, 
                                                                 :customers_status, 
                                                                 :customers_ip_address, 
                                                                 :date_last_logon, 
                                                                 :number_of_logons, 
                                                                 :date_account_created, 
                                                                 :date_account_last_modified, 
                                                                 :global_product_notifications)');

        $tQry->bindTable(':table_customers', TABLE_CUSTOMERS);
        $tQry->bindInt  (':customers_id'                , $customer['customers_id']);
        $tQry->bindInt  (':customers_group_id'          , $customer['customers_group_id']);
        $tQry->bindValue(':customers_gender'            , $customer['customers_gender']);
        $tQry->bindValue(':customers_firstname'         , $customer['customers_firstname']);
        $tQry->bindValue(':customers_lastname'          , $customer['customers_lastname']);
        $tQry->bindDate (':customers_dob'               , $customer['customers_dob']);
        $tQry->bindValue(':customers_email_address'     , $customer['customers_email_address']);
        $tQry->bindInt  (':customers_default_address_id', $customer['customers_default_address_id']);
        $tQry->bindValue(':customers_telephone'         , $customer['customers_telephone']);
        $tQry->bindValue(':customers_fax'               , $customer['customers_fax']);
        $tQry->bindValue(':customers_password'          , $customer['customers_password']);
        $tQry->bindValue(':customers_newsletter'        , $customer['customers_newsletter']);
        $tQry->bindInt  (':customers_status'            , $customer['customers_status']);
        $tQry->bindValue(':customers_ip_address'        , $customer['customers_ip_address']);
        $tQry->bindDate (':date_last_logon'             , $customer['date_last_logon']);
        $tQry->bindInt  (':number_of_logons'            , $customer['number_of_logons']);
        $tQry->bindDate (':date_account_created'        , $customer['date_account_created']);
        $tQry->bindDate (':date_account_last_modified'  , $customer['date_account_last_modified']);
        $tQry->bindInt  (':global_product_notifications', $customer['global_product_notifications']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }

        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD CUSTOMERS FROM SOURCE DB
    
    // ##########

    // LOAD ADDRESS BOOK FROM SOURCE DB
    
    $map = $this->_data_mapping['address_book'];

    $sQry = $source_db->query('SELECT * FROM address_book');
    $sQry->execute();
    
    $customers = array();  
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $address  = array(
                            'address_book_id'      => $sQry->value($map['address_book_id'])
                          , 'customers_id'         => $sQry->value($map['customers_id'])
                          , 'entry_gender'         => $sQry->value($map['entry_gender'])
                          , 'entry_company'        => $sQry->value($map['entry_company'])
                          , 'entry_firstname'      => $sQry->value($map['entry_firstname'])
                          , 'entry_lastname'       => $sQry->value($map['entry_lastname'])
                          , 'entry_street_address' => $sQry->value($map['entry_street_address'])
                          , 'entry_suburb'         => $sQry->value($map['entry_suburb'])
                          , 'entry_postcode'       => $sQry->value($map['entry_postcode'])
                          , 'entry_city'           => $sQry->value($map['entry_city'])
                          , 'entry_state'          => $sQry->value($map['entry_state'])
                          , 'entry_country_id'     => $sQry->value($map['entry_country_id'])
                          , 'entry_zone_id'        => $sQry->value($map['entry_zone_id'])
                          , 'entry_telephone'      => $sQry->value($map['entry_telephone'])
                          , 'entry_fax'            => $sQry->value($map['entry_fax'])
                           );
                           
        // get zone_name from source db 
        if ($sQry->value($map['entry_zone_id']) != 0) {
          $znQry = $source_db->query("SELECT zone_name FROM zones WHERE zone_id = " . $sQry->value($map['entry_zone_id']));
          $znQry->execute();
          $zone_name = $znQry->value('zone_name');
        } else {
          $zone_name = $sQry->value($map['entry_state']);
        }
        
        // get zone_code from new db 
        $nzQry = $target_db->query("SELECT zone_id FROM " . $t_db['DB_PREFIX'] . "zones WHERE zone_country_id = " . $sQry->value($map['entry_country_id']) . " AND zone_name = '" . $zone_name . "'");
        $nzQry->execute();
        
        $tQry = $target_db->query('INSERT INTO :table_address_book (address_book_id,
                                                                    customers_id, 
                                                                    entry_gender, 
                                                                    entry_company, 
                                                                    entry_firstname, 
                                                                    entry_lastname, 
                                                                    entry_street_address, 
                                                                    entry_suburb, 
                                                                    entry_postcode, 
                                                                    entry_city, 
                                                                    entry_state, 
                                                                    entry_country_id, 
                                                                    entry_zone_id, 
                                                                    entry_telephone, 
                                                                    entry_fax) 
                                                            VALUES (:address_book_id,
                                                                    :customers_id, 
                                                                    :entry_gender, 
                                                                    :entry_company, 
                                                                    :entry_firstname, 
                                                                    :entry_lastname, 
                                                                    :entry_street_address, 
                                                                    :entry_suburb, 
                                                                    :entry_postcode, 
                                                                    :entry_city, 
                                                                    :entry_state, 
                                                                    :entry_country_id, 
                                                                    :entry_zone_id, 
                                                                    :entry_telephone, 
                                                                    :entry_fax)');
        
        $tQry->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
        $tQry->bindInt  (':address_book_id'     , $address['address_book_id']);
        $tQry->bindInt  (':customers_id'        , $address['customers_id']);
        $tQry->bindValue(':entry_gender'        , $address['entry_gender']);
        $tQry->bindValue(':entry_company'       , $address['entry_company']);
        $tQry->bindValue(':entry_firstname'     , $address['entry_firstname']);
        $tQry->bindValue(':entry_lastname'      , $address['entry_lastname']);
        $tQry->bindValue(':entry_street_address', $address['entry_street_address']);
        $tQry->bindValue(':entry_suburb'        , $address['entry_suburb']);
        $tQry->bindValue(':entry_postcode'      , $address['entry_postcode']);
        $tQry->bindValue(':entry_city'          , $address['entry_city']);
        $tQry->bindValue(':entry_state'         , $zone_name);
        $tQry->bindInt  (':entry_country_id'    , $address['entry_country_id']);
        $tQry->bindInt  (':entry_zone_id'       , $nzQry->value('zone_id'));
        $tQry->bindValue(':entry_telephone'     , $address['entry_telephone']);
        $tQry->bindValue(':entry_fax'           , $address['entry_fax']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD ADDRESS BOOK FROM SOURCE DB
    
    // ##########

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importCustomers

  /*
  *  function name : importImages()
  *
  *  description : load product images to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importImages() {
    global $lC_Language;

    $source_img_dir = $this->_sDB['INSTALL_PATH'].$this->_sDB['IMAGE_PATH'];
    $target_img_dir = getcwd() . '/../images/products/originals/';

    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
          
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
    
    require_once('../includes/database_tables.php');   

    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
    
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
    
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
    
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
    
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
    
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // TRUNCATE PRODUCTS IMAGES TABLES IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS_IMAGES);
    $tQry->execute();

    // END TRUNCATE PRODUCTS IMAGES TABLES IN TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD products_images_groups FROM TARGET DB
    $map = $this->_data_mapping['images_groups'];
    
    $products_images_groups = array();
    $tQry = $target_db->query('SELECT * FROM ' . TABLE_PRODUCTS_IMAGES_GROUPS);
    $tQry->execute();
      
    if ($tQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ( $tQry->next() ) {
        $images_group  = array(
                                 'id'          => $tQry->value($map['id'])
                               , 'language_id' => $tQry->value($map['language_id'])
                               , 'title'       => $tQry->value($map['title'])
                               , 'code'        => $tQry->value($map['code'])
                               , 'size_width'  => $tQry->value($map['size_width'])
                               , 'size_height' => $tQry->value($map['size_height'])
                               , 'force_size'  => $tQry->value($map['force_size'])
                                ); 
                         
        $products_images_groups[] = $images_group;

        $cnt++;
      }
      
      $tQry->freeResult();
    }
    
    // END LOAD products_images_groups FROM TARGET DB

    // LOAD products images info FROM SOURCE DB
    $map = $this->_data_mapping['images'];

    $source_images = array();
    $sQry = $source_db->query('SELECT * FROM products ORDER BY products_id');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
      
        $product_main_image = '';
        if (strlen($sQry->value($map['products_image_lrg'])) > 0) $product_main_image = $sQry->value($map['products_image_lrg']);
        else if (strlen($sQry->value($map['products_image_med'])) > 0) $product_main_image = $sQry->value($map['products_image_med']);
        else $product_main_image = $sQry->value($map['products_image']);
        
        $images = array(
                          'products_image'      => $product_main_image
                        , 'products_image_xl_1' => $sQry->value($map['products_image_xl_1'])
                        , 'products_image_xl_2' => $sQry->value($map['products_image_xl_2'])
                        , 'products_image_xl_3' => $sQry->value($map['products_image_xl_3'])
                        , 'products_image_xl_4' => $sQry->value($map['products_image_xl_4'])
                        , 'products_image_xl_5' => $sQry->value($map['products_image_xl_5'])
                        , 'products_image_xl_6' => $sQry->value($map['products_image_xl_6'])
                        );
      
        $images_info  = array(
                                'products_id' => $sQry->value($map['products_id'])
                              , 'images'      => serialize($images)
                              , 'status'      => NULL
                               ); 
                         
        $source_images[] = $images_info;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD products images info FROM SOURCE DB
    
    // PROCESS tmp_image_import DATA 
    $to_zip = array();      
    
    foreach ($source_images as $k => $images_info) {
      
      $products_id = $images_info['products_id'];
      $image_array = unserialize($images_info['images']);
      
      $sorder = 0;
      foreach ($image_array as $k => $img_info) {
        
        if (empty($img_info)) continue;
      
        $r = $this->saveToProductsImages($target_db, $products_id, $img_info, $sorder, ($k == 'products_image' ? 1 : 0 ));
        
        if ($r == false) {
          $this->_msg = $target_db->getError();
          return false;
        }
        // if not exist, loop to next;
        if (!file_exists($source_img_dir . $img_info) || !is_readable($source_img_dir . $img_info)) {
          continue;
        }          
        $files_to_zip[] = $source_img_dir.$img_info;
        $to_zip[] = $img_info;

        $sorder++;
      }
                
      $source_images[$k]['status'] = 1;        
    }
    
    $o_dir = getcwd();
    chdir($source_img_dir);
    $target_zip = 'my-product-images-' . time() . '.zip';

    $result = $this->create_zip($to_zip, $target_img_dir . $target_zip);  
    if ($result == false) {
      $this->_msg = $lC_Language->get('upgrade_step4_import_product_images_zipcreateerror');
      return false;
    }    

    $result = $this->extract_zip($target_img_dir . $target_zip, $target_img_dir);      
    if ($result == false) {
      $this->_msg = $lC_Language->get('upgrade_step4_import_product_images_zipextracteerror');
      return false;
    }
        
    unlink($target_img_dir . $target_zip);
    $this->chmod_r($target_img_dir);
    chdir($o_dir);

    // PROCESS tmp_image_import DATA 

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();

    //SET THE RESIZE IMAGES FLAG
    if (!file_exists('../includes/work/resize.tmp')) {
      file_put_contents('../includes/work/resize.tmp', '1');
    }
    
    $source_db->disconnect();  
    $target_db->disconnect(); 
    
    return true;
      
  } // end importImages

  /*
  *  function name : importCategoryImages()
  *
  *  description : load category images from the source server to the new loaded7 server
  *
  *  returns : true or false  
  *
  */
  public function importCategoryImages() {
    global $lC_Language;

    $source_img_dir = $this->_sDB['INSTALL_PATH'].$this->_sDB['IMAGE_PATH'];
    $target_img_dir = getcwd() . '/../images/categories/';

    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
          
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
    
    require_once('../includes/database_tables.php');
                                           
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
    
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
    
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
    
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
    
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
    
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD category images FROM SOURCE DB
    
    $map = $this->_data_mapping['categories'];

    $source_categ_images = array();
    $sQry = $source_db->query('SELECT * FROM categories ORDER BY categories_id');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {

        if (!file_exists($source_img_dir . $sQry->value($map['categories_image'])) || 
            !is_readable($source_img_dir . $sQry->value($map['categories_image']))
            ) {
          continue;
        }          
      
        $images_info  = array(
                                'categories_id'    => $sQry->value($map['categories_id'])
                              , 'categories_image' => $sQry->value($map['categories_image'])
                               ); 
                         
        $source_categ_images[] = $images_info;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD category images FROM SOURCE DB
    
    // COPY category images TO TARGET SERVER
    
    foreach ($source_categ_images as $image) {

      $img_info = $image['categories_image'];
      
      if (empty($img_info)) continue;
      
      // if not exist, loop to next ;
      if (!file_exists($source_img_dir.$img_info) ||
          !is_readable($source_img_dir.$img_info)
          ) {
        continue;
      }          
      $to_zip[] = $img_info;
      
    }

    $o_dir = getcwd();
    chdir($source_img_dir);
    $target_zip = 'my-category-images-' . time() . '.zip';

    $result = $this->create_zip($to_zip, $target_img_dir . $target_zip);  
    if ($result == false) {
      $this->_msg = $lC_Language->get('upgrade_step4_import_category_images_zipcreateerror');
      return false;
    }    

    $result = $this->extract_zip($target_img_dir . $target_zip, $target_img_dir);      
    if ($result == false) {
      $this->_msg = $lC_Language->get('upgrade_step4_import_category_images_zipextracterror');
      return false;
    }
        
    unlink($target_img_dir . $target_zip);
    $this->chmod_r($target_img_dir);
    chdir($o_dir);

    // END COPY category images TO TARGET SERVER

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importCategoryImages
  
  public function saveToProductsImages($target_db, $pid, $image, $sort, $default_flag = 1) {
  
    $image = end(explode('/', $image));
    
    $tQry = $target_db->query('INSERT INTO :table_products_images (products_id, 
                                                                   image, 
                                                                   default_flag, 
                                                                   sort_order) 
                                                           VALUES (:products_id, 
                                                                   :image, 
                                                                   :default_flag, 
                                                                   :sort_order)');

    $tQry->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $tQry->bindInt  (':products_id' , $pid);
    $tQry->bindValue(':image'       , $image);
    $tQry->bindInt  (':default_flag', $default_flag);
    $tQry->bindInt  (':sort_order'  , $sort);
    $tQry->execute();
    
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }
    
    return true;
      
  } // end saveToProductsImages
  
  /*
  *  function name : importCustomerGroups()
  *
  *  description : load customer group data from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importCustomerGroups($switch = null) {
    global $lC_Language;
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
    $map = $this->_data_mapping['customers_groups'];

    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
      
    require_once('../includes/database_tables.php');
                              
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
      
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB
    
    // LOAD CUSTOMERS GROUPS FROM SOURCE DB

    //$tQry = $target_db->query('SELECT COUNT(*) AS exist 
    //                             FROM information_schema.tables 
    //                            WHERE table_schema =  "' . $t_db['DB_DATABASE'] . '" 
    //                              AND table_name = "' . TABLE_CUSTOMERS_GROUPS . '"');
    //$tQry->execute();
    //$tQry->next();
    //if ($tQry->value('exist') != '1') return true;
    //$tQry->freeResult();

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute(); 
    
    $customers_groups = array();
    
    $sQry = $source_db->query('SELECT * FROM customers_groups');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) {
      // TRUNCATE CUSTOMERS GROUPS TABLES IN TARGET DB
      $tQry = $target_db->query('truncate table ' . TABLE_CUSTOMERS_GROUPS);
      $tQry->execute();
      
      $cnt = 0;
      while ($sQry->next()) {
        $customers_group  = array(
                                    'customers_group_id'   => $sQry->value('customers_group_id')
                                  , 'language_id'          => 1
                                  , 'customers_group_name' => $sQry->value('customers_group_name')
                                   ); 
                         
        $tQry = $target_db->query('INSERT INTO :table_customers_groups (customers_group_id, 
                                                                        language_id, 
                                                                        customers_group_name) 
                                                                VALUES (:customers_group_id, 
                                                                        :language_id, 
                                                                        :customers_group_name)');
        
        $tQry->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
        $tQry->bindInt  (':customers_group_id'  , $customers_group['customers_group_id']);
        $tQry->bindInt  (':language_id'         , $customers_group['language_id']);
        $tQry->bindValue(':customers_group_name', $customers_group['customers_group_name']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        $customers_group_data  = array(
                                         'customers_group_id' => $sQry->value('customers_group_id')
                                       , 'baseline_discount'  => $sQry->value('group_discount')
                                        ); 
                         
        $tQry = $target_db->query('INSERT INTO :table_customers_groups_data (customers_group_id, 
                                                                             baseline_discount) 
                                                                     VALUES (:customers_group_id, 
                                                                             :baseline_discount)');
        
        $tQry->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
        $tQry->bindInt  (':customers_group_id', $customers_group_data['customers_group_id']);
        $tQry->bindValue(':baseline_discount' , $customers_group_data['baseline_discount']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }

        $cnt++;
      }
      
      $sQry->freeResult(); 
    
      // get the lowest customers group id
      $lidQry = $source_db->query("SELECT MIN(customers_group_id) AS customers_group_id FROM customers_groups");
      $lidQry->execute();
      
      // set default customers group to lowest resulting id in above query
      $tQry = $target_db->query("UPDATE :table_configuration SET configuration_value = :configuration_value WHERE configuration_key = 'DEFAULT_CUSTOMERS_GROUP_ID'");
      $tQry->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $tQry->bindInt(':configuration_value', $lidQry->value('customers_group_id'));
      $tQry->execute();
    }
    
    // END LOAD CUSTOMERS GROUPS FROM SOURCE DB
    
    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importCustomerGroups

  /*
  *  function name : importOrders()
  *
  *  description : load orders, orders_products, orders_status, orders_status_history, orders_total and orders_products_download 
  *                from the source database to the new loaded7 database
  *
  *  returns : true or false  
  *
  */
  public function importOrders($switch = null) {
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
          
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
    
    require_once('../includes/database_tables.php');
                                     
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    
    // END CONNNECT TO SOURCE DB
    
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB

    // ##########
    
    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // ##########
    
    // TRUNCATE ORDERS TABLE IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . TABLE_ORDERS);
    $tQry->execute();

    $tQry = $target_db->query('truncate table ' . TABLE_ORDERS_PRODUCTS);
    $tQry->execute();

    $tQry = $target_db->query('truncate table ' . TABLE_ORDERS_STATUS);
    $tQry->execute();

    $tQry = $target_db->query('truncate table ' . TABLE_ORDERS_STATUS_HISTORY);
    $tQry->execute();

    $tQry = $target_db->query('truncate table ' . TABLE_ORDERS_TOTAL);
    $tQry->execute();

    $tQry = $target_db->query('truncate table ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD);
    $tQry->execute();
                
    // END TRUNCATE ORDERS TABLE IN TARGET DB 
    
    // ##########

    // LOAD ORDERS STATUS  FROM SOURCE DB
    
    $map = $this->_data_mapping['orders_status'];
    $orders_status = array();

    $sQry = $source_db->query('SELECT * FROM orders_status');
    $sQry->execute();
    
    $numrows = $sQry->numberOfRows();  
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        if ($sQry->value($map['language_id']) == $this->_languages_id_default && $sQry->value($map['orders_status_name']) == 'Pending') {
          $pending_id = $sQry->value($map['orders_status_id']);
        }
        $orders_stat  = array(
                                'orders_status_id'   => $sQry->value($map['orders_status_id'])
                              , 'language_id'        => $sQry->value($map['language_id'])
                              , 'orders_status_name' => $sQry->value($map['orders_status_name'])
                               ); 
                      
        $tQry = $target_db->query('INSERT INTO :table_orders_status (orders_status_id, 
                                                                     language_id, 
                                                                     orders_status_name) 
                                                             VALUES (:orders_status_id, 
                                                                     :language_id, 
                                                                     :orders_status_name)');
        
        $tQry->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
        $tQry->bindInt  (':orders_status_id'  , $orders_stat['orders_status_id']);
        $tQry->bindInt  (':language_id'       , $orders_stat['language_id']);
        $tQry->bindValue(':orders_status_name', $orders_stat['orders_status_name']);
        $tQry->execute();
        $cnt++;
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD ORDERS STATUS FROM SOURCE DB

    $orders_stat = null;
    
    // ##########

    // LOAD ORDERS STATUS HISTORY FROM SOURCE DB
    
    $map = $this->_data_mapping['orders_status_history'];
    $orders_status_histories = array();

    $sQry = $source_db->query('SELECT * FROM orders_status_history');
    $sQry->execute();
    
    $numrows = $sQry->numberOfRows();
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $orders_status_history  = array(
                                          'orders_status_history_id' => $sQry->value($map['orders_status_history_id'])
                                        , 'orders_id'                => $sQry->value($map['orders_id'])
                                        , 'orders_status_id'         => $sQry->value($map['orders_status_id'])
                                        , 'date_added'               => $sQry->value($map['date_added'])
                                        , 'customer_notified'        => $sQry->value($map['customer_notified'])
                                        , 'comments'                 => $sQry->value($map['comments'])
                                         ); 
                      
        $tQry = $target_db->query('INSERT INTO :table_orders_status_history (orders_status_history_id, 
                                                                             orders_id, 
                                                                             orders_status_id, 
                                                                             date_added, 
                                                                             customer_notified, 
                                                                             comments) 
                                                                     VALUES (:orders_status_history_id, 
                                                                             :orders_id, 
                                                                             :orders_status_id, 
                                                                             :date_added, 
                                                                             :customer_notified, 
                                                                             :comments)');
        
        $tQry->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
        $tQry->bindInt  (':orders_status_history_id', $orders_status_history['orders_status_history_id']);
        $tQry->bindInt  (':orders_id'               , $orders_status_history['orders_id']);
        $tQry->bindInt  (':orders_status_id'        , $orders_status_history['orders_status_id']);
        $tQry->bindValue(':date_added'              , $orders_status_history['date_added']);
        $tQry->bindInt  (':customer_notified'       , $orders_status_history['customer_notified']);
        $tQry->bindValue(':comments'                , $orders_status_history['comments']);
        $tQry->execute();

        $cnt++;
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD ORDERS STATUS HISTORY FROM SOURCE DB

    $orders_status_history = null;
          
    // ##########

    // LOAD ORDERS TOTAL FROM SOURCE DB
    
    $map = $this->_data_mapping['orders_total'];
    $orders_total = array();

    $sQry = $source_db->query('SELECT * FROM orders_total');
    $sQry->execute();
    
    $orders_total_map = array(
                                'ot_coupon'   => 'coupon'
                              , 'ot_shipping' => 'shipping'
                              , 'ot_tax'      => 'tax'
                              , 'ot_subtotal' => 'subtotal'
                              , 'ot_total'    => 'total'
                              , 'xxxxxxxx'    => 'low_order_fee'
                               );
    
    $numrows= $sQry->numberOfRows();
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $order_total  = array(
                                'orders_total_id' => $sQry->value('orders_total_id')
                              , 'orders_id'       => $sQry->value('orders_id')
                              , 'title'           => $sQry->value('title')
                              , 'text'            => $sQry->value('text')
                              , 'value'           => $sQry->value('value')
                              , 'class'           => $sQry->value('class')
                              , 'sort_order'      => $sQry->value('sort_order')
                               ); 

        $tQry = $target_db->query('INSERT INTO :table_orders_total (orders_total_id, 
                                                                    orders_id, 
                                                                    title, 
                                                                    text, 
                                                                    value, 
                                                                    class, 
                                                                    sort_order) 
                                                            VALUES (:orders_total_id, 
                                                                    :orders_id, 
                                                                    :title, 
                                                                    :text, 
                                                                    :value, 
                                                                    :class, 
                                                                    :sort_order)');
        
        $tQry->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
        $tQry->bindInt  (':orders_total_id', $order_total['orders_total_id']);
        $tQry->bindInt  (':orders_id'      , $order_total['orders_id']);
        $tQry->bindValue(':title'          , $order_total['title']);
        $tQry->bindValue(':text'           , $order_total['text']);
        $tQry->bindValue(':value'          , $order_total['value']);
        $tQry->bindValue(':class'          , $orders_total_map[$order_total['class']]);
        $tQry->bindInt  (':sort_order'     , $order_total['sort_order']);
        $tQry->execute();
        
        $cnt++;
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD ORDERS TOTAL FROM SOURCE DB

    $orders_total = null;
    
    // END LOAD ORDERS TOTAL TO TARGET DB
    
    // ##########

    // LOAD ORDERS PRODUCTS DOWNLOAD FROM SOURCE DB
    
    $map = $this->_data_mapping['orders_products_download'];
    $orders_products_download = array();

    $sQry = $source_db->query('SELECT * FROM orders_products_download');
    $sQry->execute();
      
    $numrows= $sQry->numberOfRows();
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $orders_product_download  = array(
                                            'orders_products_download_id' => $sQry->value($map['orders_products_download_id'])
                                          , 'orders_id'                   => $sQry->value($map['orders_id'])
                                          , 'orders_products_id'          => $sQry->value($map['orders_products_id'])
                                          , 'orders_products_filename'    => $sQry->value($map['orders_products_filename'])
                                          , 'download_maxdays'            => $sQry->value($map['download_maxdays'])
                                          , 'download_count'              => $sQry->value($map['download_count'])
                                           ); 
                      
        $tQry = $target_db->query('INSERT INTO :table_orders_products_download (orders_products_download_id, 
                                                                                orders_id, 
                                                                                orders_products_id, 
                                                                                orders_products_filename, 
                                                                                download_maxdays, 
                                                                                download_count) 
                                                                        VALUES (:orders_products_download_id, 
                                                                                :orders_id, 
                                                                                :orders_products_id, 
                                                                                :orders_products_filename, 
                                                                                :download_maxdays, 
                                                                                :download_count)');

        $tQry->bindTable(':table_orders_products_download', TABLE_ORDERS_PRODUCTS_DOWNLOAD);
        $tQry->bindInt  (':orders_products_download_id', $orders_product_download['orders_products_download_id']);
        $tQry->bindInt  (':orders_id'                  , $orders_product_download['orders_id']);
        $tQry->bindInt  (':orders_products_id'         , $orders_product_download['orders_products_id']);
        $tQry->bindValue(':orders_products_filename'   , $orders_product_download['orders_products_filename']);
        $tQry->bindInt  (':download_maxdays'           , $orders_product_download['download_maxdays']);
        $tQry->bindInt  (':download_count'             , $orders_product_download['download_count']);
        $tQry->execute();

        $cnt++;
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD PRODUCTS DOWNLOAD FROM SOURCE DB

    $orders_products_download = null;
    
    // END LOAD ORDERS PRODUCTS DOWNLOAD TO TARGET DB
    
    // ##########

    // LOAD ORDERS FROM SOURCE DB

    $map = $this->_data_mapping['orders'];
    $orders = array();

    $sQry = $source_db->query('SELECT DISTINCT o.*, o.ipaddy as customers_ip_address, o.customers_address_format_id AS customers_address_format, o.billing_address_format_id AS billing_address_format, o.delivery_address_format_id AS delivery_address_format, c.countries_iso_code_2 AS customers_country_iso2, c.countries_iso_code_3 AS customers_country_iso3, z.zone_code AS customers_state_code, zz.zone_code AS delivery_state_code, cc.countries_iso_code_2 AS delivery_country_iso2, cc.countries_iso_code_3 AS delivery_country_iso3, zzz.zone_code AS billing_state_code, ccc.countries_iso_code_2 AS billing_country_iso2, ccc.countries_iso_code_3 AS billing_country_iso3 FROM orders o LEFT JOIN countries c ON o.customers_country = c.countries_name LEFT JOIN countries cc ON o.delivery_country = cc.countries_name LEFT JOIN countries ccc ON o.billing_country = ccc.countries_name LEFT JOIN zones z ON o.customers_state = z.zone_name LEFT JOIN zones zz ON o.delivery_state = zz.zone_name LEFT JOIN zones zzz ON o.billing_state = zzz.zone_name');
    $sQry->execute();
    
    if (!isset($pending_id) || $pending_id == null || $pending_id == '') $pending_id = 1;
      
    $numrows= $sQry->numberOfRows();
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $order = array(
                         'orders_id'                => $sQry->value($map['orders_id'])
                       , 'customers_id'             => $sQry->value($map['customers_id'])
                       , 'customers_name'           => $sQry->value($map['customers_name'])
                       , 'customers_company'        => $sQry->value($map['customers_company'])
                       , 'customers_street_address' => $sQry->value($map['customers_street_address'])
                       , 'customers_suburb'         => $sQry->value($map['customers_suburb'])
                       , 'customers_city'           => $sQry->value($map['customers_city'])
                       , 'customers_postcode'       => $sQry->value($map['customers_postcode'])
                       , 'customers_state'          => $sQry->value($map['customers_state'])
                       , 'customers_state_code'     => $sQry->value($map['customers_state_code'])
                       , 'customers_country'        => $sQry->value($map['customers_country'])
                       , 'customers_country_iso2'   => $sQry->value($map['customers_country_iso2'])
                       , 'customers_country_iso3'   => $sQry->value($map['customers_country_iso3']) 
                       , 'customers_telephone'      => $sQry->value($map['customers_telephone'])
                       , 'customers_email_address'  => $sQry->value($map['customers_email_address'])
                       , 'customers_address_format' => self::getAddressFormat($sQry->value($map['customers_country']))
                       , 'customers_ip_address'     => $sQry->value($map['customers_ip_address'])
                       , 'delivery_name'            => $sQry->value($map['delivery_name'])
                       , 'delivery_company'         => $sQry->value($map['delivery_company'])
                       , 'delivery_street_address'  => $sQry->value($map['delivery_street_address'])
                       , 'delivery_suburb'          => $sQry->value($map['delivery_suburb'])
                       , 'delivery_city'            => $sQry->value($map['delivery_city'])
                       , 'delivery_postcode'        => $sQry->value($map['delivery_postcode'])
                       , 'delivery_state'           => $sQry->value($map['delivery_state'])
                       , 'delivery_state_code'      => $sQry->value($map['delivery_state_code'])
                       , 'delivery_country'         => $sQry->value($map['delivery_country'])
                       , 'delivery_country_iso2'    => $sQry->value($map['delivery_country_iso2'])
                       , 'delivery_country_iso3'    => $sQry->value($map['delivery_country_iso3'])
                       , 'delivery_address_format'  => self::getAddressFormat($sQry->value($map['delivery_country']))
                       , 'billing_name'             => $sQry->value($map['billing_name'])
                       , 'billing_company'          => $sQry->value($map['billing_company'])
                       , 'billing_street_address'   => $sQry->value($map['billing_street_address'])
                       , 'billing_suburb'           => $sQry->value($map['billing_suburb'])
                       , 'billing_city'             => $sQry->value($map['billing_city'])
                       , 'billing_postcode'         => $sQry->value($map['billing_postcode'])
                       , 'billing_state'            => $sQry->value($map['billing_state'])
                       , 'billing_state_code'       => $sQry->value($map['billing_state_code'])
                       , 'billing_country'          => $sQry->value($map['billing_country'])
                       , 'billing_country_iso2'     => $sQry->value($map['billing_country_iso2'])
                       , 'billing_country_iso3'     => $sQry->value($map['billing_country_iso3'])
                       , 'billing_address_format'   => self::getAddressFormat($sQry->value($map['billing_country']))
                       , 'payment_method'           => $sQry->value($map['payment_method'])
                       , 'payment_module'           => $sQry->value($map['payment_info'])
                       , 'last_modified'            => $sQry->value($map['last_modified'])
                       , 'date_purchased'           => $sQry->value($map['date_purchased'])
                       , 'orders_status'            => $sQry->value($map['orders_status'])
                       , 'orders_date_finished'     => $sQry->value($map['orders_date_finished'])
                       , 'currency'                 => $sQry->value($map['currency'])
                       , 'currency_value'           => $sQry->value($map['currency_value'])
                        ); 
                    
        $tQry = $target_db->query('INSERT INTO :table_orders (orders_id, 
                                                              customers_id, 
                                                              customers_name, 
                                                              customers_company, 
                                                              customers_street_address, 
                                                              customers_suburb, 
                                                              customers_city, 
                                                              customers_postcode, 
                                                              customers_state, 
                                                              customers_state_code, 
                                                              customers_country, 
                                                              customers_country_iso2, 
                                                              customers_country_iso3, 
                                                              customers_telephone, 
                                                              customers_email_address, 
                                                              customers_address_format, 
                                                              customers_ip_address, 
                                                              delivery_name, 
                                                              delivery_company, 
                                                              delivery_street_address, 
                                                              delivery_suburb, 
                                                              delivery_city, 
                                                              delivery_postcode, 
                                                              delivery_state, 
                                                              delivery_state_code, 
                                                              delivery_country, 
                                                              delivery_country_iso2, 
                                                              delivery_country_iso3, 
                                                              delivery_address_format, 
                                                              billing_name, 
                                                              billing_company, 
                                                              billing_street_address, 
                                                              billing_suburb, 
                                                              billing_city, 
                                                              billing_postcode, 
                                                              billing_state, 
                                                              billing_state_code, 
                                                              billing_country, 
                                                              billing_country_iso2, 
                                                              billing_country_iso3, 
                                                              billing_address_format, 
                                                              payment_method, 
                                                              payment_module, 
                                                              last_modified, 
                                                              date_purchased, 
                                                              orders_status, 
                                                              orders_date_finished, 
                                                              currency, 
                                                              currency_value) 
                                                      VALUES (:orders_id, 
                                                              :customers_id, 
                                                              :customers_name, 
                                                              :customers_company, 
                                                              :customers_street_address, 
                                                              :customers_suburb, 
                                                              :customers_city, 
                                                              :customers_postcode, 
                                                              :customers_state, 
                                                              :customers_state_code, 
                                                              :customers_country, 
                                                              :customers_country_iso2, 
                                                              :customers_country_iso3, 
                                                              :customers_telephone, 
                                                              :customers_email_address, 
                                                              :customers_address_format, 
                                                              :customers_ip_address, 
                                                              :delivery_name, 
                                                              :delivery_company, 
                                                              :delivery_street_address, 
                                                              :delivery_suburb, 
                                                              :delivery_city, 
                                                              :delivery_postcode, 
                                                              :delivery_state, 
                                                              :delivery_state_code, 
                                                              :delivery_country, 
                                                              :delivery_country_iso2, 
                                                              :delivery_country_iso3, 
                                                              :delivery_address_format, 
                                                              :billing_name, 
                                                              :billing_company, 
                                                              :billing_street_address, 
                                                              :billing_suburb, 
                                                              :billing_city, 
                                                              :billing_postcode, 
                                                              :billing_state, 
                                                              :billing_state_code, 
                                                              :billing_country, 
                                                              :billing_country_iso2, 
                                                              :billing_country_iso3, 
                                                              :billing_address_format, 
                                                              :payment_method, 
                                                              :payment_module, 
                                                              :last_modified, 
                                                              :date_purchased, 
                                                              :orders_status, 
                                                              :orders_date_finished, 
                                                              :currency, :currency_value)');
        
        $tQry->bindTable(':table_orders', TABLE_ORDERS);
        $tQry->bindInt  (':orders_id'               , $order['orders_id']);
        $tQry->bindInt  (':customers_id'            , $order['customers_id']);
        $tQry->bindValue(':customers_name'          , $order['customers_name']);
        $tQry->bindValue(':customers_company'       , $order['customers_company']);
        $tQry->bindValue(':customers_street_address', $order['customers_street_address']);
        $tQry->bindValue(':customers_suburb'        , $order['customers_suburb']);
        $tQry->bindValue(':customers_city'          , $order['customers_city']);
        $tQry->bindValue(':customers_postcode'      , $order['customers_postcode']);
        $tQry->bindValue(':customers_state'         , $order['customers_state']);
        $tQry->bindValue(':customers_state_code'    , $order['customers_state_code']);
        $tQry->bindValue(':customers_country'       , $order['customers_country']);
        $tQry->bindValue(':customers_country_iso2'  , $order['customers_country_iso2']);
        $tQry->bindValue(':customers_country_iso3'  , $order['customers_country_iso3']);
        $tQry->bindValue(':customers_telephone'     , $order['customers_telephone']);
        $tQry->bindValue(':customers_email_address' , $order['customers_email_address']);
        $tQry->bindValue(':customers_address_format', $order['customers_address_format']);
        $tQry->bindValue(':customers_ip_address'    , $order['customers_ip_address']);
        $tQry->bindValue(':delivery_name'           , $order['delivery_name']);
        $tQry->bindValue(':delivery_company'        , $order['delivery_company']);
        $tQry->bindValue(':delivery_street_address' , $order['delivery_street_address']);
        $tQry->bindValue(':delivery_suburb'         , $order['delivery_suburb']);
        $tQry->bindValue(':delivery_city'           , $order['delivery_city']);
        $tQry->bindValue(':delivery_postcode'       , $order['delivery_postcode']);
        $tQry->bindValue(':delivery_state'          , $order['delivery_state']);
        $tQry->bindValue(':delivery_state_code'     , $order['delivery_state_code']);
        $tQry->bindValue(':delivery_country'        , $order['delivery_country']);
        $tQry->bindValue(':delivery_country_iso2'   , $order['delivery_country_iso2']);
        $tQry->bindValue(':delivery_country_iso3'   , $order['delivery_country_iso3']);
        $tQry->bindValue(':delivery_address_format' , $order['delivery_address_format']);
        $tQry->bindValue(':billing_name'            , $order['billing_name']);
        $tQry->bindValue(':billing_company'         , $order['billing_company']);
        $tQry->bindValue(':billing_street_address'  , $order['billing_street_address']);
        $tQry->bindValue(':billing_suburb'          , $order['billing_suburb']);
        $tQry->bindValue(':billing_city'            , $order['billing_city']);
        $tQry->bindValue(':billing_postcode'        , $order['billing_postcode']);
        $tQry->bindValue(':billing_state'           , $order['billing_state']);
        $tQry->bindValue(':billing_state_code'      , $order['billing_state_code']);
        $tQry->bindValue(':billing_country'         , $order['billing_country']);
        $tQry->bindValue(':billing_country_iso2'    , $order['billing_country_iso2']);
        $tQry->bindValue(':billing_country_iso3'    , $order['billing_country_iso3']);
        $tQry->bindValue(':billing_address_format'  , $order['billing_address_format']);
        $tQry->bindValue(':payment_method'          , $order['payment_method']);
        $tQry->bindValue(':payment_module'          , $order['payment_info']);
        $tQry->bindValue(':last_modified'           , $order['last_modified']);
        $tQry->bindValue(':date_purchased'          , $order['date_purchased']);
        $tQry->bindInt  (':orders_status'           , ($order['orders_status'] == 0) ? $pending_id : $order['orders_status']);
        $tQry->bindValue(':orders_date_finished'    , ($order['orders_date_finished'] != NULL) ? $order['orders_date_finished'] : '0000-00-00 00:00:00');
        $tQry->bindValue(':currency'                , $order['currency']);
        $tQry->bindFloat(':currency_value'          , $order['currency_value']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        $cnt++;
        
        // added to check for orders status id of 0 and adjust new db values to avoid errors
        if ($order['orders_status'] == 0) {
          $osQry = $target_db->query("SELECT * FROM :table_orders_status_history WHERE orders_id = " . $order['orders_id']);
          $osQry->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
          $osQry->execute();
          
          $numrows = $osQry->numberOfRows();
          if ($numrows < 1) {
            $osQry = $target_db->query("INSERT INTO :table_orders_status_history (orders_id, orders_status_id) VALUES (:orders_id, :orders_status_id)");
            $osQry->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
            $osQry->bindInt  (':orders_id'                  , $order['orders_id']);
            $osQry->bindInt  (':orders_status_id'           , $pending_id);
            $osQry->execute();
            
            if ($target_db->isError()) {
              $this->_msg = $target_db->getError();
              return false;
            }
          }
        }

      }
      
      $sQry->freeResult();
    }
    
    // END LOAD ORDERS FROM SOURCE DB

    $order = null;
    
    // ##########

    // LOAD ORDERS PRODUCTS FROM SOURCE DB
    
    $map = $this->_data_mapping['orders_products'];
    $orders_products = array();

    $sQry = $source_db->query('SELECT o.orders_products_id, o.orders_id, o.products_id, o.products_model, o.products_name, o.products_price, o.products_tax, o.products_quantity FROM orders_products AS o');
    $sQry->execute();
      
    $numrows= $sQry->numberOfRows();
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $orders_products_id = $sQry->value($map['orders_products_id']);
        
        // ADDED FOR ORDERS SIMPLE OPTIONS META DATA FIX
        
        $products_simple_options_meta_data = array();
        
        $mdQry = $source_db->query('SELECT * FROM orders_products_attributes WHERE orders_products_id = :orders_products_id');
        $mdQry->bindInt(':orders_products_id', $orders_products_id);
        $mdQry->execute();
        
        if ($mdQry->numberOfRows() > 0) { 
          while ($mdQry->next()) {            
            $vQry = $source_db->query('SELECT products_options_values_id FROM products_options_values WHERE products_options_values_name LIKE :products_options_values_name LIMIT 1');
            $vQry->bindValue(':products_options_values_name', '%' . $mdQry->value('products_options') . '%');
            $vQry->execute();
            
            $oQry = $source_db->query('SELECT products_options_text_id FROM products_options_text WHERE products_options_name LIKE :products_options_name LIMIT 1');
            $oQry->bindValue(':products_options_name', '%' . $mdQry->value('products_options') . '%');
            $oQry->execute();
            
            $modifier = ($mdQry->value('price_prefix') == '+' ? 1 : -1);
            
            $products_simple_options_meta_data[] = array('value_id' => $vQry->valueInt('products_options_values_id'),
                                                         'group_id' => $oQry->valueInt('products_options_text_id'),
                                                         'group_title' => $mdQry->value('products_options'),
                                                         'value_title' => $mdQry->value('products_options_values'),
                                                         'price_modifier' => (($mdQry->valueDecimal('options_values_price') * $modifier < 0) ? 0 : $mdQry->valueDecimal('options_values_price') * $modifier));
            
            
            
            
            $vQry->freeResult();
            $oQry->freeResult();
          }
        }
        
        // END ADDED FOR ORDERS SIMPLE OPTIONS META DATA FIX
                    
        $orders_product  = array(
                                   'orders_products_id'                => $orders_products_id
                                 , 'orders_id'                         => $sQry->value($map['orders_id'])
                                 , 'products_id'                       => $sQry->value($map['products_id'])
                                 , 'products_model'                    => $sQry->value($map['products_model'])
                                 , 'products_name'                     => $sQry->value($map['products_name'])
                                 , 'products_price'                    => $sQry->value($map['products_price'])
                                 , 'products_tax'                      => $sQry->value($map['products_tax'])
                                 , 'products_quantity'                 => $sQry->value($map['products_quantity'])
                                 , 'products_simple_options_meta_data' => serialize($products_simple_options_meta_data)                                            
                                  );        
        
        $mdQry->freeResult();                          
        $products_simple_options_meta_data = null;  

        $tQry = $target_db->query('INSERT INTO :table_orders_products (orders_products_id, 
                                                                       orders_id, 
                                                                       products_id, 
                                                                       products_model, 
                                                                       products_name, 
                                                                       products_price, 
                                                                       products_tax, 
                                                                       products_quantity, 
                                                                       products_simple_options_meta_data) 
                                                               VALUES (:orders_products_id, 
                                                                       :orders_id, 
                                                                       :products_id, 
                                                                       :products_model, 
                                                                       :products_name, 
                                                                       :products_price, 
                                                                       :products_tax, 
                                                                       :products_quantity, 
                                                                       :products_simple_options_meta_data)');
        
        $tQry->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
        $tQry->bindInt  (':orders_products_id'               , $orders_product['orders_products_id']);
        $tQry->bindInt  (':orders_id'                        , $orders_product['orders_id']);
        $tQry->bindInt  (':products_id'                      , $orders_product['products_id']);
        $tQry->bindValue(':products_model'                   , $orders_product['products_model']);
        $tQry->bindValue(':products_name'                    , $orders_product['products_name']);
        $tQry->bindValue(':products_price'                   , $orders_product['products_price']);
        $tQry->bindValue(':products_tax'                     , $orders_product['products_tax']);
        $tQry->bindValue(':products_quantity'                , $orders_product['products_quantity']);
        $tQry->bindValue(':products_simple_options_meta_data', $orders_product['products_simple_options_meta_data']);
        $tQry->execute();

        $cnt++;
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }

      }
      
      $sQry->freeResult();
    }

    // END LOAD ORDERS PRODUCTS FROM SOURCE DB
    
    $orders_product = null;
    
    // ##########

    // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    // ##########
      
    $source_db->disconnect();  
    $target_db->disconnect();  
      
    return true;
      
  } // end importOrders
  
  /*
  *  function name : importAttributes()
  *
  *  description : load products_variants_groups, products_variants_values, products_simple_option, products_simple_options_values from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importAttributes($switch = null) {
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
            
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
      
    require_once('../includes/database_tables.php');
                                                     
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
      
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB

    // ##########

    // TRUNCATE PRODUCT VARIANTS TABLES IN TARGET DB
    
    if ($switch != -1) {
      $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS_VARIANTS_GROUPS);
      $tQry->execute();
      
      $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS_VARIANTS_VALUES);
      $tQry->execute();
      
      $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS_SIMPLE_OPTIONS);
      $tQry->execute();
      
      $tQry = $target_db->query('truncate table ' . TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
      $tQry->execute();
    }
  
    // END TRUNCATE PRODUCT VARIANTS TABLES IN TARGET DB
    
    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    // $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    // $tQry->execute();

    // LOAD PRODUCTS VARIANTS GROUPS FROM SOURCE DB
    $map = $this->_data_mapping['products_variants_groups'];
    
    $sQry = $source_db->query('SELECT * FROM products_options_text WHERE language_id = :language_id');
    $sQry->bindInt(':language_id', $this->_languages_id_default);
    $sQry->execute();
    
    if ($sQry->numberOfRows() > 0) { 
      while ($sQry->next()) {
        // From 6.X option types ////////////////////////////////
        // if ($opt_type == 0) return 'Select';
        // if ($opt_type == 1) return 'Text';
        // if ($opt_type == 2) return 'Radio';
        // if ($opt_type == 3) return 'Checkbox';
        // if ($opt_type == 4) return 'Text Area';
        // if ($opt_type == 5) return 'File Upload';
        /////////////////////////////////////////////////////////
        $otQry = $source_db->query('SELECT products_options_sort_order, options_type FROM products_options WHERE products_options_id = :products_options_id');
        $otQry->bindInt(':products_options_id', $sQry->value('products_options_text_id'));
        $otQry->execute();
        
        $asoQry = $source_db->query('SELECT products_id, products_options_sort_order FROM products_attributes WHERE options_id = :options_id');
        $asoQry->bindInt(':options_id', $sQry->value('products_options_text_id'));
        $asoQry->execute();
        
        if ($otQry->value('options_type') == '1') {
          $module = 'text_field'; 
        } else if ($otQry->value('options_type') == '2') { 
          $module = 'radio_buttons';
        } else if ($otQry->value('options_type') == '3') { 
          // $module = 'check_box'; // back to check_box once loaded7 supports it
          $module = 'pull_down_menu';
        } else if ($otQry->value('options_type') == '4') {
          // $module = 'text_area'; // back to text_area once loaded7 supports it 
          $module = 'text_field';
        // no support for file upload yet even for conversion
        // } else if ($otQry->value('options_type') == '5') { 
          // $module = 'file_upload'; // back to check_box once loaded7 supports it
        } else {
          $module = 'pull_down_menu';
        }
        
        $otQry->freeResult();
        $asoQry->freeResult();
        
        $group = array(
                         'id'           => $sQry->value('products_options_text_id')
                       , 'languages_id' => $sQry->value('language_id')
                       , 'title'        => $sQry->value('products_options_name')
                       , 'sort_order'   => $otQry->value('products_options_sort_order')
                       , 'module'       => $module
                        ); 
                                 
        $tQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_products_variants_groups (id, 
                                                                                                                           languages_id, 
                                                                                                                           title, 
                                                                                                                           sort_order, 
                                                                                                                           module) 
                                                                                                                   VALUES (:id, 
                                                                                                                           :languages_id, 
                                                                                                                           :title, 
                                                                                                                           :sort_order, 
                                                                                                                           :module) 
                                                                                              ON DUPLICATE KEY UPDATE id = :update_id');

        $tQry->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
        $tQry->bindInt  (':id'          , $group['id']);
        $tQry->bindInt  (':languages_id', $group['languages_id']);
        $tQry->bindValue(':title'       , $group['title']);
        $tQry->bindInt  (':sort_order'  , $group['sort_order']);
        $tQry->bindValue(':module'      , $group['module']);
        $tQry->bindInt  (':update_id'   , $group['id']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        $tQry->freeResult();
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD PRODUCTS VARIANTS GROUPS FROM SOURCE DB

    // ##########

    // LOAD PRODUCTS VARIANTS VALUES FROM SOURCE DB
    $map = $this->_data_mapping['products_variants_values'];
    
    $sQry = $source_db->query('SELECT pov.*, povp.* FROM products_options_values pov LEFT JOIN products_options_values_to_products_options povp ON ( pov.products_options_values_id = povp.products_options_values_id ) WHERE language_id = :language_id');
    $sQry->bindInt(':language_id', $this->_languages_id_default);
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      while ($sQry->next()) {
        $group  = array(
                          'id'                          => $sQry->value('products_options_values_id')
                        , 'languages_id'                => $sQry->value($map['languages_id'])
                        , 'products_variants_groups_id' => $sQry->value('products_options_id')
                        , 'title'                       => $sQry->value('products_options_values_name')
                        , 'sort_order'                  => 0
                         ); 
                         
        $tQry = $target_db->query('INSERT INTO :table_products_variants_values (id, 
                                                                                languages_id, 
                                                                                products_variants_groups_id, 
                                                                                title, 
                                                                                sort_order) 
                                                                        VALUES (:id,
                                                                                :languages_id, 
                                                                                :products_variants_groups_id, 
                                                                                :title, 
                                                                                :sort_order) 
                                                   ON DUPLICATE KEY UPDATE id = :update_id');

        $tQry->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
        
        $tQry->bindInt  (':id'                         , $group['id']);
        $tQry->bindInt  (':languages_id'               , $group['languages_id']);
        $tQry->bindInt  (':products_variants_groups_id', $group['products_variants_groups_id']);
        $tQry->bindValue(':title'                      , $group['title']);
        $tQry->bindInt  (':sort_order'                 , $group['sort_order']);
        $tQry->bindInt  (':update_id'                  , $group['id']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        $tQry->freeResult();
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD PRODUCTS VARIANTS VALUES FROM SOURCE DB 
    
    // ##########      

    // LOAD PRODUCTS SIMPLE OPTIONS AND PRODUCTS SIMPLE OPTIONS VALUES FROM SOURCE DB
    $map_o = $this->_data_mapping['products_simple_options'];
    $map_v = $this->_data_mapping['products_simple_options_values'];
    $simple_options = array();
    $simple_values = array();

    $sQry = $source_db->query('SELECT * FROM products_attributes order by options_id asc');
    $sQry->execute();
      
    // get the lowest customers group id from the target db
    $cgQry = $target_db->query('SELECT MIN(customers_group_id) AS customers_group_id FROM :table_customers_groups');
    $cgQry->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
    $cgQry->execute();
    
    $customers_group_id = ($cgQry->numberOfRows() > 0) ? $cgQry->valueInt('customers_group_id') : 0;
      
    if ($sQry->numberOfRows() > 0) { 
      while ($sQry->next()) {        
        if ($sQry->valueInt('products_id') == 0) continue;
      
        $option  = array(
                           'id'          => ''
                         , 'options_id'  => $sQry->valueInt('options_id')
                         , 'products_id' => $sQry->valueInt('products_id')
                         , 'sort_order'  => $sQry->valueInt('sort_order')
                         , 'status'      => 1
                          ); 
                         
        $simple_options[] = $option;

        $prefix = ( $sQry->value('price_prefix') == '+' ? 1 : -1 );
        
        $value  = array(
                          'id'                 => ''
                        , 'products_id'        => $sQry->valueInt('products_id')
                        , 'customers_group_id' => $customers_group_id
                        , 'values_id'          => $sQry->valueInt('options_values_id')
                        , 'options_id'         => $sQry->valueInt('options_id')
                        , 'price_modifier'     => $sQry->valueDecimal('options_values_price') * $prefix
                         ); 
                         
        $simple_values[] = $value;
      }
      
      $sQry->freeResult();
      $cgQry->freeResult();
    }
    
    // LOAD PRODUCTS SIMPLE OPTIONS AND PRODUCTS SIMPLE OPTIONS VALUES FROM SOURCE DB
    
    // LOAD PRODUCTS SIMPLE OPTIONS TO TARGET DB
    
    foreach ($simple_options as $option) {
      $Qchk = $target_db->query('SELECT id from :table_products_simple_options WHERE options_id = :options_id and products_id = :products_id limit 1');
      
      $Qchk->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
      
      $Qchk->bindInt  (':options_id' , $option['options_id']);
      $Qchk->bindInt  (':products_id', $option['products_id']);
      $Qchk->execute();        
      
      if ($Qchk->numberOfRows() == 0) {
        $tQry = $target_db->query('INSERT INTO :table_products_simple_options (id, 
                                                                               options_id, 
                                                                               products_id, 
                                                                               sort_order, 
                                                                               status) 
                                                                       VALUES (:id, 
                                                                               :options_id, 
                                                                               :products_id, 
                                                                               :sort_order, 
                                                                               :status)');

        $tQry->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
        
        $tQry->bindInt  (':id'         , $option['id']);
        $tQry->bindInt  (':options_id' , $option['options_id']);
        $tQry->bindInt  (':products_id', $option['products_id']);
        $tQry->bindInt  (':sort_order' , $option['sort_order']);
        $tQry->bindInt  (':status'     , $option['status']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
      }
      
      $Qchk->freeResult();
    }

    // END LOAD PRODUCTS SIMPLE OPTIONS TO TARGET DB
    
    // LOAD PRODUCTS SIMPLE OPTIONS VALUES TO TARGET DB
      
    foreach ($simple_values as $value) {
      $Qchk = $target_db->query('SELECT id from :table_products_simple_options_values WHERE products_id = :products_id and options_id = :options_id and values_id = :values_id limit 1');
      $Qchk->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
      $Qchk->bindInt  (':products_id' , $value['products_id']);
      $Qchk->bindInt  (':options_id' , $value['options_id']);
      $Qchk->bindInt  (':values_id', $value['values_id']);
      $Qchk->execute();
      
      if ($Qchk->numberOfRows() == 0) {
        // get products_id from products_simple_options
        /*$Qso = $target_db->query('SELECT products_id from :table_products_simple_options WHERE options_id = :options_id and products_id = :products_id limit 1');
        $Qso->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
        $Qso->bindInt  (':options_id' , $option['options_id']);
        $Qso->bindInt  (':products_id', $option['products_id']);
        $Qso->execute();*/
        
        $tQry = $target_db->query('INSERT INTO :table_products_simple_options_values (products_id,
                                                                                      customers_group_id, 
                                                                                      values_id, 
                                                                                      options_id, 
                                                                                      price_modifier) 
                                                                              VALUES (:products_id,
                                                                                      :customers_group_id, 
                                                                                      :values_id, 
                                                                                      :options_id, 
                                                                                      :price_modifier)');

        $tQry->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
        
        $tQry->bindInt  (':products_id'       , $value['products_id']);
        $tQry->bindInt  (':customers_group_id', $value['customers_group_id']);
        $tQry->bindInt  (':values_id'         , $value['values_id']);
        $tQry->bindInt  (':options_id'        , $value['options_id']);
        $tQry->bindFloat(':price_modifier'    , ($value['price_modifier'] == -0) ? 0 : $value['price_modifier']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
      }

      $Qchk->freeResult();
    }
    
    // END LOAD PRODUCTS SIMPLE OPTIONS VALUES TO TARGET DB
    
    // Added for text_field options
    $stfQry = $source_db->query('SELECT products_options_sort_order FROM products_options WHERE options_type = :options_type');
    $stfQry->bindInt(':options_type', 4);
    $stfQry->execute();
    
    if ($stfQry->numberOfRows() > 0) {      
      while ($stfQry->next()) { 
        $stftQry = $source_db->query('SELECT products_options_name FROM products_options_text WHERE products_options_text_id = :products_options_text_id AND language_id = :language_id');
        $stftQry->bindInt(':products_options_text_id', 4);
        $stftQry->bindInt(':language_id', $this->_languages_id_default);
        $stftQry->execute();
        
        while ($stftQry->next()) {
          $ttfQry = $target_db->query('INSERT INTO :table_products_variants_values (languages_id,
                                                                                    products_variants_groups_id, 
                                                                                    title, 
                                                                                    sort_order) 
                                                                            VALUES (:languages_id,
                                                                                    :products_variants_groups_id, 
                                                                                    :title, 
                                                                                    :sort_order)');          
          
          $ttfQry->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
          
          $ttfQry->bindInt  (':languages_id'               , $this->_languages_id_default);
          $ttfQry->bindInt  (':products_variants_groups_id', 4);
          $ttfQry->bindValue(':title'                      , $stftQry->value('products_options_name'));
          $ttfQry->bindInt  (':sort_order'                 , $stfQry->value('products_options_sort_order'));
          $ttfQry->execute();
        
          $text_field_groups_id = $target_db->nextID();
        
          if ($target_db->isError()) {
            $this->_msg = $target_db->getError();
            return false;
          }
        }

        $stftQry->freeResult();
      }
    }

    $stfQry->freeResult();
    
    $ttfsovQry = $target_db->query('UPDATE :table_products_simple_options_values SET values_id = :values_id WHERE options_id = :options_id');
    
    $ttfsovQry->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
          
    $ttfsovQry->bindInt(':values_id', $text_field_groups_id);
    $ttfsovQry->bindInt(':options_id', 4);
    $ttfsovQry->execute();
        
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }
    
    // added for removing duplicate simple options values entries
    $clean = $target_db->query('ALTER IGNORE TABLE :table_products_simple_options ADD UNIQUE INDEX (options_id, products_id)');
    $clean->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
    $clean->execute();
        
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }    
          
    // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    // $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    // $tQry->execute();

    // ##########

    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importAttributes

  
  /*
  *  function name : importAdministrators()
  *
  *  description : load administrators and administrators_groups data from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importAdministrators($switch = null) {
    
      $s_db = $this->_sDB;
      $t_db = $this->_tDB;
              
      if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

      // CONNNECT TO SOURCE DB
        
      require_once('../includes/database_tables.php');
                                                       
      require_once('../includes/classes/database/mysqli.php');
      $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
      $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
        
      if ($source_db->isError() === false) {
        $source_db->selectDatabase($s_db['DB_DATABASE']);
      }
        
      if ($source_db->isError()) {
        $this->_msg = $source_db->getError();
        return false;
      }
      // END CONNNECT TO SOURCE DB
        
      // CONNNECT TO TARGET DB

      $class = 'lC_Database_' . $t_db['DB_CLASS'];
      $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
        
      if ($target_db->isError() === false) {
        $target_db->selectDatabase($t_db['DB_DATABASE']);
      }
        
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }

      // END CONNNECT TO TARGET DB
      
      // TRUNCATE ADMINS TABLE IN TARGET DB
      
      if ($switch != -1) {
        $tQry = $target_db->query('truncate table ' . TABLE_ADMINISTRATORS);
        $tQry->execute();
      }
      
      // END TRUNCATE ADMINS TABLE IN TARGET DB

      // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
      $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
      $tQry->execute();

      // LOAD ADMINS FROM SOURCE DB
      $map = $this->_data_mapping['administrators'];
      $admins_groups = array();

      $sQry = $source_db->query('SELECT * FROM admin');
      $sQry->execute();
        
      if ($sQry->numberOfRows() > 0) { 
        $cnt = 0;
        while ($sQry->next()) {
          $admin  = array(
                            'id'             => $sQry->value($map['id'])
                          , 'user_name'      => $sQry->value($map['user_name'])
                          , 'user_password'  => $sQry->value($map['user_password'])
                          , 'first_name'     => $sQry->value($map['first_name'])
                          , 'last_name'      => $sQry->value($map['last_name'])
                          , 'image'          => $sQry->value($map['image'])
                          , 'access_goup_id' => $sQry->value($map['access_goup_id'])
                           ); 
                           
          $tQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_admin (id, 
                                                                                                          user_name, 
                                                                                                          user_password, 
                                                                                                          first_name, 
                                                                                                          last_name, 
                                                                                                          image, 
                                                                                                          access_group_id) 
                                                                                                  VALUES (:id, 
                                                                                                          :user_name, 
                                                                                                          :user_password, 
                                                                                                          :first_name, 
                                                                                                          :last_name, 
                                                                                                          :image, 
                                                                                                          :access_group_id)');
          
          $tQry->bindTable(':table_admin', TABLE_ADMINISTRATORS);
          $tQry->bindInt  (':id'             , $admin['id']);
          $tQry->bindValue(':user_name'      , $admin['user_name']);
          $tQry->bindValue(':user_password'  , $admin['user_password']);
          $tQry->bindValue(':first_name'     , $admin['first_name']);
          $tQry->bindValue(':last_name'      , $admin['last_name']);
          $tQry->bindValue(':image'          , $admin['image']);
          $tQry->bindInt  (':access_group_id', 1);
          $tQry->execute();
          
          if ($target_db->isError()) {
            $this->_msg = $target_db->getError();
            return false;
          }

          $cnt++;
        }
        
        $sQry->freeResult();
      }
      
      // END LOAD ADMINS FROM SOURCE DB

      // LOAD ADMIN GROUPS FROM SOURCE DB
      
      if ($switch != -1) {
        $tQry = $target_db->query('truncate table ' . TABLE_ADMINISTRATORS_GROUPS);
        $tQry->execute();
      }

      $sQry = $source_db->query('SELECT * FROM admin_groups');
      $sQry->execute();
        
      if ($sQry->numberOfRows() > 0) { 
        $cnt = 0;
        while ($sQry->next()) {
        
          $admin_group  = array(
                                  'id'            => $sQry->value('admin_groups_id')
                                , 'name'          => $sQry->value('admin_groups_name')
                                , 'date_added'    => "0000-00-00 00:00:00"
                                , 'last_modified' => "0000-00-00 00:00:00"
                                 ); 
          
          $tQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_admins_groups (id, 
                                                                                                                  name, 
                                                                                                                  date_added, 
                                                                                                                  last_modified) 
                                                                                                          VALUES (:id, 
                                                                                                                  :name, 
                                                                                                                  now(), 
                                                                                                                  now())');
          
          $tQry->bindTable(':table_admins_groups', TABLE_ADMINISTRATORS_GROUPS);
          $tQry->bindInt  (':id'  , $admin_group['id']);
          $tQry->bindValue(':name', $admin_group['name']);
          $tQry->execute();
          
          if ($target_db->isError()) {
            $this->_msg = $target_db->getError();
            return false;
          }
          
          $cnt++;
        }
        
        $sQry->freeResult();
      }
      
      // END LOAD ADMIN GROUPS FROM SOURCE DB

      // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
      $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
      $tQry->execute();
      
      $source_db->disconnect();  
      $target_db->disconnect();  
      
      return true;
        
    } // end importAdministrators
  
  /*
  *  function name : importNewsletters()
  *
  *  description : load newsletters data from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importNewsletter($switch = null) {
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
    $map = $this->_data_mapping['newsletters'];
            
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
      
    require_once('../includes/database_tables.php');
                                                     
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
      
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB

    // TRUNCATE NEWSLETTER TABLE IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . TABLE_NEWSLETTERS);
    $tQry->execute();
    
    // END TRUNCATE NEWSLETTER TABLE IN TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD NEWSLETTERS FROM SOURCE DB
    $newsletters = array();

    $sQry = $source_db->query('select * from newsletters');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $nl  = array(
                       'newsletters_id' => $sQry->value($map['newsletters_id'])
                     , 'title'          => $sQry->value($map['title'])
                     , 'content'        => $sQry->value($map['content'])
                     , 'module'         => $sQry->value($map['module'])
                     , 'date_added'     => $sQry->value($map['date_added'])
                     , 'date_sent'      => $sQry->value($map['date_sent'])
                     , 'status'         => $sQry->value($map['status'])
                     , 'locked'         => $sQry->value($map['locked'])
                      ); 
                         
        $newsletters[] = $nl;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD NEWSLETTERS FROM SOURCE DB

    // LOAD NEWSLETTERS TO TARGET DB
      
    $iCnt = 0;
    foreach ($newsletters as $nl) {
      
      $tQry = $target_db->query('INSERT INTO :table_newsletters (newsletters_id, 
                                                                 title, 
                                                                 content, 
                                                                 module, 
                                                                 date_added, 
                                                                 date_sent, 
                                                                 status, 
                                                                 locked) 
                                                         VALUES (:newsletters_id, 
                                                                 :title, 
                                                                 :content, 
                                                                 :module, 
                                                                 :date_added, 
                                                                 :date_sent, 
                                                                 :status, 
                                                                 :locked)');

      $tQry->bindTable(':table_newsletters', TABLE_NEWSLETTERS);
      $tQry->bindInt  (':newsletters_id', $nl['newsletters_id']);
      $tQry->bindValue(':title'         , $nl['title']);
      $tQry->bindValue(':content'       , $nl['content']);
      $tQry->bindValue(':module'        , $nl['module']);
      $tQry->bindDate (':date_added'    , $nl['date_added']);
      $tQry->bindDate (':date_sent'     , $nl['date_sent']);
      $tQry->bindInt  (':status'        , $nl['status']);
      $tQry->bindInt  (':locked'        , $nl['locked']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD NEWSLETTERS TO TARGET DB

    // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importNewsletters
  
  /*
  *  function name : importBanners()
  *
  *  description : load banners data from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importBanners($switch = null) {
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
            
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
      
    require_once('../includes/database_tables.php');
                       
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
      
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB
    
    // TRUNCATE BANNERS TABLE IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . TABLE_BANNERS);
    $tQry->execute();

    $tQry = $target_db->query('truncate table ' . TABLE_BANNERS_HISTORY);
    $tQry->execute();
    
    // END TRUNCATE BANNERS TABLE IN TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD BANNERS FROM SOURCE DB
    $map = $this->_data_mapping['banners'];
    $banners = array();

    $sQry = $source_db->query('SELECT * FROM banners');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $banner  = array(
                           'banners_id'          => $sQry->value($map['banners_id'])
                         , 'banners_title'       => $sQry->value($map['banners_title'])
                         , 'banners_url'         => $sQry->value($map['banners_url'])
                         , 'banners_target'      => $sQry->value($map['banners_target'])
                         , 'banners_image'       => $sQry->value($map['banners_image'])
                         , 'banners_group'       => $sQry->value($map['banners_group'])
                         , 'banners_html_text'   => $sQry->value($map['banners_html_text'])
                         , 'expires_impressions' => $sQry->value($map['expires_impressions'])
                         , 'expires_date'        => $sQry->value($map['expires_date'])
                         , 'date_scheduled'      => $sQry->value($map['date_scheduled'])
                         , 'date_added'          => $sQry->value($map['date_added'])
                         , 'date_status_change'  => $sQry->value($map['date_status_change'])
                         , 'status'              => $sQry->value($map['status'])
                          ); 
                         
        $banners[] = $banner;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD BANNERS FROM SOURCE DB

    // LOAD BANNERS TO TARGET DB
      
    $iCnt = 0;
    foreach ($banners as $banner) {
      
      $tQry = $target_db->query('INSERT INTO :table_banners (banners_id, 
                                                             banners_title, 
                                                             banners_url, 
                                                             banners_target, 
                                                             banners_image, 
                                                             banners_group, 
                                                             banners_html_text, 
                                                             expires_impressions, 
                                                             expires_date, 
                                                             date_scheduled, 
                                                             date_added, 
                                                             date_status_change, 
                                                             status) 
                                                     VALUES (:banners_id, 
                                                             :banners_title, 
                                                             :banners_url, 
                                                             :banners_target, 
                                                             :banners_image, 
                                                             :banners_group, 
                                                             :banners_html_text, 
                                                             :expires_impressions, 
                                                             :expires_date, 
                                                             :date_scheduled, 
                                                             :date_added, 
                                                             :date_status_change, 
                                                             :status)');

      $tQry->bindTable(':table_banners', TABLE_BANNERS);
      $tQry->bindInt  (':banners_id'         , $banner['banners_id']);
      $tQry->bindValue(':banners_title'      , $banner['banners_title']);
      $tQry->bindValue(':banners_url'        , $banner['banners_url']);
      $tQry->bindInt  (':banners_target'     , 1);
      $tQry->bindValue(':banners_image'      , $banner['banners_image']);
      $tQry->bindValue(':banners_group'      , $banner['banners_group']);
      $tQry->bindValue(':banners_html_text'  , $banner['banners_html_text']);
      $tQry->bindInt  (':expires_impressions', $banner['expires_impressions']);
      $tQry->bindDate (':expires_date'       , $banner['expires_date']);
      $tQry->bindDate (':date_scheduled'     , $banner['date_scheduled']);
      $tQry->bindDate (':date_added'         , $banner['date_added']);
      $tQry->bindDate (':date_status_change' , $banner['date_status_change']);
      $tQry->bindInt  (':status'             , $banner['status']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD BANNERS TO TARGET DB

    // LOAD BANNERS HISTORY FROM SOURCE DB
    $map = $this->_data_mapping['banners_history'];
    $banners = array();

    $sQry = $source_db->query('SELECT * FROM banners_history');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $group  = array(
                          'banners_history_id'   => $sQry->value($map['banners_history_id'])
                        , 'banners_id'           => $sQry->value($map['banners_id'])
                        , 'banners_shown'        => $sQry->value($map['banners_shown'])
                        , 'banners_clicked'      => $sQry->value($map['banners_clicked'])
                        , 'banners_history_date' => $sQry->value($map['banners_history_date'])
                         ); 
                         
        $banners[] = $group;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD BANNERS HISTORY FROM SOURCE DB
    
    // LOAD BANNERS HISTORY TO TARGET DB
      
    $iCnt = 0;
    foreach ($banners as $banner) {
      
      $tQry = $target_db->query('INSERT INTO :table_banners_history (banners_history_id, 
                                                                     banners_id, 
                                                                     banners_shown, 
                                                                     banners_clicked, 
                                                                     banners_history_date) 
                                                             VALUES (:banners_history_id, 
                                                                     :banners_id, 
                                                                     :banners_shown, 
                                                                     :banners_clicked, 
                                                                     :banners_history_date)');

      $tQry->bindTable(':table_banners_history', TABLE_BANNERS_HISTORY);
      $tQry->bindInt  (':banners_history_id'  , $banner['banners_history_id']);
      $tQry->bindInt  (':banners_id'          , $banner['banners_id']);
      $tQry->bindInt  (':banners_shown'       , $banner['banners_shown']);
      $tQry->bindInt  (':banners_clicked'     , $banner['banners_clicked']);
      $tQry->bindDate (':banners_history_date', $banner['banners_history_date']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD BANNERS HISTORY TO TARGET DB

    // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importBanners
  
  /*
  *  function name : importConfiguration()
  *
  *  description : load configuration data from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importConfiguration($switch = null) {
    
    return true; // temporarily disable this function until we decide to include configuration from older version of the cart 
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
    
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
      
    require_once('../includes/database_tables.php');
                                                     
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
      
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB
    
    // TRUNCATE CONFIG TABLE IN TARGET DB
    
    if ($switch != -1) {
      $tQry = $target_db->query('truncate table ' . TABLE_CONFIGURATION);
      $tQry->execute();

      $tQry = $target_db->query('truncate table ' . TABLE_CONFIGURATION_GROUP);
      $tQry->execute();
    }
                
    // END TRUNCATE CONFIG TABLE IN TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD CONFIGURATION FROM SOURCE DB
    $map = $this->_data_mapping['configuration'];
    $configurations = array();

    $sQry = $source_db->query('SELECT * FROM configuration');
    $sQry->execute();
    
    $numrows = $sQry->numberOfRows();
      
    if ($numrows > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $config  = array(
                           'configuration_id'          => $sQry->value($map['configuration_id'])
                         , 'configuration_title'       => $sQry->value($map['configuration_title'])
                         , 'configuration_key'         => $sQry->value($map['configuration_key'])
                         , 'configuration_value'       => $sQry->value($map['configuration_value'])
                         , 'configuration_description' => $sQry->value($map['configuration_description'])
                         , 'configuration_group_id'    => $sQry->value($map['configuration_group_id'])
                         , 'sort_order'                => $sQry->value($map['sort_order'])
                         , 'last_modified'             => $sQry->value($map['last_modified'])
                         , 'date_added'                => $sQry->value($map['date_added'])
                         , 'use_function'              => $sQry->value($map['use_function'])
                         , 'set_function'              => $sQry->value($map['set_function'])
                          ); 

        $tQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_configuration (configuration_id, 
                                                                                                                configuration_title, 
                                                                                                                configuration_key, 
                                                                                                                configuration_value, 
                                                                                                                configuration_description, 
                                                                                                                configuration_group_id, 
                                                                                                                sort_order, 
                                                                                                                last_modified, 
                                                                                                                date_added, 
                                                                                                                use_function, 
                                                                                                                set_function) 
                                                                                                        VALUES (:configuration_id, 
                                                                                                                :configuration_title, 
                                                                                                                :configuration_key, 
                                                                                                                :configuration_value, 
                                                                                                                :configuration_description, 
                                                                                                                :configuration_group_id, 
                                                                                                                :sort_order, 
                                                                                                                :last_modified, 
                                                                                                                :date_added, 
                                                                                                                :use_function, 
                                                                                                                :set_function)');
                                                                         
                                        
        $tQry->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $tQry->bindInt  (':configuration_id'         , $config['configuration_id']);
        $tQry->bindValue(':configuration_title'      , $config['configuration_title']);
        $tQry->bindValue(':configuration_key'        , $config['configuration_key']);
        $tQry->bindValue(':configuration_value'      , $config['configuration_value']);
        $tQry->bindValue(':configuration_description', $config['configuration_description']);
        $tQry->bindInt  (':configuration_group_id'   , $config['configuration_group_id']);
        $tQry->bindInt  (':sort_order'               , $config['sort_order']);
        $tQry->bindDate (':last_modified'            , $config['last_modified']);
        $tQry->bindDate (':date_added'               , $config['date_added']);
        $tQry->bindValue(':use_function'             , $config['use_function']);
        $tQry->bindValue(':set_function'             , $config['set_function']);
        $tQry->execute();
        
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD CONFIGURATION FROM SOURCE DB

    // LOAD CONFIGURATION GROUP FROM SOURCE DB
    
    $map = $this->_data_mapping['configuration_group'];
    $configuration_group = array();

    $sQry = $source_db->query('SELECT * FROM configuration_group');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $group  = array(
                          'configuration_group_id'          => $sQry->value($map['configuration_group_id'])
                        , 'configuration_group_title'       => $sQry->value($map['configuration_group_title'])
                        , 'configuration_group_description' => $sQry->value($map['configuration_group_description'])
                        , 'sort_order'                      => $sQry->value($map['sort_order'])
                        , 'visible'                         => $sQry->value($map['visible'])
                         ); 
                         
        $configuration_group[] = $group;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD CONFIGURATION GROUP FROM SOURCE DB

    // LOAD CONFIGURATION GROUP TO TARGET DB
    
    $iCnt = 0;
    foreach ($configuration_group as $group) {
      
      $tQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_configuration_group (configuration_group_id, 
                                                                                                                    configuration_group_title, 
                                                                                                                    configuration_group_description, 
                                                                                                                    sort_order, 
                                                                                                                    visible) 
                                                                                                            VALUES (:configuration_group_id, 
                                                                                                                    :configuration_group_title, 
                                                                                                                    :configuration_group_description, 
                                                                                                                    :sort_order, 
                                                                                                                    :visible)');

      $tQry->bindTable(':table_configuration_group', TABLE_CONFIGURATION_GROUP);
      $tQry->bindInt  (':configuration_group_id'         , $group['configuration_group_id']);
      $tQry->bindValue(':configuration_group_title'      , $group['configuration_group_title']);
      $tQry->bindValue(':configuration_group_description', $group['configuration_group_description']);
      $tQry->bindInt  (':sort_order'                     , $group['sort_order']);
      $tQry->bindInt  (':visible'                        , $group['visible']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD CONFIGURATION GROUP TO TARGET DB

    // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importConfiguration
  
  /*
  *  function name : importCoupons()
  *
  *  description : load coupons data from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importCoupons($switch = null) {
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
            
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
      
    require_once('../includes/database_tables.php');
                                                     
    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
      
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB
    
    // TRUNCATE COUPONS TABLE IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . DB_TABLE_PREFIX . 'coupons');
    $tQry->execute();

    $tQry = $target_db->query('truncate table ' . DB_TABLE_PREFIX . 'coupons_description');
    $tQry->execute();

    $tQry = $target_db->query('truncate table ' . DB_TABLE_PREFIX . 'coupons_redeemed');
    $tQry->execute();
    
    // END TRUNCATE COUPONS TABLE IN TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD COUPONS FROM SOURCE DB
    $map = $this->_data_mapping['coupons'];
    $coupons = array();

    $sQry = $source_db->query('SELECT * FROM coupons');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $group  = array(
                          'coupons_id'             => $sQry->value($map['coupons_id'])
                        , 'type'                   => $sQry->value($map['type'])
                        , 'mode'                   => $sQry->value($map['mode'])
                        , 'code'                   => $sQry->value($map['code'])
                        , 'reward'                 => $sQry->value($map['reward'])
                        , 'purchase_over'          => $sQry->value($map['purchase_over'])
                        , 'start_date'             => $sQry->value($map['start_date'])
                        , 'expires_date'           => $sQry->value($map['expires_date'])
                        , 'uses_per_coupon'        => $sQry->value($map['uses_per_coupon'])
                        , 'uses_per_customer'      => $sQry->value($map['uses_per_customer'])
                        , 'restrict_to_products'   => $sQry->value($map['restrict_to_products'])
                        , 'restrict_to_categories' => $sQry->value($map['restrict_to_categories'])
                        , 'restrict_to_customers'  => $sQry->value($map['restrict_to_customers'])
                        , 'status'                 => $sQry->value($map['status'])
                        , 'date_created'           => $sQry->value($map['date_created'])
                        , 'date_modified'          => $sQry->value($map['date_modified'])
                        , 'sale_exclude'           => $sQry->value($map['sale_exclude'])
                        , 'notes'                  => $sQry->value($map['notes'])
                         ); 
                         
        $coupons[] = $group;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD COUPONS FROM SOURCE DB
    
    // LOAD COUPONS TO TARGET DB
      
    $coupon_types = array(
                            'F' => 'R'
                          , 'P' => 'T'
                          , 'S' => 'S'
                           );
    
    $iCnt = 0;
    foreach ($coupons as $coupon) {
    
      if($coupon['type'] == 'G') continue;
      
      $tQry = $target_db->query('INSERT INTO :table_coupons (coupons_id, 
                                                             type, mode, 
                                                             code, 
                                                             reward, 
                                                             purchase_over, 
                                                             start_date, 
                                                             expires_date, 
                                                             uses_per_coupon, 
                                                             uses_per_customer, 
                                                             restrict_to_products, 
                                                             restrict_to_categories, 
                                                             restrict_to_customers, 
                                                             status, 
                                                             date_created, 
                                                             date_modified, 
                                                             sale_exclude, 
                                                             notes) 
                                                     VALUES (:coupons_id, 
                                                             :type, 
                                                             :mode, 
                                                             :code, 
                                                             :reward, 
                                                             :purchase_over, 
                                                             :start_date, 
                                                             :expires_date, 
                                                             :uses_per_coupon, 
                                                             :uses_per_customer, 
                                                             :restrict_to_products, 
                                                             :restrict_to_categories, 
                                                             :restrict_to_customers, 
                                                             :status, 
                                                             :date_created, 
                                                             :date_modified, 
                                                             :sale_exclude, 
                                                             :notes)');

      $tQry->bindTable(':table_coupons', DB_TABLE_PREFIX . 'coupons');
      $tQry->bindInt  (':coupons_id'            , $coupon['coupons_id']);
      $tQry->bindValue(':type'                  , $coupon_types[$coupon['type']]);
      $tQry->bindValue(':mode'                  , $coupon['mode']);
      $tQry->bindValue(':code'                  , $coupon['code']);
      $tQry->bindFloat(':reward'                , $coupon['reward']);
      $tQry->bindFloat(':purchase_over'         , $coupon['purchase_over']);
      $tQry->bindDate (':start_date'            , $coupon['start_date']);
      $tQry->bindDate (':expires_date'          , $coupon['expires_date']);
      $tQry->bindInt  (':uses_per_coupon'       , $coupon['uses_per_coupon']);
      $tQry->bindInt  (':uses_per_customer'     , $coupon['uses_per_customer']);
      $tQry->bindValue(':restrict_to_products'  , $coupon['restrict_to_products']);
      $tQry->bindValue(':restrict_to_categories', $coupon['restrict_to_categories']);
      $tQry->bindValue(':restrict_to_customers' , $coupon['restrict_to_customers']);
      $tQry->bindInt  (':status'                , $coupon['status']);
      $tQry->bindDate (':date_created'          , $coupon['date_created']);
      $tQry->bindDate (':date_modified'         , $coupon['date_modified']);
      $tQry->bindInt  (':sale_exclude'          , $coupon['sale_exclude']);
      $tQry->bindValue(':notes'                 , $coupon['notes']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD COUPONS TO TARGET DB

    // LOAD COUPONS DESCRIPTION FROM SOURCE DB
    $map = $this->_data_mapping['coupons_description'];
    $coupons_description = array();

    $sQry = $source_db->query('SELECT * FROM coupons_description');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $group  = array(
                          'coupons_id'  => $sQry->value($map['coupons_id'])
                        , 'language_id' => $sQry->value($map['language_id'])
                        , 'name'        => $sQry->value($map['name'])
                         ); 
                         
        $coupons_description[] = $group;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD COUPONS DESCRIPTION FROM SOURCE DB

    // LOAD COUPONS DESCRIPTION TO TARGET DB
      
    $iCnt = 0;
    foreach ($coupons_description as $desc) {
      
      $tQry = $target_db->query('INSERT INTO :table_coupons_desc (coupons_id, 
                                                                  language_id, 
                                                                  name) 
                                                          VALUES (:coupons_id, 
                                                                  :language_id, 
                                                                  :name)');

      $tQry->bindTable(':table_coupons_desc', DB_TABLE_PREFIX . 'coupons_description');
      $tQry->bindInt  (':coupons_id' , $desc['coupons_id']);
      $tQry->bindInt  (':language_id', $desc['language_id']);
      $tQry->bindValue(':name'       , $desc['name']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD COUPONS DESCRIPTION TO TARGET DB

    // LOAD COUPONS REDEEMED FROM SOURCE DB
    $map = $this->_data_mapping['coupons_redeemed'];
    $coupons_redeemed = array();

    $sQry = $source_db->query('SELECT * FROM coupon_redeem_track');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $group  = array(
                          'id'           => $sQry->value($map['id'])
                        , 'coupons_id'   => $sQry->value($map['coupons_id'])
                        , 'customers_id' => $sQry->value($map['customers_id'])
                        , 'redeem_date'  => $sQry->value($map['redeem_date'])
                        , 'redeem_ip'    => $sQry->value($map['redeem_ip'])
                        , 'order_id'     => $sQry->value($map['order_id'])
                         ); 
                         
        $coupons_redeemed[] = $group;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD COUPONS REDEEMED FROM SOURCE DB

    // LOAD COUPONS REDEEMED TO TARGET DB
      
    $iCnt = 0;
    foreach ($coupons_redeemed as $coupon) {
      
      $tQry = $target_db->query('INSERT INTO :table_coupons_redeemed (id, 
                                                                      coupons_id, 
                                                                      customers_id, 
                                                                      redeem_date, 
                                                                      redeem_ip, 
                                                                      order_id) 
                                                              VALUES (:id, 
                                                                      :coupons_id, 
                                                                      :customers_id, 
                                                                      :redeem_date, 
                                                                      :redeem_ip, 
                                                                      :order_id)');

      $tQry->bindTable(':table_coupons_redeemed', DB_TABLE_PREFIX . 'coupons_redeemed');
      $tQry->bindInt  (':id'          , $coupon['id']);
      $tQry->bindInt  (':coupons_id'  , $coupon['coupons_id']);
      $tQry->bindInt  (':customers_id', $coupon['customers_id']);
      $tQry->bindDate (':redeem_date' , $coupon['redeem_date']);
      $tQry->bindValue(':redeem_ip'   , $coupon['redeem_ip']);
      $tQry->bindInt  (':order_id'    , $coupon['order_id']);
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD COUPONS REDEEMED TO TARGET DB

    // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();
    
    return true;
      
  } // end importCoupons
  
  /*
  *  function name : importTaxClassesRates()
  *
  *  description : load tax class rates data from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importTaxClassesRates($switch = null) {
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
            
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
      
    require_once('../includes/database_tables.php');

    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
      
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB
    
    // TRUNCATE TAX TABLES IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . TABLE_TAX_CLASS);
    $tQry->execute();

    $tQry = $target_db->query('truncate table ' . TABLE_TAX_RATES);
    $tQry->execute();
    
    // END TRUNCATE TAX TABLES IN TARGET DB

    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD TAX CLASSES FROM SOURCE DB
    $map = $this->_data_mapping['tax_class'];
    $tax_classes = array();

    $sQry = $source_db->query('SELECT * FROM tax_class');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $class  = array(
                          'tax_class_id'          => $sQry->value($map['tax_class_id'])
                        , 'tax_class_title'       => $sQry->value($map['tax_class_title'])
                        , 'tax_class_description' => $sQry->value($map['tax_class_description'])
                        , 'last_modified'         => $sQry->value($map['last_modified'])
                        , 'date_added'            => $sQry->value($map['date_added'])
                         ); 
                         
        $tax_classes[] = $class;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD TAX CLASSES FROM SOURCE DB

    // LOAD TAX CLASSES TO TARGET DB
      
    $iCnt = 0;
    foreach ($tax_classes as $class) {
      
      $tQry = $target_db->query('INSERT INTO :table_tax_class (tax_class_id, 
                                                               tax_class_title, 
                                                               tax_class_description, 
                                                               last_modified, 
                                                               date_added) 
                                                       VALUES (:tax_class_id, 
                                                               :tax_class_title, 
                                                               :tax_class_description, 
                                                               :last_modified, 
                                                               :date_added)');

      $tQry->bindTable(':table_tax_class', TABLE_TAX_CLASS);
      $tQry->bindInt  (':tax_class_id'         , $class['tax_class_id']);
      $tQry->bindValue(':tax_class_title'      , $class['tax_class_title']);
      $tQry->bindValue(':tax_class_description', $class['tax_class_description']);
      $tQry->bindDate (':last_modified'        , $class['last_modified']);
      $tQry->bindDate (':date_added'           , $class['date_added']);
      
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD TAX CLASSES TO TARGET DB

    // LOAD TAX RATES FROM SOURCE DB
    $map = $this->_data_mapping['tax_rates'];
    $tax_rates = array();

    $sQry = $source_db->query('SELECT * FROM tax_rates');
    $sQry->execute();
      
    if ($sQry->numberOfRows() > 0) { 
      $cnt = 0;
      while ($sQry->next()) {
        $rate  = array(
                         'tax_rates_id'    => $sQry->value($map['tax_rates_id'])
                       , 'tax_zone_id'     => $sQry->value($map['tax_zone_id'])
                       , 'tax_class_id'    => $sQry->value($map['tax_class_id'])
                       , 'tax_priority'    => $sQry->value($map['tax_priority'])
                       , 'tax_rate'        => $sQry->value($map['tax_rate'])
                       , 'tax_description' => $sQry->value($map['tax_description'])
                       , 'last_modified'   => $sQry->value($map['last_modified'])
                       , 'date_added'      => $sQry->value($map['date_added'])
                        ); 
                         
        $tax_rates[] = $rate;
        $cnt++;
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD TAX RATES FROM SOURCE DB
    
    // LOAD TAX RATES TO TARGET DB
      
    $iCnt = 0;
    foreach ($tax_rates as $rate) {
      
      $tQry = $target_db->query('INSERT INTO :table_tax_rates (tax_rates_id, 
                                                               tax_zone_id, 
                                                               tax_class_id, 
                                                               tax_priority, 
                                                               tax_rate, 
                                                               tax_description, 
                                                               last_modified, 
                                                               date_added) 
                                                       VALUES (:tax_rates_id, 
                                                               :tax_zone_id, 
                                                               :tax_class_id, 
                                                               :tax_priority, 
                                                               :tax_rate, 
                                                               :tax_description, 
                                                               :last_modified, 
                                                               :date_added)');

      $tQry->bindTable(':table_tax_rates', TABLE_TAX_RATES);
      $tQry->bindInt  (':tax_rates_id'   , $rate['tax_rates_id']);
      $tQry->bindInt  (':tax_zone_id'    , $rate['tax_zone_id']);
      $tQry->bindInt  (':tax_class_id'   , $rate['tax_class_id']);
      $tQry->bindInt  (':tax_priority'   , $rate['tax_priority']);
      $tQry->bindFloat(':tax_rate'       , $rate['tax_rate']);
      $tQry->bindValue(':tax_description', $rate['tax_description']);
      $tQry->bindDate (':last_modified'  , $rate['last_modified']);
      $tQry->bindDate (':date_added'     , $rate['date_added']);
      
      $tQry->execute();
      
      if ($target_db->isError()) {
        $this->_msg = $target_db->getError();
        return false;
      }
      $iCnt++;
    }

    // END LOAD TAX RATES TO TARGET DB

    // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importTaxClassesRates
  
  /*
  *  function name : importLanguages()
  *
  *  description : load language table data from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importLanguages($switch = null) {
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
            
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
      
    require_once('../includes/database_tables.php');

    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
      
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB
    
    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();

    // LOAD LANGUAGES FROM SOURCE DB
    $tax_classes = array();

    $sQry = $source_db->query('SELECT * FROM languages order by sort_order');
    $sQry->execute();
    
    // SUPPORTED LANGUAGES ARRAY
  
    $sLangs = array(array('name' => 'English',
                          'code' => 'en_US',
                          'locale' => 'en_US.UTF-8,en_US,english',
                          'charset' => 'utf-8',
                          'date_format_short' => '%m/%d/%Y',
                          'date_format_long' => '%A %B %d, %Y at %H:%M',
                          'time_format' => '%H:%M:%S',
                          'text_direction' => 'ltr',
                          'default_currency' => 'USD',
                          'numeric_separator_decimal' => '.',
                          'numeric_separator_thousands' => ','),
                    array('name' => 'German',
                          'code' => 'de_DE',
                          'locale' => 'de_DE.UTF-8,de_DE,german',
                          'charset' => 'utf-8',
                          'date_format_short' => '%Y/%m/%d',
                          'date_format_long' => '%A %B %d, %Y at %H:%M',
                          'time_format' => '%H:%M:%S',
                          'text_direction' => 'ltr',
                          'default_currency' => 'EUR',
                          'numeric_separator_decimal' => '.',
                          'numeric_separator_thousands' => ','),
                    array('name' => 'Spanish',
                          'code' => 'es_ES',
                          'locale' => 'es_ES.UTF-8,es_ES,spanish',
                          'charset' => 'utf-8',
                          'date_format_short' => '%d/%m/%Y',
                          'date_format_long' => '%A %B %d, %Y at %H:%M',
                          'time_format' => '%H:%M:%S',
                          'text_direction' => 'ltr',
                          'default_currency' => 'EUR',
                          'numeric_separator_decimal' => '.',
                          'numeric_separator_thousands' => ','),
                    array('name' => 'French',
                          'code' => 'fr_FR',
                          'locale' => 'fr_FR.UTF-8,fr_FR,french',
                          'charset' => 'utf-8',
                          'date_format_short' => '%d/%m/%Y',
                          'date_format_long' => '%A %B %d, %Y at %H:%M',
                          'time_format' => '%H:%M:%S',
                          'text_direction' => 'ltr',
                          'default_currency' => 'EUR',
                          'numeric_separator_decimal' => '.',
                          'numeric_separator_thousands' => ','),
                    array('name' => 'Russian',
                          'code' => 'ru_RU',
                          'locale' => 'ru_RU.UTF-8,ru_RU,russian',
                          'charset' => 'utf-8',
                          'date_format_short' => '%Y/%m/%d',
                          'date_format_long' => '%A %B %d, %Y at %H:%M',
                          'time_format' => '%H:%M:%S',
                          'text_direction' => 'ltr',
                          'default_currency' => 'RUB',
                          'numeric_separator_decimal' => '.',
                          'numeric_separator_thousands' => ','),
                    array('name' => 'Swedish',
                          'code' => 'sv_SE',
                          'locale' => 'sv_SE.UTF-8,sv_SE,swedish',
                          'charset' => 'utf-8',
                          'date_format_short' => '%Y/%m/%d',
                          'date_format_long' => '%A %B %d, %Y at %H:%M',
                          'time_format' => '%H:%M:%S',
                          'text_direction' => 'ltr',
                          'default_currency' => 'SEK',
                          'numeric_separator_decimal' => '.',
                          'numeric_separator_thousands' => ','),
                    array('name' => 'Chinese Simplified',
                          'code' => 'zh-CN',
                          'locale' => 'zh_CN.UTF-8,zh_CN,english',
                          'charset' => 'utf-8',
                          'date_format_short' => '%m/%d/%Y',
                          'date_format_long' => '%A %B %d, %Y at %H:%M',
                          'time_format' => '%H:%M:%S',
                          'text_direction' => 'ltr',
                          'default_currency' => 'CNY',
                          'numeric_separator_decimal' => '.',
                          'numeric_separator_thousands' => ','));
                          
    // END SUPPORTED LANGUAGES ARRAY
    
    if ($sQry->numberOfRows() > 0) { 
      while ($sQry->next()) {
        if ($sQry->value('name') != 'English') {
          foreach ($sLangs as $suplang) {
            if ($sQry->value('name') == $suplang['name']) {
              // GET CURRENCY ID FROM SOURCE DB
              $cQry = $source_db->query('SELECT currencies_id, code FROM currencies WHERE code = :code');
              $cQry->bindValue(':code', $suplang['default_currency']);
              $cQry->execute();
              
              if ($suplang['default_currency'] == $cQry->value('code')) {
                $cur_id = $cQry->value('currencies_id');
              } else {
                $cur_id = 1;
              }
              $language  = array(
                                   'languages_id'                => $sQry->value('languages_id')
                                 , 'name'                        => $sQry->value('name')
                                 , 'code'                        => $suplang['code']
                                 , 'locale'                      => $suplang['locale']
                                 , 'charset'                     => $suplang['charset']
                                 , 'date_format_short'           => $suplang['date_format_short']
                                 , 'date_format_long'            => $suplang['date_format_long']
                                 , 'time_format'                 => $suplang['time_format']
                                 , 'text_direction'              => $suplang['text_direction']
                                 , 'currencies_id'               => $cur_id
                                 , 'numeric_separator_decimal'   => $suplang['numeric_separator_decimal']
                                 , 'numeric_separator_thousands' => $suplang['numeric_separator_thousands']
                                 , 'parent_id'                   => 0
                                 , 'sort_order'                  => ($sQry->value('sort_order')+10)
                                  ); 
                               
              $languages[] = $language;
              $cQry->freeResult();
            } 
          }
        }
      }        
      $sQry->freeResult();
    }
    
    // END LOAD LANGUAGES FROM SOURCE DB

    // LOAD LANGUAGES TO TARGET DB
    
    if (isset($languages) && !empty($languages)) {  
      foreach ($languages as $language) {
        $tQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_languages (languages_id, 
                                                                                                            name, 
                                                                                                            code, 
                                                                                                            locale, 
                                                                                                            charset, 
                                                                                                            date_format_short, 
                                                                                                            date_format_long, 
                                                                                                            time_format, 
                                                                                                            text_direction, 
                                                                                                            currencies_id, 
                                                                                                            numeric_separator_decimal, 
                                                                                                            numeric_separator_thousands, 
                                                                                                            parent_id, 
                                                                                                            sort_order) 
                                                                                                    VALUES (:languages_id, 
                                                                                                            :name, 
                                                                                                            :code, 
                                                                                                            :locale, 
                                                                                                            :charset, 
                                                                                                            :date_format_short, 
                                                                                                            :date_format_long, 
                                                                                                            :time_format, 
                                                                                                            :text_direction, 
                                                                                                            :currencies_id, 
                                                                                                            :numeric_separator_decimal, 
                                                                                                            :numeric_separator_thousands, 
                                                                                                            :parent_id, 
                                                                                                            :sort_order)');
                                                                      
        $tQry->bindTable(':table_languages', TABLE_LANGUAGES);
        $tQry->bindInt  (':languages_id',                $language['languages_id']);
        $tQry->bindValue(':name',                        $language['name']);
        $tQry->bindValue(':code',                        $language['code']);
        $tQry->bindValue(':locale',                      $language['locale']);
        $tQry->bindValue(':charset',                     $language['charset']);
        $tQry->bindValue(':date_format_short',           $language['date_format_short']);
        $tQry->bindValue(':date_format_long',            $language['date_format_long']);
        $tQry->bindValue(':time_format',                 $language['time_format']);
        $tQry->bindValue(':text_direction',              $language['text_direction']);
        $tQry->bindInt  (':currencies_id',               $language['currencies_id']);
        $tQry->bindValue(':numeric_separator_decimal',   $language['numeric_separator_decimal']);
        $tQry->bindValue(':numeric_separator_thousands', $language['numeric_separator_thousands']);
        $tQry->bindInt  (':parent_id',                   $language['parent_id']);
        $tQry->bindInt  (':sort_order',                  $language['sort_order']);
        
        $tQry->execute();
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        // added for products images groups
        $igQry = $target_db->query('select id, title, code, size_width, size_height, force_size from :table_products_images_groups where language_id = :language_id');
        $igQry->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
        $igQry->bindInt(':language_id', $this->_languages_id_default);
        $igQry->execute();

        if ($igQry->numberOfRows() > 0) {
          while ( $igQry->next() ) {
            $igiQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_products_images_groups (id, language_id, title, code, size_width, size_height, force_size) VALUES (:id, :language_id, :title, :code, :size_width, :size_height, :force_size)');
            $igiQry->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
            $igiQry->bindInt(':id', $igQry->valueInt('id'));
            $igiQry->bindInt(':language_id', $language['languages_id']);
            $igiQry->bindValue(':title', $igQry->value('title'));
            $igiQry->bindValue(':code', $igQry->value('code'));
            $igiQry->bindInt(':size_width', $igQry->value('size_width'));
            $igiQry->bindInt(':size_height', $igQry->value('size_height'));
            $igiQry->bindInt(':force_size', $igQry->value('force_size'));
            $igiQry->execute();
          }
        }
        
        $igQry->freeResult();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        // added for orders transactions status
        $otsQry = $target_db->query('select id, status_name from :table_orders_transactions_status where language_id = :language_id');
        $otsQry->bindTable(':table_orders_transactions_status', TABLE_ORDERS_TRANSACTIONS_STATUS);
        $otsQry->bindInt(':language_id', $this->_languages_id_default);
        $otsQry->execute();

        if ($otsQry->numberOfRows() > 0) {
          while ( $otsQry->next() ) {
            $otsiQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_orders_transactions_status (id, language_id, status_name) VALUES (:id, :language_id, :status_name)');
            $otsiQry->bindTable(':table_orders_transactions_status', TABLE_ORDERS_TRANSACTIONS_STATUS);
            $otsiQry->bindInt(':id', $otsQry->valueInt('id'));
            $otsiQry->bindInt(':language_id', $language['languages_id']);
            $otsiQry->bindValue(':status_name', $otsQry->value('status_name'));
            $otsiQry->execute();
          }
        }
        
        $otsQry->freeResult();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        // added shipping availability
        $saQry = $target_db->query('select id, title, css_key from :table_shipping_availability where languages_id = :languages_id');
        $saQry->bindTable(':table_shipping_availability', TABLE_SHIPPING_AVAILABILITY);
        $saQry->bindInt(':languages_id', $this->_languages_id_default);
        $saQry->execute();

        if ($saQry->numberOfRows() > 0) {
          while ( $saQry->next() ) {
            $saiQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_shipping_availability (id, languages_id, title, css_key) VALUES (:id, :languages_id, :title, :css_key)');
            $saiQry->bindTable(':table_shipping_availability', TABLE_SHIPPING_AVAILABILITY);
            $saiQry->bindInt(':id', $saQry->valueInt('id'));
            $saiQry->bindInt(':languages_id', $language['languages_id']);
            $saiQry->bindValue(':title', $saQry->value('title'));
            $saiQry->bindValue(':css_key', $saQry->value('css_key'));
            $saiQry->execute();
          }
        }
        
        $saQry->freeResult();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        // added for weight class
        $wcQry = $target_db->query('select weight_class_id, weight_class_key, weight_class_title from :table_weight_classes where language_id = :language_id');
        $wcQry->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
        $wcQry->bindInt(':language_id', $this->_languages_id_default);
        $wcQry->execute();

        if ($wcQry->numberOfRows() > 0) {
          while ( $wcQry->next() ) {
            $wciQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_weight_classes (weight_class_id, weight_class_key, language_id, weight_class_title) VALUES (:weight_class_id, :weight_class_key, :language_id, :weight_class_title)');
            $wciQry->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
            $wciQry->bindInt(':weight_class_id', $wcQry->valueInt('weight_class_id'));
            $wciQry->bindValue(':weight_class_key', $wcQry->value('weight_class_key'));
            $wciQry->bindInt(':language_id', $language['languages_id']);
            $wciQry->bindValue(':weight_class_title', $wcQry->value('weight_class_title'));
            $wciQry->execute();
          }
        }
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
        
        $wcQry->freeResult();
        
        // added for variants groups
        $vgQry = $target_db->query('SELECT * FROM :table_products_variants_groups WHERE languages_id = :languages_id');
        $vgQry->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
        $vgQry->bindInt(':languages_id', $this->_languages_id_default);
        $vgQry->execute();
        
        if ($vgQry->numberOfRows() > 0) { 
          while ($vgQry->next()) {
            $vgnQry = $source_db->query('SELECT products_options_name FROM products_options_text WHERE products_options_text_id = :products_options_text_id AND language_id = :language_id');
            $vgnQry->bindInt(':products_options_text_id', $vgQry->value('id'));
            $vgnQry->bindInt(':language_id', $language['languages_id']);
            $vgnQry->execute();
            
            $tQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_products_variants_groups (id, 
                                                                                                                               languages_id, 
                                                                                                                               title, 
                                                                                                                               sort_order, 
                                                                                                                               module) 
                                                                                                                       VALUES (:id, 
                                                                                                                               :languages_id, 
                                                                                                                               :title, 
                                                                                                                               :sort_order, 
                                                                                                                               :module)');

            $tQry->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
            
            $tQry->bindInt  (':id'          , $vgQry->value('id'));
            $tQry->bindInt  (':languages_id', $language['languages_id']);
            $tQry->bindValue(':title'       , $vgnQry->value('products_options_name'));
            $tQry->bindInt  (':sort_order'  , $vgQry->value('sort_order'));
            $tQry->bindValue(':module'      , $vgQry->value('module'));
            $tQry->execute();
            
            $vgnQry->freeResult();
            
            if ($target_db->isError()) {
              $this->_msg = $target_db->getError();
              return false;
            }
          } 
        
          $vgQry->freeResult();
          
        } 
              
        // added for variants values
        $vvQry = $target_db->query('SELECT * FROM :table_products_variants_values WHERE languages_id = :languages_id');
        $vvQry->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
        $vvQry->bindInt(':languages_id', $this->_languages_id_default);
        $vvQry->execute();
          
        if ($vvQry->numberOfRows() > 0) { 
          while ($vvQry->next()) {
            // later let's add sort order
            //$vsoQry = $source_db->query('SELECT  FROM  WHERE  = : AND  = : AND  = :');
            //$vsoQry->bindInt(':', $sQry->value(''));
            //$vsoQry->bindInt(':', $sQry->value(''));
            //$vsoQry->bindInt(':', $sQry->value(''));
            //$vsoQry->execute();
            
            $vvnQry = $source_db->query('SELECT products_options_values_name FROM products_options_values WHERE products_options_values_id = :products_options_values_id AND language_id = :language_id');
            $vvnQry->bindInt(':products_options_values_id', $vvQry->value('id'));
            $vvnQry->bindInt(':language_id', $language['languages_id']);
            $vvnQry->execute(); 
            
            $tQry = $target_db->query((($switch != -1) ? 'INSERT' : 'INSERT IGNORE') . ' INTO :table_products_variants_values (id, 
                                                                                                                               languages_id, 
                                                                                                                               products_variants_groups_id, 
                                                                                                                               title, 
                                                                                                                               sort_order) 
                                                                                                                       VALUES (:id, 
                                                                                                                               :languages_id, 
                                                                                                                               :products_variants_groups_id, 
                                                                                                                               :title, 
                                                                                                                               :sort_order)');

            $tQry->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
            $tQry->bindInt  (':id'                            , $vvQry->value('id'));
            $tQry->bindInt  (':languages_id'                  , $language['languages_id']);
            $tQry->bindInt  (':products_variants_groups_id'   , $vvQry->value('products_variants_groups_id'));
            $tQry->bindValue(':title'                         , $vvnQry->value('products_options_values_name'));
            $tQry->bindInt  (':sort_order'                    , $vvQry->value('sort_order'));
            $tQry->execute();
            
            $vvnQry->freeResult();
            
            if ($target_db->isError()) {
              $this->_msg = $target_db->getError();
              return false;
            }
          }
          
          $vvQry->freeResult();
        }
        
        // Added for text_field options
        $stfQry = $source_db->query('SELECT products_options_sort_order FROM products_options WHERE options_type = :options_type');
        $stfQry->bindInt(':options_type', 4);
        $stfQry->execute();
        
        if ($stfQry->numberOfRows() > 0) {      
          while ($stfQry->next()) { 
            $stftQry = $source_db->query('SELECT products_options_name FROM products_options_text WHERE products_options_text_id = :products_options_text_id AND language_id = :language_id');
            $stftQry->bindInt(':products_options_text_id', 4);
            $stftQry->bindInt(':language_id', $language['languages_id']);
            $stftQry->execute();
            
            while ($stftQry->next()) {
              $ttfQry = $target_db->query('UPDATE :table_products_variants_values SET title = :title WHERE products_variants_groups_id = :products_variants_groups_id AND languages_id = :languages_id');          
              
              $ttfQry->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
              
              $ttfQry->bindInt  (':languages_id'               , $language['languages_id']);
              $ttfQry->bindInt  (':products_variants_groups_id', 4);
              $ttfQry->bindValue(':title'                      , $stftQry->value('products_options_name'));
              $ttfQry->execute();
            
              if ($target_db->isError()) {
                $this->_msg = $target_db->getError();
                return false;
              }
            }
          }
        }
      }
    }
    // END LOAD LANGUAGES TO TARGET DB 

    // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();
    
    return true;
      
  } // end importLanguages
  
  /*
  *  function name : importCurrencies()
  *
  *  description : load currencies table data from source to loaded7
  *
  *  returns : true or false  
  *
  */
  public function importCurrencies($switch = null) {
    
    $s_db = $this->_sDB;
    $t_db = $this->_tDB;
            
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO SOURCE DB
      
    require_once('../includes/database_tables.php');

    require_once('../includes/classes/database/mysqli.php');
    $class = 'lC_Database_mysqli'; // . $s_db['DB_DATABASE_CLASS'];
    $source_db = new $class($s_db['DB_SERVER'], $s_db['DB_SERVER_USERNAME'], $s_db['DB_SERVER_PASSWORD']);
      
    if ($source_db->isError() === false) {
      $source_db->selectDatabase($s_db['DB_DATABASE']);
    }
      
    if ($source_db->isError()) {
      $this->_msg = $source_db->getError();
      return false;
    }
    // END CONNNECT TO SOURCE DB
      
    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB
    
    // DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
    $tQry->execute();
    
    // TRUNCATE CURRENCIES TABLES IN TARGET DB
    
    $tQry = $target_db->query('truncate table ' . TABLE_CURRENCIES);
    $tQry->execute();
    
    // END TRUNCATE CURRENCIES TABLES IN TARGET DB

    // LOAD CURRENCIES FROM SOURCE DB
    $map = $this->_data_mapping['currencies'];
    
    $sQry = $source_db->query('SELECT * FROM currencies order by currencies_id');
    $sQry->execute();
    
    // END LOAD CURRENCIES FROM SOURCE DB

    // LOAD CURRENCIES TO TARGET DB
    
    if ($sQry->numberOfRows() > 0) { 
      while ($sQry->next()) {
        $currency  = array(
                             'currencies_id'  => $sQry->value($map['currencies_id'])
                           , 'title'          => $sQry->value($map['title'])
                           , 'code'           => $sQry->value($map['code'])
                           , 'symbol_left'    => $sQry->value($map['symbol_left'])
                           , 'symbol_right'   => $sQry->value($map['symbol_right'])
                           , 'decimal_places' => $sQry->value($map['decimal_places'])
                           , 'value'          => $sQry->value($map['value'])
                           , 'last_updated'   => $sQry->value($map['last_updated'])
                            );
      
        $tQry = $target_db->query('INSERT INTO :table_currencies (currencies_id, 
                                                                  title, 
                                                                  code, 
                                                                  symbol_left, 
                                                                  symbol_right, 
                                                                  decimal_places, 
                                                                  value,
                                                                  last_updated) 
                                                          VALUES (:currencies_id, 
                                                                  :title, 
                                                                  :code, 
                                                                  :symbol_left, 
                                                                  :symbol_right, 
                                                                  :decimal_places, 
                                                                  :value,
                                                                  :last_updated)');

        $tQry->bindTable(':table_currencies', TABLE_CURRENCIES);
        
        $tQry->bindInt   (':currencies_id' , $currency['currencies_id']);
        $tQry->bindValue (':title'         , $currency['title']);
        $tQry->bindValue (':code'          , $currency['code']);
        $tQry->bindValue (':symbol_left'   , $currency['symbol_left']);
        $tQry->bindValue (':symbol_right'  , $currency['symbol_right']);
        $tQry->bindInt   (':decimal_places', $currency['decimal_places']);
        $tQry->bindFloat (':value'         , $currency['value']);
        $tQry->bindDate  (':last_updated'  , $currency['last_updated']);
        $tQry->execute();
        
        if ($target_db->isError()) {
          $this->_msg = $target_db->getError();
          return false;
        }
      }
      
      $sQry->freeResult();
    }
    
    // END LOAD CURRENCIES TO TARGET DB 

    // END DISABLE AUTO INCREMENT WHEN PRIMARY KEY = 0
    $tQry = $target_db->query('SET GLOBAL sql_mode = ""');
    $tQry->execute();
    
    $source_db->disconnect();  
    $target_db->disconnect();  
    
    return true;
      
  } // end importCurrencies
    
  public function generateCleanPermalink($p) {
    $p = preg_replace("/&.{0,}?;/", '', $p);
    $p = str_replace(array(" ", ",", "/", "(", ")", "'", ":", "?", ";", "\"", "%"), "-", $p);
    $p = str_replace("&", "and", $p);
    $p = str_replace(".", "", $p);
    $p = str_replace("----", "-", $p);
    $p = str_replace("---", "-", $p);
    $p = str_replace("--", "-", $p);
      
    //Convert accented characters, and remove parentheses and apostrophes
    $from = explode(',', "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,(,),[,],'");
    $to = explode(',', 'c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,,,,,,');

    //Do the replacements, and convert all other non-alphanumeric characters to spaces
    $p = preg_replace('~[^\w\d]+~', '-', str_replace($from, $to, trim($p)));

    //Remove a - at the beginning or end and make lowercase
    return strtolower(preg_replace('/^-/', '', preg_replace('/-$/', '', $p)));
  }
  
  public function getAddressFormat($name = null, $iso2 = null, $iso3 = null) {
    
    $t_db = $this->_tDB;
            
    if (!defined('DB_TABLE_PREFIX')) define('DB_TABLE_PREFIX', $t_db['DB_PREFIX']);

    // CONNNECT TO TARGET DB

    $class = 'lC_Database_' . $t_db['DB_CLASS'];
    $target_db = new $class($t_db['DB_SERVER'], $t_db['DB_SERVER_USERNAME'], $t_db['DB_SERVER_PASSWORD']);
      
    if ($target_db->isError() === false) {
      $target_db->selectDatabase($t_db['DB_DATABASE']);
    }
      
    if ($target_db->isError()) {
      $this->_msg = $target_db->getError();
      return false;
    }

    // END CONNNECT TO TARGET DB
    
    // GET ADDRESS FORMAT FROM TARGET DB
    
    $tQry = $target_db->query('SELECT address_format FROM :table_countries WHERE countries_name = :countries_name OR countries_iso_code_2 = :countries_iso_code_2 OR countries_iso_code_3 = :countries_iso_code_3');
    
    $tQry->bindTable (':table_countries', TABLE_COUNTRIES);
    
    $tQry->bindValue(':countries_name' , $name); 
    $tQry->bindValue(':countries_iso_code_2' , strtoupper($iso2)); 
    $tQry->bindValue(':countries_iso_code_3' , strtoupper($iso3)); 
    $tQry->execute();
    
    // END GET ADDRESS FORMAT FROM TARGET DB
    
    $address_format = '';     
    
    while ($tQry->next()) {
      $address_format = $tQry->value('address_format');
    }
    
    if ($address_format == '') {
      $address_format = ':name' . "\n" . 
                        ':street_address' . "\n" .
                        ':city :state_code :postcode' . "\n" .
                        ':country';
    }
      
    return $address_format; 
  }
}
?>
