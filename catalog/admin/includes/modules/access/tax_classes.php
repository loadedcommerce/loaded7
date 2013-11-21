<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: tax_classes.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Tax_classes extends lC_Access {
  var $_module = 'tax_classes',
      $_group = 'configuration',
      $_icon = 'classes.png',
      $_title,
      $_sort_order = 500;

  public function lC_Access_Tax_classes() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_tax_classes_title');
  }
}
?>