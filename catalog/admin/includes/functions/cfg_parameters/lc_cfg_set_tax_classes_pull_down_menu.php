<?php
/*
  $Id: lc_cfg_set_tax_classes_pull_down_menu.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_set_tax_classes_pull_down_menu($default, $key = null) {
    global $lC_Database, $lC_Language;

    if (isset($_GET['plugins'])) {
      $name = (empty($key)) ? 'plugins_value' : 'plugins[' . $key . ']';
    } else {
      $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';
    }

    $tax_class_array = array(array('id' => '0',
                                   'text' => $lC_Language->get('parameter_none')));

    $Qclasses = $lC_Database->query('select tax_class_id, tax_class_title from :table_tax_class order by tax_class_title');
    $Qclasses->bindTable(':table_tax_class', TABLE_TAX_CLASS);
    $Qclasses->execute();

    while ($Qclasses->next()) {
      $tax_class_array[] = array('id' => $Qclasses->valueInt('tax_class_id'),
                                 'text' => $Qclasses->value('tax_class_title'));
    }

    return lc_draw_pull_down_menu($name, $tax_class_array, $default, 'class="input with-small-padding"');
  }
?>