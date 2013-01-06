<?php
/*
  $Id: tempaltes_modules_layout.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Templates_modules_layout extends lC_Access {
    var $_module = 'templates_modules_layout',
        $_group = 'configuration',
        $_icon = 'windows.png',
        $_title,
        $_sort_order = 1300;

    function lC_Access_Templates_modules_layout() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_templates_modules_layout_title');

      $this->_subgroups = array(array('icon' => 'modules.png',
                                      'title' => $lC_Language->get('access_templates_modules_layout_boxes_title'),
                                      'identifier' => 'set=boxes'),
                                array('icon' => 'windows.png',
                                      'title' => $lC_Language->get('access_templates_modules_layout_content_title'),
                                      'identifier' => 'set=content'));
    }
  }
?>