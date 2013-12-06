<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: templates_modules_layout.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Templates_modules_layout extends lC_Access {
  var $_module = 'templates_modules_layout',
      $_group = 'configuration',
      $_icon = 'windows.png',
      $_title,
      $_sort_order = 1100;

  public function lC_Access_Templates_modules_layout() {
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