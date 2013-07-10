<?php
/*
  $Id: output.php v1.0 2012-08-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  Based on the Developr theme
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
        $mOpenClass = 'cfg-open';
        $newArr = array();
        foreach($access as $key => $value) {
          if ($key != 'configuration' && $key != 'store') continue;
          $newArr[$key] = $value;
        }
        $access = $newArr;
        break;

      default:  // main big menu
        $mOpenClass = '';
        $newArr = array();
        foreach($access as $key => $value) {
          if ($key == 'configuration' || $key == 'store') continue;
          $newArr[$key] = $value;
        }
        $access = $newArr;
    }

    ksort($access);
    $output = '';
    foreach ( $access as $group => $links ) {
      ksort($links);

      if ($group == 'hidden') continue;
      
      $output .= '<li class="with-right-arrow">';
      $output .= '  <span><span class="list-count">' . count($links) . '</span>' . lC_Access::getGroupTitle($group) . '</span>';
      $output .= '  <ul class="big-menu ' . $_class . '">';

      foreach ( $links as $link) {
        
        if ($link['title'] == $lC_Language->get('access_orders_title') ||
            $link['title'] == $lC_Language->get('access_customers_title')) {
          $link['title'] .= ' ' . $lC_Language->get('text_list');
        }
        
        if (count($link['subgroups']) > 1 && $link['module'] != 'configuration') {
          $output .= '<li class="with-right-arrow">';
          $output .= '<span><span class="list-count">' . count($link['subgroups']) . '</span>' . $link['title'] . '</span>';
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