<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufacturers.php v1.0 2013-08-08 datazen $
*/
class lC_ProductAttributes_manufacturers extends lC_Product_attributes_Admin {
  
  public function __construct() {
    $this->_section = 'dataManagementSettings';
  }
  
  public function setFunction($value, $value2 = null) {
    global $lC_Database, $lC_Language;

    $string = '';

    $Qmanufacturers = $lC_Database->query('select manufacturers_id, manufacturers_name from :table_manufacturers order by manufacturers_name');
    $Qmanufacturers->bindTable(':table_manufacturers');
    $Qmanufacturers->execute();

    $array = array(array('id' => 'NULL',
                         'text' => $lC_Language->get('none')));

    while ( $Qmanufacturers->next() ) {
      $array[] = array('id' => $Qmanufacturers->valueInt('manufacturers_id'),
                       'text' => $Qmanufacturers->value('manufacturers_name'));
    }

    if ( !empty($array) ) {
      $string = lc_draw_pull_down_menu('attributes[' . self::getID() . ']', $array, $value, 'class="select full-width"');
    }

    return $string;
  }
}
?>