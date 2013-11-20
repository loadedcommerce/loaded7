<?php
/**
  @package    catalog::modules::product_attributes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufacturers.php v1.0 2013-08-08 datazen $
*/
class lC_ProductAttributes_manufacturers {
  static public function getValue($value) {
    global $lC_Database;

    $Qmanufacturer = $lC_Database->query('select manufacturers_name from :table_manufacturers where manufacturers_id = :manufacturers_id');
    $Qmanufacturer->bindTable(':table_manufacturers');
    $Qmanufacturer->bindInt(':manufacturers_id', $value);
    $Qmanufacturer->execute();

    if ( $Qmanufacturer->numberOfRows() === 1 ) {
      return $Qmanufacturer->value('manufacturers_name');
    }
  }
}
?>