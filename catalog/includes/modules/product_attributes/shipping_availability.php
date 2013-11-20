<?php
/**
  @package    catalog::modules::product_attributes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shipping_availability.php v1.0 2013-08-08 datazen $
*/
class lC_ProductAttributes_shipping_availability {
  static public function getValue($value) {
    global $lC_Database, $lC_Language;

    $string = '';

    $Qstatus = $lC_Database->query('select title, css_key from :table_shipping_availability where id = :id and languages_id = :languages_id');
    $Qstatus->bindTable(':table_shipping_availability');
    $Qstatus->bindInt(':id', $value);
    $Qstatus->bindInt(':languages_id', $lC_Language->getID());
    $Qstatus->execute();

    if ( $Qstatus->numberOfRows() === 1 ) {
      $string = $Qstatus->value('title');

      if ( !lc_empty($Qstatus->value('css_key')) ) {
        $string = '<span class="' . $Qstatus->value('css_key') . '">' . $string . '</span>';
      }
    }

    return $string;
  }
}
?>