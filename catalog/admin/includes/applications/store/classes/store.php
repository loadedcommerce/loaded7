<?php
/*
  $Id: store.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
*/
class lC_Store_Admin { 

 /*
  * Returns the app store menu listing
  *
  * @access public
  * @return string
  */
  public static function drawMenu() {
    foreach ( lc_toObjectInfo(self::getAllTypes())->get('entries') as $type ) {
      $menu .= '<li class="message-menu" id="menuGroup' . $type['text'] . '">' .
               '  <span class="message-status" style="padding-top:14px;">' .
               '     <a href="javascript://" onclick="showType(\'' . (int)$type['id'] . '\', \'' . lc_output_string_protected($type['text']) . '\');" class="new-message" title=""></a>' .
               '   </span>' .
               '   <a id="menuLink' . (int)$type['id'] . '" href="javascript://" onclick="showGroup(\'' . (int)$type['id'] . '\', \'' . lc_output_string_protected($type['text']) . '\');">' .
               '     <br><strong>' . lc_output_string_protected($type['text']) . '</strong>' .
               '   </a>' .
               ' </li>';
    }

    return $menu;
  }
  
 /*
  * Get all the app store types
  *
  * @access public
  * @return array
  */
  public static function getAllTypes() {

    $result = array('entries' => array('id' => '0', 'text' => 'Payment'),
                                 array('id' => '1', 'text' => 'Shipping'),
                                 array('id' => '2', 'text' => 'Themes'),
                                 array('id' => '3', 'text' => 'Checkout'),
                                 array('id' => '4', 'text' => 'Catalog'),
                                 array('id' => '5', 'text' => 'Admin'),
                                 array('id' => '6', 'text' => 'Reports'),
                                 array('id' => '7', 'text' => 'Connectors'),
                                 array('id' => '8', 'text' => 'Other'));
    return $result;
  }  
}
?>