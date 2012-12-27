<?php
/*
  $Id: images.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Images extends lC_Access {
    var $_module = 'images',
        $_group = 'tools',
        $_icon = 'image.png',
        $_title,
        $_sort_order = 600;

    function lC_Access_Images() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_images_title');
    }
  }
?>
