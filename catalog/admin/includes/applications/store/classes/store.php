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
  * Returns the addons datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;
    
    $media = $_GET['media'];

    $Qaddons = self::getAvailbleAddons(); 
    
    $result = array('aaData' => array());
    foreach ( $Qaddons as $key => $value ) {
      
      $thumb = $value['thumbnail'];
      $title = $value['title'];
      $desc = substr($value['description'], 0, 60) . '...';
      $action = 'action';
                 
      $result['aaData'][] = array("$thumb", "$title", "$desc", "$action");
    }

    return $result;
  }
 /*
  * Returns the app store menu listing
  *
  * @access public
  * @return string
  */
  public static function drawMenu() {
    foreach ( self::getAllTypes() as $key => $type ) {
      
      $menu .= '<li class="message-menu" id="menuGroup' . $type['text'] . '">' .
               '  <span class="message-status" style="padding-top:14px;">' .
               '     <a href="javascript://" onclick="showType(\'' . (int)$type['id'] . '\', \'' . lc_output_string_protected($type['text']) . '\');" class="new-message" title=""></a>' .
               '   </span>' .
               '   <a id="menuLink' . (int)$type['id'] . '" href="javascript://" onclick="showType(\'' . (int)$type['id'] . '\', \'' . lc_output_string_protected($type['text']) . '\');">' .
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

    $types = array(array('id' => '0', 'text' => 'Payment', 'icon' => ''),
                   array('id' => '1', 'text' => 'Shipping', 'icon' => ''),
                   array('id' => '2', 'text' => 'Themes', 'icon' => ''),
                   array('id' => '3', 'text' => 'Checkout', 'icon' => ''),
                   array('id' => '4', 'text' => 'Catalog', 'icon' => ''),
                   array('id' => '5', 'text' => 'Admin', 'icon' => ''),
                   array('id' => '6', 'text' => 'Reports', 'icon' => ''),
                   array('id' => '7', 'text' => 'Connectors', 'icon' => ''),
                   array('id' => '8', 'text' => 'Other', 'icon' => ''));

    return $types;
  }  
  
 /*
  * Get all the app store available add ons
  *
  * @access public
  * @return array
  */
  public static function getAvailbleAddons() {
    
    $desc = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. Sed lectus.';

    $types = array(array('id' => '1', 
                         'type' => 'payment', 
                         'title' => 'Loaded Payments', 
                         'description' => $desc, 
                         'rating' => '5', 
                         'author' => 'Loaded Commerce, LLC', 
                         'thumbnail' => 'loadedpayments.png', 
                         'version' => '1.0.1',
                         'installed' => '1'),                         

                   array('id' => '2', 
                         'type' => 'payment', 
                         'title' => 'PayPal', 
                         'description' => $desc, 
                         'rating' => '5', 
                         'author' => 'PayPal, Inc.', 
                         'thumbnail' => 'paypal.png', 
                         'version' => '2.1.3',
                         'installed' => '1'),   
                         
                   array('id' => '3', 
                         'type' => 'payment', 
                         'title' => 'Other Payments', 
                         'description' => $desc, 
                         'rating' => '4', 
                         'author' => 'Even More Corp.', 
                         'thumbnail' => 'otherpay.png', 
                         'version' => '5.0.3',
                         'installed' => '0'),
                         
                   array('id' => '4', 
                         'type' => 'payment', 
                         'title' => 'More Payments', 
                         'description' => $desc, 
                         'rating' => '3', 
                         'author' => 'Acme, Inc.', 
                         'thumbnail' => 'morepay.png', 
                         'version' => '3.1.4',
                         'installed' => '0'),
                         
                   array('id' => '5', 
                         'type' => 'payment', 
                         'title' => 'I.O.U. Payments', 
                         'description' => $desc, 
                         'rating' => '3', 
                         'author' => 'Wadayawant, Inc.', 
                         'thumbnail' => 'iou.png', 
                         'version' => '5.7.7',
                         'installed' => '0'),
                         
                   array('id' => '6', 
                         'type' => 'payment', 
                         'title' => 'Please Pay Me', 
                         'description' => $desc, 
                         'rating' => '2', 
                         'author' => 'Morners, Inc.', 
                         'thumbnail' => 'ppme.png', 
                         'version' => '1.0.1',
                         'installed' => '0'                                                                                                                          
                         ));

    return $types;
  }   
}
?>