<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: server_info.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Server_info extends lC_Access {
  var $_module = 'server_info',
      $_group = 'tools',
      $_icon = 'server_info.png',
      $_title,
      $_sort_order = 700;

  public function lC_Access_Server_info() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_server_info_title');
  }
}
?>