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

class lC_Variants_text_field extends lC_Variants_Abstract {
  const ALLOW_MULTIPLE_VALUES = true;
  const HAS_CUSTOM_VALUE = true;

  static public function parse($data) {
    global $lC_Currencies;

    if (isset($data['simple_option']) && $data['simple_option'] !== false) {
      $options = array();
      $group_id = '';
      $group_title = '';
      unset($data['simple_option']);
      
      foreach($data as $key => $val) {
        if (isset($val['group_title']) && empty($val['group_title']) === false) {
          $group_title = $val['group_title'];
          break;
        }
      }      
      
      $string = '';
      reset($data);
      $cnt = 0;
      foreach($data as $key => $val) {
        $price_ind = ((float)$val['price_modifier'] < 0.00) ? '-' : '+';
        $price_formatted = ((float)$val['price_modifier'] != 0.00) ? $price_ind . $lC_Currencies->format(number_format($val['price_modifier'], DECIMAL_PLACES), $lC_Currencies->getCode()) : null;
        $options[$val['value_id']] = $val['price_modifier']; 
        $group_id = $val['group_id'];
        $group_title = $val['group_title'];
                          
        $string .= '<div class="form-group small-margin-left"><label class="label-control">' . $val['value_title'] . '</label><input class="form-control half-width display-inline small-margin-left" type="text" name="variants[' . $data['group_id'] . '][' . $field['id'] . ']" value="" modifier="' . $val['price_modifier'] . '" onchange="refreshPrice();" id="simple_options_' . $group_id . '_' . $val['value_id'] . '"><span class="small-margin-left">' . $price_formatted . '</span></div>';
        $cnt++;
      }                 
    } else {      
      
      
      $string = '';
      $i = 0;
      foreach ( $data['data'] as $field ) {
        $i++;
        $string .= '<div class="form-group"><label class="label-control">' . $field['text'] . '</label><input type="text" class="form-control" name="variants[' . $data['group_id'] . '][' . $field['id'] . ']" id="variants_' . $data['group_id'] . '_' . $i . '"></div>';
      }
    }

    return $string;
  }

  static public function getGroupTitle($data) {
    return $data['value_title'];
  }

  static public function getValueTitle($data) {
    return $_POST['variants'][$data['group_id']][$data['value_id']];
  }

  static public function allowsMultipleValues() {
    return self::ALLOW_MULTIPLE_VALUES;
  }

  static public function hasCustomValue() {
    return self::HAS_CUSTOM_VALUE;
  }
}
?>
