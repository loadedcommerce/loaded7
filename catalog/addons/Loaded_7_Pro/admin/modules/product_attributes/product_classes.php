<?php
/*
  $Id: product_classes.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_ProductAttributes_product_classes extends lC_Product_attributes_Admin {
  
  public function __construct() {
    $this->_section = 'dataManagementSettings';
  }
  
  public function setFunction($value) {
    global $lC_Database, $lC_Language;

    $string = '';
    
 //   if (defined('ADDONS_SYSTEM_LOADED_7_PRO_STATUS') && ADDONS_SYSTEM_LOADED_7_PRO_STATUS == '1') {
      $Qclass = $lC_Database->query('select id, name from :table_product_classes where language_id = :language_id order by name');
      $Qclass->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
      $Qclass->bindInt(':language_id', $lC_Language->getID());
      $Qclass->execute();

      $classes = array();
      while ( $Qclass->next() ) {
        $classes[] = array('id' => $Qclass->valueInt('id'),
                           'text' => $Qclass->value('name'));
      }

      if ( !empty($classes) ) {
        $string = lc_draw_pull_down_menu('attributes[' . self::getID() . ']', $classes, $value, 'class="select full-width"');
      }
   // }
    
    return $string;
  }
}
?>