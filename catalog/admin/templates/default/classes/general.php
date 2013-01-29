<?php
/*
  $Id: general.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @method The lC_Search_Admin class is for AJAX remote program control
*/

class lC_General_Admin {
 /*
  * Returns the search results from database
  *
  * @param string $_GET['q'] The search string
  * @access public
  * @return json
  */
  public static function find($search) {
    $result = array();
    $result['html'] = $search;
    
    return $result;
  }
}
?>