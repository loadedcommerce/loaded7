<?php
/*
  $Id: lc_cfg_set_weight_classes_pulldown_menu.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_set_weight_classes_pulldown_menu($default, $key = null) {
    global $lC_Database, $lC_Language;
    
    $css_class = 'class="input with-small-padding"';

    $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

    $weight_class_array = array();

    foreach (lC_Weight::getClasses() as $class) {
      $weight_class_array[] = array('id' => $class['id'],
                                    'text' => $class['title']);
    }

    return lc_draw_pull_down_menu($name, $weight_class_array, $default, $css_class);
  }
?>