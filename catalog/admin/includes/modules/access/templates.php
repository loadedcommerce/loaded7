<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: templates.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Templates extends lC_Access {
  var $_module = 'templates',
      $_group = 'configuration',
      $_icon = 'default.png',
      $_title,
      $_sort_order = 900;

  public function lC_Access_Templates() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_templates_title');
  }
}
?>