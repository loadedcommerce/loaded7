<?php
/**
  $Id: recently_visited.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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