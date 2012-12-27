<?php
/*
  $Id: server_info.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Server_info extends lC_Access {
    var $_module = 'server_info',
        $_group = 'tools',
        $_icon = 'server_info.png',
        $_title,
        $_sort_order = 700;

    function lC_Access_Server_info() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_server_info_title');
    }
  }
?>