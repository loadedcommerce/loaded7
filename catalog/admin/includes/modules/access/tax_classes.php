<?php
/*
  $Id: tax_classes.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Tax_classes extends lC_Access {
    var $_module = 'tax_classes',
        $_group = 'configuration',
        $_icon = 'classes.png',
        $_title,
        $_sort_order = 1000;

    function lC_Access_Tax_classes() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_tax_classes_title');
    }
  }
?>
