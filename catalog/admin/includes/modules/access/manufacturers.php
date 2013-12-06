<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufacturers.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Manufacturers extends lC_Access {
  var $_module = 'manufacturers',
      $_group = 'products',
      $_icon = 'manufacturers.png',
      $_title,
      $_sort_order = 600;

  public function lC_Access_Manufacturers() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_manufacturers_title');
  }
}
?>
