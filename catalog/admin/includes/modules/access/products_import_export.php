<?php
/*
  $Id: products_import_export.php v1.0 2013-12-03 resultsonlyweb $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Products_import_export extends lC_Access {
    var $_module = 'products_import_export',
        $_group = 'products',
        $_icon = 'products.png',
        $_title,
        $_sort_order = 900;

    function lC_Access_Products_import_export() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_products_import_export_title');
    }
  }
?>