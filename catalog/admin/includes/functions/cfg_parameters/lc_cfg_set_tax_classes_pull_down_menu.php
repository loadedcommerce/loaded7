<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_tax_classes_pull_down_menu.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_tax_classes_pull_down_menu($default, $key = null) {
  global $lC_Database, $lC_Language;
  
  $css_class = 'class="input with-small-padding"';

  $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

  $tax_class_array = array(array('id' => '0',
                                 'text' => $lC_Language->get('parameter_none')));

  $Qclasses = $lC_Database->query('select tax_class_id, tax_class_title from :table_tax_class order by tax_class_title');
  $Qclasses->bindTable(':table_tax_class', TABLE_TAX_CLASS);
  $Qclasses->execute();

  while ($Qclasses->next()) {
    $tax_class_array[] = array('id' => $Qclasses->valueInt('tax_class_id'),
                               'text' => $Qclasses->value('tax_class_title'));
  }

  return lc_draw_pull_down_menu($name, $tax_class_array, $default, $css_class);
}
?>