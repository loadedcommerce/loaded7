<?php
/*
  $Id$

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2007 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Services_banner {
    function start() {
      global $lC_Banner;

      include('includes/classes/banner.php');
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
