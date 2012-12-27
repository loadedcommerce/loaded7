<?php
/*
  $Id: product_variants.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Product_variants extends lC_Access {
    var $_module = 'product_variants',
        $_group = 'products',
        $_icon = 'attach.png',
        $_title,
        $_sort_order = 500;

    function lC_Access_Product_variants() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_product_variants_title');
    }
  }
?>
