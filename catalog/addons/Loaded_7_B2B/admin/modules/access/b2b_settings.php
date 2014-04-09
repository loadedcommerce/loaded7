<?php
/**
  @package    admin::modules::access
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: b2b_settings.php v1.0 2013-08-08 datazen $
*/
class lC_Access_B2b_settings extends lC_Access {
  var $_module = 'b2b_settings',
      $_group = 'configuration',
      $_icon = 'settings.png',
      $_title,
      $_sort_order = 100;

  public function lC_Access_B2b_settings() {
    global $lC_Database, $lC_Language;

    $this->_title = $lC_Language->get('access_b2b_settings_title');
  }
}
?>