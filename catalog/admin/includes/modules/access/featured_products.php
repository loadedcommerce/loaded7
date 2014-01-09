<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured_products.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Featured_products extends lC_Access {
  var $_module = 'featured_products',
      $_group = 'products',
      $_icon = 'people.png',
      $_title,
      $_sort_order = 300;

  public function lC_Access_Featured_products() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_featured_products_title');
  }
}
?>