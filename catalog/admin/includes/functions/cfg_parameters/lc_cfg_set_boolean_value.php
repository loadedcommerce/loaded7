<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_boolean_value.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_boolean_value($select_array, $default, $key = null) {
  global $lC_Language, $lC_Template;

  $string = '<span class="button-group">';

  $select_array = explode(',', substr($select_array, 6, -1));

  $name = (!empty($key) ? 'configuration[' . $key . ']' : 'configuration_value');

  for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
    $value = trim($select_array[$i]);

    if (strpos($value, '\'') !== false) {
      $value = substr($value, 1, -1);
    } else {
      $value = (int)$value;
    }

    $select_array[$i] = $value;

    if ($value === -1) {
      $value = $lC_Language->get('parameter_false');
    } elseif ($value === 0) {
      $value = $lC_Language->get('parameter_optional');
    } elseif ($value === 1) {
      $value = $lC_Language->get('parameter_true');
    }

    $string .= '<label for="' . $name . '-' . $i . '" class="button green-active">' . $value;
    $string .= '<input type="radio" name="' . $name . '" id="' . $name . '-' . $i . '" value="' . $select_array[$i] . '"';

    if ($default == $select_array[$i]) $string .= ' checked="checked"';

    $string .= '></label>';
  }
  $string .= '</span>';

  return $string;
}
?>