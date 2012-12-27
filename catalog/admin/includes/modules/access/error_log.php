<?php
/*
  $Id: error_log.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Error_log extends lC_Access {
    var $_module = 'error_log',
        $_group = 'tools',
        $_icon = 'error.png',
        $_title,
        $_sort_order = 400;

    function lC_Access_Error_log() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_error_log_title');
    }
  }
?>