<?php
/**
  @package    catalog::admin::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: output.php v1.0 2013-08-08 datazen $
*/
class output {
  
  public function __contruct() {
  }
  
  public function drawBigMenu($_section = NULL, $_class = NULL) {
    global $lC_Access, $lC_Language;

    $access = array();
    if ( isset($_SESSION['admin']) ) {
      $access = lC_Access::getLevels();
    }
    ksort($access);

    switch ($_section) {
      case 'configuration':  // settings menu
      case 'tools':  // settings menu
        $mOpenClass = 'cfg-open';
        $newArr = array();
        foreach($access as $key => $value) {
          if ($key != 'configuration' && $key != 'tools' && $key != 'store') continue;
          $newArr[$key] = $value;
        }
        $access = $newArr;
        break;

      default:  // main big menu
        $mOpenClass = '';
        $newArr = array();
        foreach($access as $key => $value) {
          if ($key != 'configuration' && $key != 'tools' && $key != 'store') { } else { continue; }
          $newArr[$key] = $value;
        }
        
        // custom sort
        $access = array();
        if (array_key_exists('orders', $newArr)) $access['orders'] = $newArr['orders'];
        if (array_key_exists('customers', $newArr)) $access['customers'] = $newArr['customers'];
        if (array_key_exists('products', $newArr)) $access['products'] = $newArr['products'];
        if (array_key_exists('content', $newArr)) $access['content'] = $newArr['content'];
        if (array_key_exists('marketing', $newArr)) $access['marketing'] = $newArr['marketing'];
        if (array_key_exists('reports', $newArr)) $access['reports'] = $newArr['reports'];

        // include any other added sections
        foreach($newArr as $key => $value) {
          if (array_key_exists($key, $access)) continue;
          $access[$key] = $value;  
        }
        
    }

    $output = '';
    foreach ( $access as $group => $links ) {
      ksort($links);

      if ($group == 'hidden') continue;
      
      $output .= '<li class="with-right-arrow">';
      $output .= '  <span><span class="list-count" id="list-count-' . $group . '">' . count($links) . '</span>' . lC_Access::getGroupTitle($group) . '</span>';
      $output .= '  <ul class="big-menu ' . $_class . '">';

      foreach ( $links as $link) {
        
        if ($link['module'] == 'images') continue; // To hide image manager from Big menu

        if ($link['title'] == $lC_Language->get('access_orders_title') ||
            $link['title'] == $lC_Language->get('access_products_title') ||
            $link['title'] == $lC_Language->get('access_customers_title')) {
          $link['title'] .= ' ' . $lC_Language->get('text_list');
        }
        
        if (count($link['subgroups']) > 0 && $link['module'] != 'configuration') {
          $output .= '<li class="with-right-arrow">';
          $output .= '<span><span class="list-count" id="list-count-' . $link['title'] . '">' . count($link['subgroups']) . '</span>' . $link['title'] . '</span>';
        } else {
          $output .= '<li><a class="' . $mOpenClass . '" id="big-menu_' . str_replace(" ", "_", strtolower($link['title'])) . '" href="' . lc_href_link_admin(FILENAME_DEFAULT, $link['module']) . '">';
          $output .= '<span>' . $link['title'] . '</span></a>';
        }

        if ( is_array($link['subgroups']) && !empty($link['subgroups']) ) {
          $output .= '<ul class="big-menu ' . $_class . '">';
          foreach ( $link['subgroups'] as $subgroup ) {
            if (substr($subgroup['identifier'], 0, 1) == '?') {
              $output .= '<li><a class="' . $mOpenClass . '" id="big-menu_' . str_replace(" ", "_", strtolower($subgroup['title'])) . '" href="' . lc_href_link_admin(FILENAME_DEFAULT, str_replace('?', '', $subgroup['identifier'])) . '">' . $subgroup['title'] . '</a></li>' . "\n";
            } else {
              $output .= '<li><a class="' . $mOpenClass . '" id="big-menu_' . str_replace(" ", "_", strtolower($subgroup['title'])) . '" href="' . lc_href_link_admin(FILENAME_DEFAULT, $link['module'] . '&' . $subgroup['identifier']) . '">' . $subgroup['title'] . '</a></li>' . "\n";
            }
          }
          $output .= '</ul>' . "\n";
        }
        $output .= '</li>' . "\n";
      }
      $output .= '</ul>' . "\n";
      $output .= '</li>' . "\n";
    }

    return $output;
  }
}
?>