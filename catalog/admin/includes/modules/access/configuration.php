<?php
/*
  $Id: configuration.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Configuration extends lC_Access {
    var $_module = 'configuration',
        $_group = 'configuration',
        $_icon = 'configuration.png',
        $_title,
        $_sort_order = 100;

    function lC_Access_Configuration() {
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
