<?php
/*
  $Id: shipping_availability.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_ProductAttributes_shipping_availability extends lC_Product_attributes_Admin {
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
        $string = lc_draw_pull_down_menu('attributes[' . self::getID() . ']', $array, $value, 'class="input with-small-padding"');
      }

      return $string;
    }
  }
?>