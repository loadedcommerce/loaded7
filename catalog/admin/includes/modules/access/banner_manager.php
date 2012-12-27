<?php
/*
  $Id: banner_manager.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Banner_manager extends lC_Access {
    var $_module = 'banner_manager',
        $_group = 'marketing',
        $_icon = 'windows.png',
        $_title,
        $_sort_order = 100;

    function lC_Access_Banner_manager() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_banner_manager_title');
    }
  }
?>
