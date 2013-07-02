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
      
      $string = '<table border="0" cellspacing="0" cellpadding="2" style="padding-bottom:10px;">' .
                '  <tr>' .
                '    <td valign="top"><label>' . $group_title . '</label></td>' .
                '    <td valign="top" style="vertical-align:middle; padding-left:26px;">';

      reset($data);
      $cnt = 0;
      foreach($data as $key => $val) {
        $price_ind = ((float)$val['price_modifier'] < 0.00) ? '-' : '+';
        $price_formatted = ((float)$val['price_modifier'] != 0.00) ? $price_ind . $lC_Currencies->format(number_format($val['price_modifier'], DECIMAL_PLACES), $lC_Currencies->getCode()) : null;
        $options[$val['value_id']] = $val['price_modifier']; 
        $group_id = $val['group_id'];
        $group_title = $val['group_title'];
                          
        $string .= '<div><input style="margin:4px 0;" type="text" name="simple_options[' . $group_id . ']" value="" modifier="' . $val['price_modifier'] . '" onchange="refreshPrice();" id="simple_options_' . $group_id . '_' . $val['value_id'] . '"><span style="font-size:.9em;">' . ' ' . $val['value_title'] . ' ' . $price_formatted . '</span></div>';
        $cnt++;
      }                 
       
      $string .= '    </td>' .
                 '  </tr>' .
                 '</table>';                     
      
    } else {      
      
      
      $string = '<table border="0" cellspacing="0" cellpadding="2">';

      $i = 0;

      foreach ( $data['data'] as $field ) {
        $i++;

        $string .= '  <tr>' .
                   '    <td width="100">' . $field['text'] . ':</td>' .
                   '    <td>' . lc_draw_input_field('variants[' . $data['group_id'] . '][' . $field['id'] . ']', null, 'id="variants_' . $data['group_id'] . '_' . $i . '"') . '</td>' .
                   '  </tr>';
      }

      $string .= '</table>';
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
