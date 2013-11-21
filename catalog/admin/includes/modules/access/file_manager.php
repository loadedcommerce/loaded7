<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: file_manager.php v1.0 2013-08-08 datazen $
*/
class lC_Access_File_manager extends lC_Access {
  var $_module = 'file_manager',
      $_group = 'tools',
      $_icon = 'file_manager.png',
      $_title,
      $_sort_order = 500;

  public function lC_Access_File_manager() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_file_manager_title');
  }
}
?>