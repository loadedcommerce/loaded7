<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: recently_visted.php v1.0 2013-08-08 datazen $
*/
class lC_Services_recently_visited {
  function start() {
    global $lC_Services, $lC_RecentlyVisited, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/classes/recently_visited.php'));

    $lC_RecentlyVisited = new lC_RecentlyVisited();

    $lC_Services->addCallBeforePageContent('lC_RecentlyVisited', 'initialize');

    return true;
  }

  function stop() {
    return true;
  }
}
?>