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

  class lC_Services_reviews {
    function start() {
    	global $lC_Reviews;
      include('includes/classes/reviews.php');

      $lC_Reviews = new lC_Reviews();
      return true;
    }

    function stop() {
      return true;
    }
  }
?>
