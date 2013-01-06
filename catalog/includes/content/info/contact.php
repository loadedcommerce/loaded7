<?php
/*
  $Id: contact.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Info_Contact extends lC_Template {

    /* Private variables */
    var $_module = 'contact',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'info_contact.php',
        $_page_image = 'table_background_contact_us.gif';

    /* Class constructor */
    function lC_Info_Contact() {
      global $lC_Services, $lC_Language, $lC_Breadcrumb;

      $this->_page_title = $lC_Language->get('info_contact_heading');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_contact'), lc_href_link(FILENAME_INFO, $this->_module));
      }

      if ($_GET[$this->_module] == 'process') {
        $this->_process();
      }
    }

    /* Private methods */
    function _process() {
      global $lC_Language, $lC_MessageStack;

      $name = lc_sanitize_string($_POST['name']);
      $email_address = lc_sanitize_string($_POST['email']);
      $enquiry = lc_sanitize_string($_POST['enquiry']);

      if (lc_validate_email_address($email_address)) {
        lc_email(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $lC_Language->get('contact_email_subject'), $enquiry, $name, $email_address);

        lc_redirect(lc_href_link(FILENAME_INFO, 'contact=success', 'AUTO'));
      } else {
        $lC_MessageStack->add('contact', $lC_Language->get('field_customer_email_address_check_error'));
      }
    }
  }
?>