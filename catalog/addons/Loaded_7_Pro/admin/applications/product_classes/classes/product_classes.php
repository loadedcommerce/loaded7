<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_classes.php v1.0 2013-08-08 datazen $
*/
class lC_Product_classes_Admin {
 /*
  * Returns the datatable data for listings
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
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $key . '" id="' . $key . '"></td>';
      $name = '<td>' . $value . '</td>';
      $action = '<td><a href="javascript://" onclick="editGroup(\'' . $key . '\')">' . lc_icon('edit.png') . '</a></td>' .
                '<td><a href="javascript://" onclick="deleteGroup(\'' . $key . '\', \'' . urlencode($value) . '\')">' . lc_icon('trash.png') . '</a></td>';
                
      $result['aaData'][] = array("$check", "$name", "$action"); 
    } 

    return $result;
  } 
}
?>