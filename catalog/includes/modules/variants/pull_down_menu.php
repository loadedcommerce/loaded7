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

  class lC_Variants_pull_down_menu extends lC_Variants_Abstract {
    const ALLOW_MULTIPLE_VALUES = false;
    const HAS_CUSTOM_VALUE = false;

    static public function parse($data) {
      $default_value = null;

      foreach ( $data['data'] as $variant ) {
        if ( $variant['default'] === true ) {
          $default_value = $variant['id'];

          break;
        }
      }

      $string = '<div class="variant_sel" id="variantsBlockData">' . 
                '  <label>' . $data['title'] . '</label>' . 
                lc_draw_pull_down_menu('variants[' . $data['group_id'] . ']', $data['data'], $default_value, 'onchange="refreshVariants();" id="variants_' . $data['group_id'] . '"') .
                '</div>';

      return $string;
    }

    static public function allowsMultipleValues() {
      return self::ALLOW_MULTIPLE_VALUES;
    }

    static public function hasCustomValue() {
      return self::HAS_CUSTOM_VALUE;
    }
  }
?>
