<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
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