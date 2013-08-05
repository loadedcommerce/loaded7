<?php
/*
  $Id: lc_cfg_set_num_pulldown_menu.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
  function lc_cfg_set_num_pulldown_menu($default, $key = null) {
    global $lC_Database;
    
    $css_class = 'class="input with-small-padding"';
    
    $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

    $i = 0;
    $array = array();
    for ($i = 1; $i <= 20; $i++) {

      $array[] = array('id' => $i,
                        'text' => $i);
    }

    return lc_draw_pull_down_menu($name, $array, $default, $css_class);
  }

?>