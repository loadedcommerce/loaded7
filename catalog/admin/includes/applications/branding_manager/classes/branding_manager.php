<?php
/*
  $Id: branding_manager.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Branding_Admin class manages customer groups definitions
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
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language;

    $error = false;   
    if ( empty($language_id) ) {
      $language_id = $lC_Language->getID();
    }

    $Qdelete = $lC_Database->query('delete from :table_branding where language_id = :language_id');
    $Qdelete->bindTable(':table_branding', TABLE_BRANDING);
    $Qdelete->bindInt(':language_id', $language_id);
    $Qdelete->execute();
    
    $Qbranding = $lC_Database->query('insert into :table_branding (site_image, name, slogan, chat_code, address, support_phone, support_email, sales_phone, sales_email, meta_description, meta_keywords, og_image, meta_title, meta_slug, meta_slug_placement, meta_delimeter, social_facebook_page, social_twitter, social_pinterest, social_google_plus, social_youtube, social_linkedin, footer_text,language_id) values (:site_image, :name, :slogan, :chat_code, :address, :support_phone, :support_email, :sales_phone, :sales_email, :meta_description, :meta_keywords, :og_image, :meta_title, :meta_slug, :meta_slug_placement, :meta_delimeter, :social_facebook_page, :social_twitter, :social_pinterest, :social_google_plus, :social_youtube, :social_linkedin, :footer_text,:language_id )');

    $Qbranding->bindTable(':table_branding', TABLE_BRANDING);
    $Qbranding->bindValue(':site_image', $data['site_image']);
    $Qbranding->bindValue(':name', $data['name']);
    $Qbranding->bindValue(':slogan', $data['slogan']);
    $Qbranding->bindValue(':chat_code', $data['chat_code']);
    $Qbranding->bindValue(':address', $data['address']);
    $Qbranding->bindInt(':support_phone', $data['support_phone']);
    $Qbranding->bindValue(':support_email', $data['support_email']);
    $Qbranding->bindInt(':sales_phone', $data['sales_phone']);
    $Qbranding->bindValue(':sales_email', $data['sales_email']);
    $Qbranding->bindValue(':meta_description', $data['meta_description']);
    $Qbranding->bindValue(':meta_keywords', $data['meta_keywords']);
    $Qbranding->bindValue(':og_image', $data['og_image']);
    $Qbranding->bindValue(':meta_title', $data['meta_title']);
    $Qbranding->bindValue(':meta_slug', $data['meta_slug']);
    $Qbranding->bindInt(':meta_slug_placement', $data['meta_slug_placement']);
    $Qbranding->bindValue(':meta_delimeter', $data['meta_delimeter']);
    $Qbranding->bindValue(':social_facebook_page', $data['social_facebook_page']);
    $Qbranding->bindValue(':social_twitter', $data['social_twitter']);
    $Qbranding->bindValue(':social_pinterest', $data['social_pinterest']);
    $Qbranding->bindValue(':social_google_plus', $data['social_google_plus']);
    $Qbranding->bindValue(':social_youtube', $data['social_youtube']);
    $Qbranding->bindValue(':social_linkedin', $data['social_linkedin']);
    $Qbranding->bindValue(':footer_text', $data['footer_text']);
    $Qbranding->bindValue(':language_id', $language_id);
    $Qbranding->setLogging($_SESSION['module']);
    $Qbranding->execute();

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
    
    $branding_manager_logo = $uploader->handleUpload('../images/');
    
    $result = array('result' => 1,
                    'fileName' => $branding_manager_logo['filename'],
                    'success' => true,
                    'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }


}
?>