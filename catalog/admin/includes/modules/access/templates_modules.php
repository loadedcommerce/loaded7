<?php
/*
  $Id: templates_modules.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Templates_modules extends lC_Access {
    var $_module = 'templates_modules',
        $_group = 'configuration',
        $_icon = 'modules.png',
        $_title,
        $_sort_order = 1200;

    function lC_Access_Templates_modules() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_templates_modules_title');

      $this->_subgroups = array(array('icon' => 'modules.png',
                                      'title' => $lC_Language->get('access_templates_modules_boxes_title'),
                                      'identifier' => 'set=boxes'),
                                array('icon' => 'windows.png',
                                      'title' => $lC_Language->get('access_templates_modules_content_title'),
                                      'identifier' => 'set=content'));
    }
  }
?>