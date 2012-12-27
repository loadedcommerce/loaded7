<?php
/*
  $Id: lc_cfg_set_textarea_field.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_set_textarea_field($default, $key = null) {

    if (isset($_GET['plugins'])) {
      $name = (!empty($key) ? 'plugins[' . $key . ']' : 'plugins_value');
    } else {
      $name = (!empty($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    }

    return lc_draw_textarea_field($name, $default, 35, 5);
  }
?>
