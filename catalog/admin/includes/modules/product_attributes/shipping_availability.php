<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shipping_availability.php v1.0 2013-08-08 datazen $
*/
class lC_ProductAttributes_shipping_availability extends lC_Product_attributes_Admin {
  
  public function __construct() {
    $this->_section = 'shippingSupplierCharacteristics';
  }
    
  public function setFunction($value) {
    global $lC_Database, $lC_Language;

    $string = '';

    $Qstatus = $lC_Database->query('select id, title from :table_shipping_availability where languages_id = :languages_id order by title');
    $Qstatus->bindTable(':table_shipping_availability');
    $Qstatus->bindInt(':languages_id', $lC_Language->getID());
    $Qstatus->execute();

    $array = array();

    while ( $Qstatus->next() ) {
      $array[] = array('id' => $Qstatus->valueInt('id'),
                       'text' => $Qstatus->value('title'));
    }

    if ( !empty($array) ) {
      $string = lc_draw_pull_down_menu('attributes[' . self::getID() . ']', $array, $value, 'class="select full-width"');
    }

    return $string;
  }
}
?>