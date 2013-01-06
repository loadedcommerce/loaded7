<?php
/*
  $Id: products_expected.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Products_expected extends lC_Access {
    var $_module = 'products_expected',
        $_group = 'products',
        $_icon = 'date.png',
        $_title,
        $_sort_order = 300;

    function lC_Access_Products_expected() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_products_expected_title');
    }
  }
?>