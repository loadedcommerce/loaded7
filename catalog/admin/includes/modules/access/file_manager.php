<?php
/*
  $Id: file_manager.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_File_manager extends lC_Access {
    var $_module = 'file_manager',
        $_group = 'tools',
        $_icon = 'file_manager.png',
        $_title,
        $_sort_order = 500;

    function lC_Access_File_manager() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_file_manager_title');
    }
  }
?>
