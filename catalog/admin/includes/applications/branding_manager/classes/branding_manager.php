<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: branding_manager.php v1.0 2013-08-08 datazen $
*/
class lC_Branding_manager_Admin {
  /*
  * Returns the store branding data
  *
  * @access public
  * @return array
  */
  public static function get() {
    global $lC_Database, $lC_Language;

    if ( empty($language_id) ) {
      $language_id = $lC_Language->getID();
    }

    $Qbranding = $lC_Database->query('select * from :table_branding where language_id = :language_id');
    $Qbranding->bindTable(':table_branding', TABLE_BRANDING);
    $Qbranding->bindInt(':language_id', $language_id);
    $Qbranding->execute();

    $data = $Qbranding->toArray();


    return $data;
  }
  /*
  * Save the category record
  *
  * @param integer $id The category id on update, null on insert
  * @param array $data The category information
  * @access public
  * @return boolean
  */
  public static function save($data) {
    global $lC_Database, $lC_Language;

    $error = false; 

    if ( empty($language_id) ) {
      $language_id = $lC_Language->getID();
    }


    $Qdelete = $lC_Database->query('delete from :table_branding_data');
    $Qdelete->bindTable(':table_branding_data', TABLE_BRANDING_DATA);
    $Qdelete->execute();


    foreach ( $lC_Language->getAll() as $l ) {
      //delete data first
      $Qdelete = $lC_Database->query('delete from :table_branding where language_id = :language_id');
      $Qdelete->bindTable(':table_branding', TABLE_BRANDING);
      $Qdelete->bindInt(':language_id', $l['id']);
      $Qdelete->execute();
      //save new data
      $QBrand = $lC_Database->query('insert into :table_branding (language_id, homepage_text, slogan, meta_description, meta_keywords, meta_title, meta_title_prefix, meta_title_suffix, footer_text) values (:language_id, :homepage_text, :slogan, :meta_description, :meta_keywords, :meta_title, :meta_title_prefix, :meta_title_suffix, :footer_text)');
      $QBrand->bindTable(':table_branding', TABLE_BRANDING);
      $QBrand->bindValue(':homepage_text', $data['home_page_text'][$l['id']]);
      $QBrand->bindValue(':slogan', $data['slogan'][$l['id']]);
      $QBrand->bindValue(':meta_description', $data['meta_description'][$l['id']]);
      $QBrand->bindValue(':meta_keywords', $data['meta_keywords'][$l['id']]);
      $QBrand->bindValue(':meta_title', $data['meta_title'][$l['id']]);
      $QBrand->bindValue(':meta_title_prefix', $data['meta_title_prefix'][$l['id']]); 
      $QBrand->bindValue(':meta_title_suffix', $data['meta_title_suffix'][$l['id']]);
      $QBrand->bindValue(':footer_text', $data['footer_text'][$l['id']]);
      $QBrand->bindValue(':language_id', $l['id']);
      $QBrand->setLogging($_SESSION['module']);
      $QBrand->execute();

    }

    //save non language specific data
    $QbrandingData = $lC_Database->query('insert into :table_branding_data (site_image, chat_code, support_phone, support_email, sales_phone, sales_email, og_image, meta_delimeter, social_facebook_page, social_twitter, social_pinterest, social_google_plus, social_youtube, social_linkedin) values ( :site_image, :chat_code, :support_phone, :support_email, :sales_phone, :sales_email, :og_image, :meta_delimeter, :social_facebook_page, :social_twitter, :social_pinterest, :social_google_plus, :social_youtube, :social_linkedin)');
    $QbrandingData->bindTable(':table_branding_data', TABLE_BRANDING_DATA);
    $QbrandingData->bindValue(':site_image', $data['site_image']);
    $QbrandingData->bindValue(':chat_code', $data['chat_code']);
    $QbrandingData->bindValue(':address', $data['address']);
    $QbrandingData->bindValue(':support_phone', $data['support_phone']);
    $QbrandingData->bindValue(':support_email', $data['support_email']);
    $QbrandingData->bindValue(':sales_phone', $data['sales_phone']);
    $QbrandingData->bindValue(':sales_email', $data['sales_email']);
    $QbrandingData->bindValue(':og_image', $data['og_image']);
    $QbrandingData->bindValue(':meta_delimeter', $data['meta_delimeter']);
    $QbrandingData->bindValue(':social_facebook_page', $data['social_facebook_page']);
    $QbrandingData->bindValue(':social_twitter', $data['social_twitter']);
    $QbrandingData->bindValue(':social_pinterest', $data['social_pinterest']);
    $QbrandingData->bindValue(':social_google_plus', $data['social_google_plus']);
    $QbrandingData->bindValue(':social_youtube', $data['social_youtube']);
    $QbrandingData->bindValue(':social_linkedin', $data['social_linkedin']);
    $QbrandingData->bindValue(':language_id', $language_id);
    $QbrandingData->execute();

    $QbrandingName = $lC_Database->query('update :table_configuration set configuration_value = :name where configuration_key = "STORE_NAME"');
    $QbrandingName->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $QbrandingName->bindValue(':name', $data['name']);
    $QbrandingName->execute();

    $QbrandingAddress = $lC_Database->query('update :table_configuration set configuration_value = :address where configuration_key = "STORE_NAME_ADDRESS"');
    $QbrandingAddress->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $QbrandingAddress->bindValue(':address', $data['address']);
    $QbrandingAddress->execute();
   
    lC_Cache::clear('configuration');

    return true;
  }
  /*
  * Upload Branding Manager Logo
  * 
  * @access public
  * @return json
  */
  public static function brandingImageUpload() {
    global $lC_Database, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/ajax_upload.php'));

    // list of valid extensions, ex. array("jpeg", "xml", "bmp")
    $allowedExtensions = array('gif', 'jpg', 'jpeg', 'png');
    // max file size in bytes
    $sizeLimit = 10 * 1024 * 1024;

    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

    $branding_manager_logo = $uploader->handleUpload('../images/branding/');

    $result = array('result' => 1,
      'fileName' => $branding_manager_logo['filename'],
      'success' => true,
      'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }
  /*
  * Delete Site Logo Image
  * 
  * @access public
  * @return json
  */
  public static function deleteBmLogo($_logo) {
    global $lC_Database;

      if (file_exists('../images/branding/' . $_logo)){
        unlink('../images/branding/' . $_logo);
      }
      $QbrandingImage = $lC_Database->query('update :table_branding_data set site_image = ""');
      $QbrandingImage->bindTable(':table_branding_data', TABLE_BRANDING_DATA);
      $QbrandingImage->execute();
    
    return true;
  }
  /*
  * Delete Open Graph Image
  * 
  * @access public
  * @return json
  */
  public static function deleteOgImage($_ogimage) {
    global $lC_Database;

      if (file_exists('../images/branding/' . $_ogimage)){
        unlink('../images/branding/' . $_ogimage);
      }
      $QbrandingImage = $lC_Database->query('update :table_branding_data set og_image = ""');
      $QbrandingImage->bindTable(':table_branding_data', TABLE_BRANDING_DATA);
      $QbrandingImage->execute();
    
    return true;
  }
}
?>