<?php
/*
  $Id: lc_cfg_use_get_tax_class_title.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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