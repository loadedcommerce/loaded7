<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: variants.php v1.0 2013-08-08 datazen $
*/
abstract class lC_Variants_Abstract {
  abstract static public function parse($data);
  abstract static public function allowsMultipleValues();
  abstract static public function hasCustomValue();

  static public function getGroupTitle($data) {
    return $data['group_title'];
  }

  static public function getValueTitle($data) {
    return $data['value_title'];
  }
}

class lC_Variants {
  static public function parse($module, $data) {
    global $lC_Vqmod;
    
    if ( !class_exists('lC_Variants_' . $module) ) {
      if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
        include($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php'));
      } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php'));
      }
    }

    if ( class_exists('lC_Variants_' . $module) ) {
      return call_user_func(array('lC_Variants_' . $module, 'parse'), $data);
    }
  }
  
  static public function parseSimpleOptions($module, $data) {
    global $lC_Vqmod;
    
    if ( !class_exists('lC_Variants_' . $module) ) {
      if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
        include($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php'));
      } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php'));
      }
    }

    $data['simple_option'] = true;

    if ( class_exists('lC_Variants_' . $module) ) {   
      return call_user_func(array('lC_Variants_' . $module, 'parse'), $data);
    }
  }    

  static public function getGroupTitle($module, $data) {
    global $lC_Vqmod;
    
    if ( !class_exists('lC_Variants_' . $module) ) {
      if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
        include($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php'));  
      } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php'));
      }
    }

    if ( class_exists('lC_Variants_' . $module) ) {
      return call_user_func(array('lC_Variants_' . $module, 'getGroupTitle'), $data);
    }

    return $data['group_title'];
  }

  static public function getValueTitle($module, $data) {
    global $lC_Vqmod;
    
    if ( !class_exists('lC_Variants_' . $module) ) {
      if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
        include($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php'));
      } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php'));
      }
    }

    if ( class_exists('lC_Variants_' . $module) ) {
      return call_user_func(array('lC_Variants_' . $module, 'getValueTitle'), $data);
    }

    return $data['value_title'];
  }

  static public function allowsMultipleValues($module) {
    global $lC_Vqmod;
    
    if ( !class_exists('lC_Variants_' . $module) ) {
      if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
        include($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php'));
      } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php'));
      }
    }

    if ( class_exists('lC_Variants_' . $module) ) {
      return call_user_func(array('lC_Variants_' . $module, 'allowsMultipleValues'));
    }

    return false;
  }

  static public function hasCustomValue($module) {
    global $lC_Vqmod;
    
    if ( !class_exists('lC_Variants_' . $module) ) {
      if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
        include($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php'));
      } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php'));
      }
    }

    if ( class_exists('lC_Variants_' . $module) ) {
      return call_user_func(array('lC_Variants_' . $module, 'hasCustomValue'));
    }

    return false;
  }

  static public function defineJavascript($products) {
    global $lC_Currencies;

    $string = '<script>var combos = new Array();' . "\n";

    foreach ( $products as $product_id => $product ) {
   
      $string .= 'combos[' . $product_id . '] = new Array();' . "\n" .
                 'combos[' . $product_id . '] = { price: "' . addslashes($lC_Currencies->displayPrice($product['data']['price'], $product['data']['tax_class_id'])) . '", model: "' . addslashes($product['data']['model']) . '", availability_shipping: ' . (int)$product['data']['availability_shipping'] . ', values: [] };' . "\n";

      foreach ( $product['values'] as $group_id => $variants ) {
        $check_flag = false;

        foreach ( $variants as $variant ) {
          if ( !lC_Variants::hasCustomValue($variant['module']) ) {
            if ( $check_flag === false ) {
              $check_flag = true;

              $string .= 'combos[' . $product_id . ']["values"][' . $group_id . '] = new Array();' . "\n";
            }

            $string .= 'combos[' . $product_id . ']["values"][' . $group_id . '][' . $variant['value_id'] . '] = ' . $variant['value_id'] . ';' . "\n";
          }
        }
      }
    }

    $string .= '</script>';

    return $string;
  }
}
?>