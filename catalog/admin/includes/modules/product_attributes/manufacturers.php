<?php
/*
  $Id: manufacturers.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_ProductAttributes_manufacturers extends lC_Product_attributes_Admin {
    public function setFunction($value) {
      global $lC_Database, $lC_Language;

      $string = '';

      $Qmanufacturers = $lC_Database->query('select manufacturers_id, manufacturers_name from :table_manufacturers order by manufacturers_name');
      $Qmanufacturers->bindTable(':table_manufacturers');
      $Qmanufacturers->execute();

      $array = array(array('id' => '',
                           'text' => $lC_Language->get('none')));

      while ( $Qmanufacturers->next() ) {
        $array[] = array('id' => $Qmanufacturers->valueInt('manufacturers_id'),
                         'text' => $Qmanufacturers->value('manufacturers_name'));
      }

      if ( !empty($array) ) {
        $string = lc_draw_pull_down_menu('attributes[' . self::getID() . ']', $array, $value, 'class="input with-small-padding"');
      }

      return $string;
    }
  }
?>