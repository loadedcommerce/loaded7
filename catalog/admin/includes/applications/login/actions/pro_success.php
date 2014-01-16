<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: pro_success.php v1.0 2013-08-08 datazen $
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
      $_SESSION['pro_success'] = true;
    }    
  }
}
?>
