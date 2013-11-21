<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: tell_a_friend.php v1.0 2013-08-08 datazen $
*/
class lC_Products_Tell_a_friend extends lC_Template {

  /* Private variables */
  var $_module = 'tell_a_friend',
      $_group = 'products',
      $_page_title,
      $_page_contents = 'tell_a_friend.php',
      $_page_image = 'table_background_products_new.gif';

  /* Class constructor */
  public function lC_Products_Tell_a_friend() {
    global $lC_Services, $lC_Session, $lC_Language, $lC_Breadcrumb, $lC_Customer, $lC_NavigationHistory, $lC_Product;

    if ((ALLOW_GUEST_TO_TELL_A_FRIEND == '-1') && ($lC_Customer->isLoggedOn() === false)) {
      $lC_NavigationHistory->setSnapshot();

      lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
    }

    $counter = 0;
    foreach ($_GET as $key => $value) {
      $counter++;

      if ($counter < 2) {
        continue;
      }

      if ( (preg_match('/^[0-9]+(#?([0-9]+:?[0-9]+)+(;?([0-9]+:?[0-9]+)+)*)*$/', $key) || preg_match('/^[a-zA-Z0-9 -_]*$/', $key)) && ($key != $lC_Session->getName()) ) {
        if (lC_Product::checkEntry($key) === false) {
          $this->_page_title = $lC_Language->get('product_not_found_heading');
          $this->_page_contents = 'info_not_found.php';
        } else {
          $lC_Product = new lC_Product($key);

          $this->_page_title = $lC_Product->getTitle();

          if ($lC_Services->isStarted('breadcrumb')) {
            $lC_Breadcrumb->add($lC_Product->getTitle(), lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()));
            $lC_Breadcrumb->add($lC_Language->get('breadcrumb_tell_a_friend'), lc_href_link(FILENAME_PRODUCTS, $this->_module . '&' . $lC_Product->getKeyword()));
          }

          if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
            $this->_process();
          }
        }

        break;
      }
    }

    if ($counter < 2) {
      $this->_page_title = $lC_Language->get('product_not_found_heading');
      $this->_page_contents = 'info_not_found.php';
    }
  }

  /* Private methods */
  protected function _process() {
    global $lC_Language, $lC_MessageStack, $lC_Product;

    if (empty($_POST['from_name'])) {
      $lC_MessageStack->add('tell_a_friend', $lC_Language->get('error_tell_a_friend_customers_name_empty'));
    }

    if (!lc_validate_email_address($_POST['from_email_address'])) {
      $lC_MessageStack->add('tell_a_friend', $lC_Language->get('error_tell_a_friend_invalid_customers_email_address'));
    }

    if (empty($_POST['to_name'])) {
      $lC_MessageStack->add('tell_a_friend', $lC_Language->get('error_tell_a_friend_friends_name_empty'));
    }

    if (!lc_validate_email_address($_POST['to_email_address'])) {
      $lC_MessageStack->add('tell_a_friend', $lC_Language->get('error_tell_a_friend_invalid_friends_email_address'));
    }

    if ($lC_MessageStack->size('tell_a_friend') < 1) {
      $email_subject = sprintf($lC_Language->get('email_tell_a_friend_subject'), lc_sanitize_string($_POST['from_name']), STORE_NAME);
      $email_body = sprintf($lC_Language->get('email_tell_a_friend_intro'), lc_sanitize_string($_POST['to_name']), lc_sanitize_string($_POST['from_name']), $lC_Product->getTitle(), STORE_NAME) . "\n\n";

      if (!empty($_POST['message'])) {
        $email_body .= lc_sanitize_string($_POST['message']) . "\n\n";
      }

      $email_body .= sprintf($lC_Language->get('email_tell_a_friend_link'), lc_href_link(HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCTS, $lC_Product->getKeyword(), 'NONSSL', false)) . "\n\n" .
                     sprintf($lC_Language->get('email_tell_a_friend_signature'), STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");

      lc_email(lc_sanitize_string($_POST['to_name']), lc_sanitize_string($_POST['to_email_address']), $email_subject, $email_body, lc_sanitize_string($_POST['from_name']), lc_sanitize_string($_POST['from_email_address']));

      lc_redirect(lc_href_link(FILENAME_PRODUCTS, 'tell_a_friend&' . $lC_Product->getID() . '&success=' . urlencode(sprintf($lC_Language->get('success_tell_a_friend_email_sent'), $lC_Product->getTitle(), lc_output_string_protected($_POST['to_name'])))));
    }
  }
}
?>