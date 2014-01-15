<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: branding_manager.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Branding_manager extends lC_Access {
  var $_module = 'branding_manager',
      $_group = 'marketing',
      $_icon = '',
      $_title,
      $_sort_order = 100;

  public function lC_Access_Branding_manager() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_branding_manager_title');
  }
}
?>