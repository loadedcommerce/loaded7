<?php
/*
  $Id: products.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Products_Products extends lC_Template {

    /* Private variables */
    var $_module = 'products',
        $_group = 'products',
        $_page_title,
        $_page_contents = 'info.php',
        $_page_image = 'table_background_list.gif';

    /* Class constructor */
    function lC_Products_Products() {
      global $lC_Database, $lC_Services, $lC_Session, $lC_Language, $lC_Breadcrumb, $lC_Product, $lC_Image, $lC_Currencies;
      
      $template_code = (isset($_SESSION['template']['code']) && $_SESSION['template']['code'] != NULL) ? $_SESSION['template']['code'] : 'default';

      if (empty($_GET) === false) {
        $id = false;

        // PHP < 5.0.2; array_slice() does not preserve keys and will not work with numerical key values, so foreach() is used
        foreach ($_GET as $key => $value) {
          if ( (preg_match('/^[0-9]+(#?([0-9]+:?[0-9]+)+(;?([0-9]+:?[0-9]+)+)*)*$/', $key) || preg_match('/^[a-zA-Z0-9 -_]*$/', $key)) && ($key != $lC_Session->getName()) ) {
            $id = $key;
          }

          break;
        }

        if (($id !== false) && lC_Product::checkEntry($id)) {
          $lC_Product = new lC_Product($id);
          $lC_Product->incrementCounter();

          $this->addPageTags('keywords', $lC_Product->getTitle());
          $this->addPageTags('keywords', $lC_Product->getModel());

          if ($lC_Product->hasTags()) {
            $this->addPageTags('keywords', $lC_Product->getTags());
          }
          
          $this->addOGPTags('type', 'product');
          $this->addOGPTags('title', $lC_Product->getTitle() . ' ' . $lC_Product->getModel());
          $this->addOGPTags('description', $lC_Currencies->displayPrice($lC_Product->getPriceBreak(), $lC_Product->getTaxClassID()) .  ' - ' . $lC_Product->getTitle() . ' ' . lc_clean_html($lC_Product->getDescription()));
          $this->addOGPTags('url', lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword(), 'NONSSL',false,true,true));
          $this->addOGPTags('image', HTTP_SERVER . DIR_WS_CATALOG . 'templates/' . $template_code . '/images/logo.png');
          $this->addOGPTags('image', HTTP_SERVER . DIR_WS_CATALOG . $lC_Image->getAddress($lC_Product->getImage(), 'large'));
          foreach ( $lC_Product->getImages() as $key => $value ) {
            if ($value['default_flag'] == true) continue;
            if(file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($value['image'], 'popup'))){
              $this->addOGPTags('image', HTTP_SERVER . DIR_WS_CATALOG . $lC_Image->getAddress($value['image'], 'large'));
            }
          }
        
          lC_Services_category_path::process($lC_Product->getCategoryID());

          if ($lC_Services->isStarted('breadcrumb')) {
            $lC_Breadcrumb->add($lC_Product->getTitle(), lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $_GET['cPath']);
          }

          $this->_page_title = $lC_Product->getTitle();
        } else {
          $this->_page_title = $lC_Language->get('product_not_found_heading');
          $this->_page_contents = 'info_not_found.php';
        }
      } else {
        $this->_page_title = $lC_Language->get('product_not_found_heading');
        $this->_page_contents = 'info_not_found.php';
      }
    }
  }
?>