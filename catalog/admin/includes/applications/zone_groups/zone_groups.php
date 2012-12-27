<?php
/*
  $Id: zone_groups.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Zone_groups class manages the zone groups GUI
*/
require_once('includes/applications/zone_groups/classes/zone_groups.php');

class lC_Application_Zone_groups extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'zone_groups',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language, $breadcrumb_string;

    $this->_page_title = $lC_Language->get('heading_title');

    $breadcrumb_array = array(lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module), $lC_Language->get('heading_title'))); 
    if ( !empty($_GET[$this->_module]) && is_numeric($_GET[$this->_module]) ) {
      $this->_page_contents = 'entries.php';
      $this->_page_title = lC_Zone_groups_Admin::get($_GET[$this->_module], 'geo_zone_name');
      $breadcrumb_array[] = lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $this->_page_contents ), $this->_page_title);
    }
    $breadcrumb_string = '<ul>';
    foreach ($breadcrumb_array as $key => $value) {
      $breadcrumb_string .= '<li>' . $value . '</li>';
    }  
    $breadcrumb_string .= '</ul>';      
  }
}
?>