<?php
/*
  $Id: newsletters.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Newsletters extends lC_Access {
    var $_module = 'newsletters',
        $_group = 'marketing',
        $_icon = 'email_send.png',
        $_title,
        $_sort_order = 200;

    function lC_Access_Newsletters() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_newsletters_title');
    }
  }
?>