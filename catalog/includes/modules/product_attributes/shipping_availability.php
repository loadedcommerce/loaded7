<?php
/*
  $Id: $

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2007 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
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
