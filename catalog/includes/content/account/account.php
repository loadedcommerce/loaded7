<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: account.php v1.0 2013-08-08 datazen $
*/
class lC_Account_Account extends lC_Template {

  /* Private variables */
  var $_module = 'account',
      $_group = 'account',
      $_page_title,
      $_page_contents = 'account.php',
      $_page_image = 'table_background_account.gif';

  public function lC_Account_Account() {
    global $lC_Language, $lC_Vqmod;

    require($lC_Vqmod->modCheck('includes/classes/order.php'));

    $this->_page_title = $lC_Language->get('account_heading');
  }
}
?>