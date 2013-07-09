<?php
/*
  $Id: coupons.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Coupons_Admin class manages customer groups definitions
*/
class lC_Coupons_Admin {
 /*
  * Returns the customer groups datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    
    // replace Qitems array with your own
    
    $Qitems = array('1' => 'Product One',
                    '2' => 'Product Two',
                    '3' => 'Product Three',
                    '4' => 'Product Four',
                    '5' => 'Product Five');
    
    $result = array('aaData' => array());

    foreach ( $Qitems as $key => $value ) {
      $name = '<span>' . $value . '</span>';
      $action = '<span style="padding:3px;"><a href="javascript://" onclick="editGroup(\'' . $key . '\')">' . lc_icon('edit.png') . '</a></span>' .
                '<span style="padding:3px;"><a href="javascript://" onclick="deleteGroup(\'' . $key . '\', \'' . urlencode($value) . '\')">' . lc_icon('trash.png') . '</a></span>' . 
                '<span style="padding-right:8px;"><input type="checkbox" name="batch[]" value="' . $key . '" id="' . $key . '"></span>';         
      $result['aaData'][] = array("$name", "$action"); 
    } 

    return $result;
  } 
}
?>