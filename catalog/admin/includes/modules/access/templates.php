<?php
/*
  $Id: templates.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Templates extends lC_Access {
    var $_module = 'templates',
        $_group = 'configuration',
        $_icon = 'default.png',
        $_title,
        $_sort_order = 1100;

    function lC_Access_Templates() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_templates_title');
    }
  }
?>
