<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: store.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Store extends lC_Access {
  var $_module = 'store',
      $_group = 'hidden',
      $_icon = '',
      $_title,
      $_sort_order = 10;

  public function lC_Access_Store() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_store_title');
  }
}
?>