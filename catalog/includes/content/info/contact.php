<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: contact.php v1.0 2013-08-08 datazen $
*/
class lC_Info_Contact extends lC_Template {

  /* Private variables */
  var $_module = 'contact',
      $_group = 'info',
      $_page_title,
      $_page_contents = 'info_contact.php',
      $_page_image = 'table_background_contact_us.gif';

  /* Class constructor */
  public function lC_Info_Contact() {
    global $lC_Services, $lC_Language, $lC_Breadcrumb, $lC_MessageStack;

    $this->_page_title = $lC_Language->get('info_contact_heading');
    if (file_exists(DIR_FS_CATALOG . 'templates/' . $this->getCode() . '/javascript/info_contact.js.php')) {
      $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/info_contact.js.php'); 
    }

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_contact'), lc_href_link(FILENAME_INFO, $this->_module));
    }

    if ($_GET[$this->_module] == 'process') {
      $this->_process();
    }
  }

  /* Private methods */
  protected function _process() {
    global $lC_Language, $lC_MessageStack;

    $name = lc_sanitize_string($_POST['name']);
    $email_address = lc_sanitize_string($_POST['email']);
    $inquiry = lc_sanitize_string($_POST['inquiry']);

    if (lc_validate_email_address($email_address)) {
      lc_email(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $lC_Language->get('contact_email_subject'), $inquiry, $name, $email_address);
      lc_redirect(lc_href_link(FILENAME_INFO, 'contact&success=' . urlencode($lC_Language->get('contact_email_sent_successfully')), 'AUTO'));
    } else {
      $lC_MessageStack->add('contact', $lC_Language->get('field_customer_email_address_check_error'));
    }
  }
}
?>