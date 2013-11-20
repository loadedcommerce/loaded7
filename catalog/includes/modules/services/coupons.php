<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: coupons.php v1.0 2013-08-08 datazen $
*/
class lC_Services_coupons {
  function start() {
    global $lC_Coupons, $lC_Vqmod;
    
    include($lC_Vqmod->modCheck('includes/classes/coupons.php'));

    $lC_Coupons = new lC_Coupons();
    return true;
  }

  function stop() {
    return true;
  }
}
?>