<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: administrators.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Administrators extends lC_Access {
  var $_module = 'administrators',
      $_group = 'configuration',
      $_icon = 'people.png',
      $_title,
      $_sort_order = 200;

  public function lC_Access_Administrators() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_administrators_title');

    $this->_subgroups = array(array('icon' => 'statistics.png',
                                    'title' => $lC_Language->get('access_administrators_groups_title'),
                                    'identifier' => 'set=groups'),
                              array('icon' => 'people.png',
                                    'title' => $lC_Language->get('access_administrators_members_title'),
                                    'identifier' => 'set=members'));
  }
}
?>