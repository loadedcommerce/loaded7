<?php
/**
  $Id: specials.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Services_specials {
  function start() {
    global $lC_Specials, $lC_Vqmod;

    require($lC_Vqmod->modCheck('includes/classes/specials.php'));
    $lC_Specials = new lC_Specials();

    $lC_Specials->activateAll();
    $lC_Specials->expireAll();

    return true;
  }

  function stop() {
    return true;
  }
}
?>