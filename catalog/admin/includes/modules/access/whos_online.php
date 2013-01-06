<?php
/*
  $Id: whos_online.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Whos_online extends lC_Access {
    var $_module = 'whos_online',
        $_group = 'reports',
        $_icon = 'people.png',
        $_title,
        $_sort_order = 200;

    function lC_Access_Whos_online() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_whos_online_title');
    }
  }
?>