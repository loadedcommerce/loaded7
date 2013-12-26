<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: configuration.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Configuration extends lC_Access {
  var $_module = 'configuration',
      $_group = 'configuration',
      $_icon = 'configuration.png',
      $_title,
      $_sort_order = 100;

  public function lC_Access_Configuration() {
    global $lC_Database, $lC_Language;

    $this->_title = $lC_Language->get('access_configuration_title');

    $this->_subgroups = array();

    $Qgroups = $lC_Database->query('select configuration_group_id, configuration_group_title from :table_configuration_group where visible = 1 order by sort_order, configuration_group_title');
    $Qgroups->bindTable(':table_configuration_group', TABLE_CONFIGURATION_GROUP);
    $Qgroups->execute();

//      while ($Qgroups->next()) {
//        $this->_subgroups[] = array('icon' => 'configure.png',
//                                    'title' => $Qgroups->value('configuration_group_title'),
//                                    'identifier' => 'gID=' . $Qgroups->valueInt('configuration_group_id'));
//      }
  }
}
?>
