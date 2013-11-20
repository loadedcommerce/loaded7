<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: receipt.php v1.0 2013-08-08 datazen $
*/
class lC_Account_Receipt extends lC_Template {

  /* Private variables */
  var $_module = 'receipt',
      $_group = 'account',
      $_page_title ,
      $_page_contents = 'receipt.php',
      $_has_header = false,
      $_has_footer = false,
      $_has_box_modules = false,
      $_has_content_modules = false,
      $_show_debug_messages = false;

  /* Class constructor */
  public function lC_Account_Receipt() {
    global $lC_Language, $lC_NavigationHistory;

    $this->_page_title = $lC_Language->get('receipt_heading');

    $lC_NavigationHistory->removeCurrentPage();
  }
}
?>