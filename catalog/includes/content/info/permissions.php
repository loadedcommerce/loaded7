<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: edit.php v1.0 2013-08-08 datazen $
*/
class lC_Info_Permissions extends lC_Template {

  /* Private variables */
  var $_module = 'permissions',
      $_group = 'info',
      $_page_title,
      $_page_contents = 'permissions.php',
      $_page_image = 'none.gif';

  /* Class constructor */
  function lC_Info_Permissions() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('info_permissions_heading');
  }
}
?>