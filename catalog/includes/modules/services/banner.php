<?php
/**
  $Id: banner.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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