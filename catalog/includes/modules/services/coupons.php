<?php
/*
  $Id: coupons.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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