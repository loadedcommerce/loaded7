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

  class lC_Services_breadcrumb {
    function start() {
      global $lC_Breadcrumb, $lC_Language;

      include('includes/classes/breadcrumb.php');
      $lC_Breadcrumb = new lC_Breadcrumb();

      //$lC_Breadcrumb->add($lC_Language->get('breadcrumb_top'), HTTP_SERVER);
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_shop'), lc_href_link(FILENAME_DEFAULT));

      return true;
    }

    function stop() {
      return true;
    }
  }
?>
