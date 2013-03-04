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

  class lC_Services_language {
    function start() {
      global $lC_Language, $lC_Session, $lC_Vqmod;

      require($lC_Vqmod->modCheck('includes/classes/language.php'));
      $lC_Language = new lC_Language();

      if (isset($_GET['language']) && !empty($_GET['language'])) {
        $lC_Language->set($_GET['language']);
      }

      $lC_Language->load('general');
      $lC_Language->load('modules-boxes');
      $lC_Language->load('modules-content');

      header('Content-Type: text/html; charset=' . $lC_Language->getCharacterSet());

      lc_setlocale(LC_TIME, explode(',', $lC_Language->getLocale()));

      return true;
    }

    function stop() {
      return true;
    }
  }
?>
