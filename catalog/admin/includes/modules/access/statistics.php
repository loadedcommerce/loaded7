<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: statistics.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Statistics extends lC_Access {
  var $_module = 'statistics',
      $_group = 'reports',
      $_icon = 'statistics.png',
      $_title,
      $_sort_order = 100;

  public function lC_Access_Statistics() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_statistics_title');
  }
}
?>