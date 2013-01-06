<?php
/*
  $Id: images.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Products_Images extends lC_Template {

    /* Private variables */
    var $_module = 'images',
        $_group = 'products',
        $_page_title,
        $_page_contents = 'images.php',
        $_page_image = 'table_background_list.gif',
        $_has_header = false,
        $_has_footer = false,
        $_has_box_modules = false,
        $_has_content_modules = false,
        $_show_debug_messages = false;

    /* Class constructor */
    function lC_Products_Images() {
      global $lC_Session, $lC_Language, $lC_Product;

      if (empty($_GET) === false) {
        $id = false;

        $counter = 0;
        foreach ($_GET as $key => $value) {
          $counter++;

          if ($counter < 2) {
            continue;
          }

          if ( (preg_match('/^[0-9]+(#?([0-9]+:?[0-9]+)+(;?([0-9]+:?[0-9]+)+)*)*$/', $key) || preg_match('/^[a-zA-Z0-9 -_]*$/', $key)) && ($key != $lC_Session->getName()) ) {
            $id = $key;
          }

          break;
        }

        if (($id !== false) && lC_Product::checkEntry($id)) {
          $lC_Product = new lC_Product($id);

          $this->addPageTags('keywords', $lC_Product->getTitle());
          $this->addPageTags('keywords', $lC_Product->getModel());

          if ($lC_Product->hasTags()) {
            $this->addPageTags('keywords', $lC_Product->getTags());
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