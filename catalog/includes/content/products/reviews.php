<?php
/*
  $Id: reviews.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Products_Reviews extends lC_Template {

    /* Private variables */
    var $_module = 'reviews',
        $_group = 'products',
        $_page_title,
        $_page_contents = 'reviews.php',
        $_page_image = 'table_background_reviews_new.gif';

    /* Class constructor */
    function lC_Products_Reviews() {
      global $lC_Services, $lC_Session, $lC_Language, $lC_Breadcrumb, $lC_Product, $lC_Customer, $lC_NavigationHistory;

      if ($lC_Services->isStarted('reviews') === false) {
        lc_redirect(lc_href_link(FILENAME_DEFAULT));
      }

      $this->_page_title = $lC_Language->get('reviews_heading');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_reviews'), lc_href_link(FILENAME_PRODUCTS, $this->_module));
      }

      if (is_numeric($_GET[$this->_module])) {
        if (lC_Reviews::exists($_GET[$this->_module])) {
          $lC_Product = new lC_Product(lC_Reviews::getProductID($_GET[$this->_module]));

          $this->_page_title = $lC_Product->getTitle();
          $this->_page_contents = 'reviews_info.php';

          if ($lC_Services->isStarted('breadcrumb')) {
            $lC_Breadcrumb->add($lC_Product->getTitle(), lc_href_link(FILENAME_PRODUCTS, $this->_module . '=' . $_GET[$this->_module]));
          }
        } else {
          $this->_page_contents = 'reviews_not_found.php';
        }
      } else {
        $counter = 0;
        foreach ($_GET as $key => $value) {
          $counter++;

          if ($counter < 2) {
            continue;
          }

          if ( (preg_match('/^[0-9]+(#?([0-9]+:?[0-9]+)+(;?([0-9]+:?[0-9]+)+)*)*$/', $key) || preg_match('/^[a-zA-Z0-9 -_]*$/', $key)) && ($key != $lC_Session->getName()) ) {
            if (lC_Product::checkEntry($key) === false) {
              $this->_page_contents = 'info_not_found.php';
            } elseif ($_GET[$this->_module] == 'new') {
              if ( ($lC_Customer->isLoggedOn() === false ) && (SERVICE_REVIEW_ENABLE_REVIEWS == 1) ) {
                $lC_NavigationHistory->setSnapshot();

                lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
              }

              $lC_Product = new lC_Product($key);

              $this->_page_title = $lC_Product->getTitle();
              $this->_page_contents = 'reviews_new.php';
              $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/products/reviews_new.php');

              if ($lC_Services->isStarted('breadcrumb')) {
                $lC_Breadcrumb->add($lC_Product->getTitle(), lc_href_link(FILENAME_PRODUCTS, $this->_module . '&' . $lC_Product->getKeyword()));
                $lC_Breadcrumb->add($lC_Language->get('breadcrumb_reviews_new'), lc_href_link(FILENAME_PRODUCTS, $this->_module . '=new&' . $lC_Product->getKeyword()));
              }

              if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
                $this->_process($lC_Product->getID());
              }
            } else {
              $lC_Product = new lC_Product($key);

              $this->_page_title = $lC_Product->getTitle();
              $this->_page_contents = 'product_reviews.php';

              if ($lC_Services->isStarted('breadcrumb')) {
                $lC_Breadcrumb->add($lC_Product->getTitle(), lc_href_link(FILENAME_PRODUCTS, $this->_module . '&' . $lC_Product->getKeyword()));
              }
            }
          }

          break;
        }

        if ($counter < 2) {
          if (lC_Reviews::exists() === false) {
            $this->_page_contents = 'reviews_not_found.php';
          }
        }
      }
    }

/* Private methods */

    function _process($id) {
      global $lC_Language, $lC_MessageStack, $lC_Customer, $lC_Reviews;

      $data = array('products_id' => $id);

      if ($lC_Customer->isLoggedOn()) {
        $data['customer_id'] = $lC_Customer->getID();
        $data['customer_name'] = $lC_Customer->getName();
      } else {
        $data['customer_id'] = '0';
        $data['customer_name'] = $_POST['customer_name'];
      }

      if (strlen(trim($_POST['review'])) < REVIEW_TEXT_MIN_LENGTH) {
        $lC_MessageStack->add('reviews', sprintf($lC_Language->get('js_review_text'), REVIEW_TEXT_MIN_LENGTH));
      } else {
        $data['review'] = $_POST['review'];
      }

      if (($_POST['rating'] < 1) || ($_POST['rating'] > 5)) {
        $lC_MessageStack->add('reviews', $lC_Language->get('js_review_rating'));
      } else {
        $data['rating'] = $_POST['rating'];
      }

      if ($lC_MessageStack->size('reviews') < 1) {
        if ($lC_Reviews->is_moderated === true) {
          $data['status'] = '0';

          $lC_MessageStack->add('reviews', $lC_Language->get('success_review_moderation'), 'success');
        } else {
          $data['status'] = '1';

          $lC_MessageStack->add('reviews', $lC_Language->get('success_review_new'), 'success');
        }

        lC_Reviews::saveEntry($data);

        lc_redirect(lc_href_link(FILENAME_PRODUCTS, 'reviews&' . $id));
      }
    }
  }
?>