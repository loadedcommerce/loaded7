<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_use_get_tax_class_title.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_use_get_tax_class_title($id) {
  global $lC_Database, $lC_Language;

  if ($id < 1) {
    return $lC_Language->get('parameter_none');
  }

  $Qclass = $lC_Database->query('select tax_class_title from :table_tax_class where tax_class_id = :tax_class_id');
  $Qclass->bindTable(':table_tax_class', TABLE_TAX_CLASS);
  $Qclass->bindInt(':tax_class_id', $id);
  $Qclass->execute();

  return $Qclass->value('tax_class_title');
}
?>