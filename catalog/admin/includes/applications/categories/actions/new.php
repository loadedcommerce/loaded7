<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: new.php v1.0 2013-08-08 datazen $
*/
class lC_Application_Categories_Actions_new extends lC_Application_Categories {
  public function __construct() {
    global $lC_Language, $lC_MessageStack;
    
    parent::__construct();
    
    $this->_page_contents = 'new.php'; 
  }
}
?>