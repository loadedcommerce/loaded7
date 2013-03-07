<?php
/*
  $Id: updates.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/updates/classes/updates.php'));

class lC_Application_Updates extends lC_Template_Admin {
  /*
  * Protected variables
  */
  protected $_module = 'updates',
            $_page_title,
            $_page_contents = 'main.php';
  /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');
  }
  /**
  * Check if the location is writeable
  *  
  * @param array  $location  The file location to check
  * @access public      
  * @return boolean
  */   
  public function getLogList() {
    $array = array(array('id' => '',
                         'text' => OSCOM::getDef('select_log_to_view'),
                         'params' => 'disabled="disabled"'));

    foreach ( updates::getLogs() as $f ) {
      $array[] = array('id' => substr($f, 0, -4),
                       'text' => substr($f, 0, -4));
    }

    return $array;
  }  
}
?>