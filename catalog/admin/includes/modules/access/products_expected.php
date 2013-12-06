<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products_expected.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Products_expected extends lC_Access {
  var $_module = 'products_expected',
      $_group = 'products',
      $_icon = 'date.png',
      $_title,
      $_sort_order = 500;

  public function lC_Access_Products_expected() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_products_expected_title');
  }
}
?>