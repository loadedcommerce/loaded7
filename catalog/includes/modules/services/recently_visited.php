<?php
/*
  $Id: $

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2007 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Services_recently_visited {
    function start() {
      global $lC_Services, $lC_RecentlyVisited;

      include('includes/classes/recently_visited.php');

      $lC_RecentlyVisited = new lC_RecentlyVisited();

      $lC_Services->addCallBeforePageContent('lC_RecentlyVisited', 'initialize');

      return true;
    }

    function stop() {
      return true;
    }
  }
?>
