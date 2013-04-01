<?php
/**
  $Id: controller.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

*/
ini_set('display_errors', 1);

require_once('includes/applications/updates/classes/updates.php'); 
 
class lC_Updates_Admin_run_after extends lC_Updates_Admin {

  public function __constructor() {
  }
  
  public function process() {
    
    parent::log('##### RUNAFTER PROCESS');
  }  
  
}  
?>