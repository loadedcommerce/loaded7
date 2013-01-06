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
