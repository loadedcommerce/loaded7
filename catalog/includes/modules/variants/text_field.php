<?php
/**
  @package    catalog::modules::variants
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: text_field.php v1.0 2013-08-08 datazen $
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
                          
        $string .= '<div class="form-group margin-left">
                      <label class="label-control">' . $val['group_title'] . '</label>
                      <input class="form-control display-inline two-third-width mid-margin-left mid-margin-right float-right" type="text" name="variants[' . $data['group_id'] . '][' . $field['id'] . ']" value="" modifier="' . $val['price_modifier'] . '" onchange="refreshPrice();" id="simple_options_' . $group_id . '_' . $val['value_id'] . '">
                      <span class="small-margin-left">' . $price_formatted . '</span>
                    </div>';
        $cnt++;
      }                 
    } else {      
      
      $string = '';
      foreach ( $data['data'] as $field ) {
        $string .= '<div class="form-group margin-left">
                      <label class="label-control">' . $data['title']  . '</label>
                      <input type="text" class="form-control display-inline two-third-width mid-margin-left mid-margin-right float-right" name="variants[' . $data['group_id'] . '][' . $field['id'] . ']" id="variants_' . $data['group_id'] . '_' . $field['id'] . '">
                    </div>';
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