<?php
/*
  $Id: tax_classes.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
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
