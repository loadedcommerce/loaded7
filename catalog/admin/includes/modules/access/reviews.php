<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: reviews.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Reviews extends lC_Access {
  var $_module = 'reviews',
      $_group = 'products',
      $_icon = 'reviews.png',
      $_title,
      $_sort_order = 700;

  public function lC_Access_Reviews() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_reviews_title');
  }
}
?>