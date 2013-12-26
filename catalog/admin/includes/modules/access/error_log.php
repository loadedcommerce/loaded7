<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: error_log.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Error_log extends lC_Access {
  var $_module = 'error_log',
      $_group = 'tools',
      $_icon = 'error.png',
      $_title,
      $_sort_order = 400;

  public function lC_Access_Error_log() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_error_log_title');
  }
}
?>