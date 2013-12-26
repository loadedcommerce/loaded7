<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_variants.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Product_variants extends lC_Access {
  var $_module = 'products',
      $_group = 'products',
      $_icon = 'products.png',
      $_title,
      $_sort_order = 200;

  public function lC_Access_Product_variants() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_product_variants_title');
  }
}
?>