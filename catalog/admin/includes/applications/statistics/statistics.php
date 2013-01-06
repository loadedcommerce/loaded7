<?php
/*
  $Id: statistics.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Statistics class manages the newsletters GUI
*/
class lC_Application_Statistics extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'statistics',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language, $lC_Statistics, $breadcrumb_string;

    $this->_page_title = $lC_Language->get('heading_title');
    
    if ( !isset($_GET['module']) ) {
      $_GET['module'] = '';
    }
                                                             
    if ( !empty($_GET['module']) && !file_exists('includes/modules/statistics/' . $_GET['module'] . '.php') ) {
      $_GET['module'] = '';
    }

    if ( empty($_GET['module']) ) {
      $this->_page_contents = 'listing.php';
    } else {
      include_once('includes/modules/statistics/' . $_GET['module'] . '.php');
      $class = 'lC_Statistics_' . str_replace(' ', '_', ucwords(str_replace('_', ' ', $_GET['module'])));
      $lC_Statistics = new $class();
      $lC_Statistics->activate();
      $breadcrumb_array = array(lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module), $lC_Language->get('heading_title')));
      $breadcrumb_array[] = lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '&module=' . $_GET['module']), $lC_Statistics->getTitle());
      $breadcrumb_string = '<ul>';
      foreach ($breadcrumb_array as $key => $value) {
        $breadcrumb_string .= '<li>' . $value . '</li>';
      }  
      $breadcrumb_string .= '</ul>';     
    }
  }
}
?>
