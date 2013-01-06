<?php
/*
  $Id: variants.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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
      if ( !class_exists('lC_Variants_' . $module) ) {
        if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
          include(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php');
        } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
          include(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php');
        }
      }

      if ( class_exists('lC_Variants_' . $module) ) {
        return call_user_func(array('lC_Variants_' . $module, 'parse'), $data);
      }
    }

    static public function getGroupTitle($module, $data) {
      if ( !class_exists('lC_Variants_' . $module) ) {
        if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
          include(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php');  
        } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
          include(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php');
        }
      }

      if ( class_exists('lC_Variants_' . $module) ) {
        return call_user_func(array('lC_Variants_' . $module, 'getGroupTitle'), $data);
      }

      return $data['group_title'];
    }

    static public function getValueTitle($module, $data) {
      if ( !class_exists('lC_Variants_' . $module) ) {
        if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
          include(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php');
        } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
          include(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php');
        }
      }

      if ( class_exists('lC_Variants_' . $module) ) {
        return call_user_func(array('lC_Variants_' . $module, 'getValueTitle'), $data);
      }

      return $data['value_title'];
    }

    static public function allowsMultipleValues($module) {
      if ( !class_exists('lC_Variants_' . $module) ) {
        if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
          include(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php');
        } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
          include(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php');
        }
      }

      if ( class_exists('lC_Variants_' . $module) ) {
        return call_user_func(array('lC_Variants_' . $module, 'allowsMultipleValues'));
      }

      return false;
    }

    static public function hasCustomValue($module) {
      if ( !class_exists('lC_Variants_' . $module) ) {
        if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php')) {
          include(DIR_FS_TEMPLATE . 'includes/modules/variants/' . basename($module) . '.php');
        } else if ( file_exists(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php') ) {
          include(DIR_FS_CATALOG . 'includes/modules/variants/' . basename($module) . '.php');
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