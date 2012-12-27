<?php
/*
  $Id: languages.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Languages extends lC_Access {
    var $_module = 'languages',
        $_group = 'configuration',
        $_icon = 'locale.png',
        $_title,
        $_sort_order = 700;

    function lC_Access_Languages() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_languages_title');
    }
  }
?>
