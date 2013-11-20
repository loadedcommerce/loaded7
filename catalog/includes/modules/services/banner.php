<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: banner.php v1.0 2013-08-08 datazen $
*/
class lC_Services_banner {
  function start() {
    global $lC_Banner, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/classes/banner.php'));
    $lC_Banner = new lC_Banner();

    $lC_Banner->activateAll();
    $lC_Banner->expireAll();

    return true;
  }

  function stop() {
    return true;
  }
}
?>