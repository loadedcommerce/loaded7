<?php
/*
  $Id$

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2006 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Boxes_languages extends lC_Modules {
    var $_title,
        $_code = 'languages',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_languages() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_languages_heading');
    }

    function initialize() {
      global $lC_Language, $request_type;

      $this->_content = '<ul class="category departments"><p align="center">';

      foreach ($lC_Language->getAll() as $value) {
        $this->_content .= ' ' . lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('language', 'currency')) . '&language=' . $value['code'], 'AUTO'), $lC_Language->showImage($value['code'])) . ' ';
      }
      
      $this->_content .= '</p></ul>';
    }
  }
?>
