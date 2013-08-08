<?php
/*
  $Id: pro_success.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Login_Actions_pro_success class
*/
class lC_Application_Login_Actions_pro_success extends lC_Application_Login {
    
  /*
  * Protected variables
  */
  protected $_page_contents = 'pro_success.php'; 
  
  public function __construct() {
    global $lC_Database, $lC_Language, $lC_MessageStack, $lC_Api, $rInfo;

    parent::__construct();
    
    if (isset($_POST)) {
      $_POST['installID'] = (preg_match("'<installationID[^>]*?>(.*?)</installationID>'i", $lC_Api->register($_POST), $regs) == 1) ? $regs[1] : NULL;      
      $rInfo = new lC_ObjectInfo($_POST);
    }    
  }
}
?>
