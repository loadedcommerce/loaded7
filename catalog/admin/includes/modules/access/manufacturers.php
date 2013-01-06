<?php
/*
  $Id: manufacturers.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Manufacturers extends lC_Access {
    var $_module = 'manufacturers',
        $_group = 'products',
        $_icon = 'manufacturers.png',
        $_title,
        $_sort_order = 600;

    function lC_Access_Manufacturers() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_manufacturers_title');
    }
  }
?>
