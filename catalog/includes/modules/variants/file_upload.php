<?php
/**
  @package    catalog::modules::variants
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: file_upload.php v1.0 2013-08-08 datazen $
*/
class lC_Variants_file_upload extends lC_Variants_Abstract {
  const ALLOW_MULTIPLE_VALUES = false;
  const HAS_CUSTOM_VALUE = false;

  static public function parse($data) {
    global $lC_Currencies;
    
    $default_value = null;
      
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
      
      $string = '<style>.file-inputs { .margin-top-neg(); }</style><div class="margin-left margin-bottom">' .
                '  <table class="full-width">' . 
                '    <tr>' .
                '      <td valign="top" class="third-width"><label class="margin-right margin-top">' . $group_title . '</label></td>' .
                '      <td valign="top">';

      reset($data);
      $cnt = 0;
      foreach($data as $key => $val) {  
        $price_ind = ((float)$val['price_modifier'] < 0.00) ? '-' : '+';
        $price_formatted = ((float)$val['price_modifier'] != 0.00) ? $price_ind . $lC_Currencies->format(number_format($val['price_modifier'], DECIMAL_PLACES), $lC_Currencies->getCode()) : null;
        $options[$val['value_id']] = $val['price_modifier']; 
        $group_id = $val['group_id'];
        $group_title = $val['group_title'];       
                    
        $string .= '        <div id="simple_options_div_' . $group_id . '_' . $val['value_id'] . '" class="radio no-margin-top small-margin-bottom mid-margin-left">' .
                   '         <label>' .
                   '           <input type="file" class="file-inputs btn-primary btn-file" data-filename-placement="inside" title="' . $val['value_title'] . '" default="' . $val['value_title'] . '" name="simple_options[' . $group_id . ']" value="' . $val['value_id'] . '[img]" modifier="' . $val['price_modifier'] . '" onchange="refreshPrice();" id="simple_options_' . $group_id . '_' . $val['value_id'] . '_img">' .
                   '           <input type="hidden" name="simple_options[' . $group_id . ']" value="' . $val['value_id'] . '" modifier="' . $val['price_modifier'] . '" id="simple_options_' . $group_id . '_' . $val['value_id'] . '">' .
                   '         </label><i id="simple_options_remove_' . $group_id . '_' . $val['value_id'] . '" class="fa fa-times small-margin-left red hidden" style="cursor:pointer;" onclick="removeFileUploadRow(\'simple_options_div_' . $group_id . '_' . $val['value_id'] . '\');"></i>' .
                   '       </div>';
        $cnt++;
      }                 
       
      $string .= '       </td>' .   
                 '    </tr>' .
                 '  </table>' .
                 '</div>';                     
      
    } else {      

      $string = '';
      $i = 0;
      foreach ( $data['data'] as $field ) {
        $i++;
        $string .= '<div class="form-group margin-left">
                      <label class="label-control">' . $field['text'] . '</label>
                      <input type="file" class="form-control display-inline two-third-width mid-margin-left mid-margin-right float-right" name="simple_options[' . $data['group_id'] . '][' . $field['id'] . ']" id="variants_' . $data['group_id'] . '_' . $i . '">
                    </div>';
      } 
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