<?php
/*
  $Id: lc_cfg_set_boolean_value.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_set_boolean_value($select_array, $default, $key = null) {
    global $lC_Language, $lC_Template;

    $string = '<span class="button-group">';

    $select_array = explode(',', substr($select_array, 6, -1));

    if (isset($_GET['plugins'])) {
      $name = (!empty($key) ? 'plugins[' . $key . ']' : 'plugins_value');
    } else {
      $name = (!empty($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    }

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

    if (!empty($string)) {
   //   $string = substr($string, 0, -6);
    }

    return $string;
  }
?>