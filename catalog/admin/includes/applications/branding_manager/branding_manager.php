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
require('includes/applications/branding_manager/classes/branding_manager.php');

class lC_Application_Branding_manager extends lC_Template_Admin {
  /*
  * Protected variables
  */
  protected $_module = 'branding_manager',
  $_page_title,
  $_page_contents = 'edit.php';
  /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');

    if ( !isset($_GET['action']) ) {
      $_GET['action'] = '';
    }


    if ( !empty($_GET['action']) && !($_SESSION['error']) ) {

      switch ( $_GET['action'] ) {
        case 'save':
          $branding_image                = $_POST['branding_manager_logo'];
          $branding_name                 = $_POST['branding_name'];
          $branding_slogan               = $_POST['branding_slogan'];
          $branding_chat_code            = $_POST['branding_chat_code'];
          $branding_address              = $_POST['branding_address'];           
          $branding_support_phone        = $_POST['branding_support_phone'];
          $branding_support_email        = $_POST['branding_support_email'];
          $branding_sales_phone          = $_POST['branding_sales_phone'];
          $branding_sales_email          = $_POST['branding_sales_email'];
          $branding_meta_description     = $_POST['branding_meta_description'];
          $branding_meta_keywords        = $_POST['branding_meta_keywords'];
          $branding_graph_site_thumbnail = $_POST['branding_graph_site_thumbnail'];
          $branding_meta_title           = $_POST['branding_meta_title'];
          $branding_meta_title_prefix    = $_POST['branding_meta_title_prefix'];
          $branding_meta_title_suffix    = $_POST['branding_meta_title_suffix'];
          $branding_meta_title_delimeter = $_POST['branding_meta_title_delimeter'];
          $branding_social_fb_page       = $_POST['branding_social_fb_page'];
          $branding_social_twitter       = $_POST['branding_social_twitter'];
          $branding_social_pinterest     = $_POST['branding_social_pinterest'];
          $branding_social_google_plus   = $_POST['branding_social_google_plus'];
          $branding_social_youtube       = $_POST['branding_social_youtube'];
          $branding_social_linkedin      = $_POST['branding_social_linkedin'];
          $branding_footer_text          = $_POST['branding_footer_text'];
          $branding_home_page_text       = $_POST['branding_home_page_text'];

          $data = array('site_image' => $branding_image,
            'name'                 => $branding_name,
            'slogan'               => $branding_slogan,
            'chat_code'            => $branding_chat_code,
            'address'              => $branding_address,
            'support_phone'        => $branding_support_phone,
            'support_email'        => $branding_support_email,
            'sales_phone'          => $branding_sales_phone,
            'sales_email'          => $branding_sales_email,
            'meta_description'     => $branding_meta_description,
            'meta_keywords'        => $branding_meta_keywords,
            'og_image'             => $branding_graph_site_thumbnail,
            'meta_title'           => $branding_meta_title,
            'meta_title_prefix'    => $branding_meta_title_prefix,
            'meta_title_suffix'    => $branding_meta_title_suffix,
            'meta_delimeter'       => $branding_meta_title_delimeter,
            'social_facebook_page' => $branding_social_fb_page,
            'social_twitter'       => $branding_social_twitter,
            'social_pinterest'     => $branding_social_pinterest,
            'social_google_plus'   => $branding_social_google_plus,
            'social_youtube'       => $branding_social_youtube,
            'social_linkedin'      => $branding_social_linkedin,
            'footer_text'          => $branding_footer_text,
            'home_page_text'       => $branding_home_page_text

          );

          /*
          * Save the banner information
          *
          * @param integer $_GET['bid'] The banner id
          * @param array $data The banner information
          * @access public
          * @return boolean
          */           
          if ( lC_Branding_manager_Admin::save($data) ) {
            lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
          } else {
            $_SESSION['error'] = true;
            $_SESSION['errmsg'] = $lC_Language->get('ms_error_action_not_performed');
          }
          break;
      }
    }
  }
}
?>