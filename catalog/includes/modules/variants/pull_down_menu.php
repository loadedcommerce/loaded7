<?php
/**  
  $Id: pull_down_menu.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Variants_pull_down_menu extends lC_Variants_Abstract {
  const ALLOW_MULTIPLE_VALUES = false;
  const HAS_CUSTOM_VALUE = false;

  static public function parse($data) {
    global $lC_Currencies;
    
    $default_value = null;  
    
    if (isset($data['simple_option']) && $data['simple_option'] !== false) {
      $options = '';
      $group_id = '';
      $group_title = '';
      unset($data['simple_option']);
      foreach($data as $key => $val) {
        $price_ind = ((float)$val['price_modifier'] < 0.00) ? '-' : '+';
        $price_formatted = ((float)$val['price_modifier'] != 0.00) ? $price_ind . $lC_Currencies->format(number_format($val['price_modifier'], DECIMAL_PLACES), $lC_Currencies->getCode()) : null;
        $options .= '<option modifier="' . $val['price_modifier'] . '" value="' . $val['value_id'] . '">' . $val['value_title'] . ' ' . $price_formatted . '</option>'; 
        $group_id = $val['group_id'];
        $group_title = $val['group_title'];
      }
      
      $string = '<div class="variant_sel">' . 
                '  <label>' . $group_title . '</label>' . 
                '  <select style="width:165px !important;" onchange="refreshPrice();" id="simple_options_' . $group_id . '_' . $val['value_id'] . '" name="simple_options[' . $group_id . ']">' . $options . '</select>' . 
                '</div>';      
      
    } else {

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

    }              

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
