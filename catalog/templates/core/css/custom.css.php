<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: custom.css.php v1.0 2013-08-08 datazen $
*/

  chdir('../../../');
  require('includes/application_top.php');
  
  $Qcss = $lC_Database->query('select custom_css from :table_branding_data');
  $Qcss->bindTable(':table_branding_data', TABLE_BRANDING_DATA);
  $Qcss->execute();
  
  if ($Qcss->numberOfRows() === 1) {
    $css = $Qcss->toArray();
    echo $css['custom_css'];
  }
  require($lC_Vqmod->modCheck('includes/application_bottom.php'));
?>