<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: info.php v1.0 2013-08-08 datazen $
*/
class lC_Info_Info extends lC_Template {

  /* Private variables */
  var $_module = 'info',
      $_group = 'info',
      $_page_title,
      $_page_contents = 'info.php',
      $_page_image = 'table_background_account.gif';

  public function lC_Info_Info() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('info_heading');
  }
}
?>