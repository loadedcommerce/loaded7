<?php
/*
  $Id: backup.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Backup extends lC_Access {
    var $_module = 'backup',
        $_group = 'tools',
        $_icon = 'backup.png',
        $_title,
        $_sort_order = 200;

    function lC_Access_Backup() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_backup_title');
    }
  }
?>