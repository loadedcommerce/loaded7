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

  class lC_Services_specials {
    function start() {
      global $lC_Specials;

      require('includes/classes/specials.php');
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
