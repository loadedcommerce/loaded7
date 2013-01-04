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

  class lC_Boxes_information extends lC_Modules {
    var $_title,
        $_code = 'information',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_information() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_information_heading');
    }

    function initialize() {
      global $lC_Language, $lC_Customer;

      $this->_title_link = lc_href_link(FILENAME_INFO);
     
      $this->_content = '<ul class="category">' . 
                        (($lC_Customer->isLoggedOn()) ? '  <li>' . lc_link_object(lc_href_link(FILENAME_ACCOUNT), $lC_Language->get('my_account')) . '</li>' : '') .
                        '  <li>' . lc_link_object(lc_href_link(FILENAME_INFO, 'shipping'), $lC_Language->get('box_information_shipping')) . '</li>' .
                        '  <li>' . lc_link_object(lc_href_link(FILENAME_INFO, 'privacy'), $lC_Language->get('box_information_privacy')) . '</li>' .
                        '  <li>' . lc_link_object(lc_href_link(FILENAME_INFO, 'conditions'), $lC_Language->get('box_information_conditions')) . '</li>' .
                        '  <li>' . lc_link_object(lc_href_link(FILENAME_INFO, 'contact'), $lC_Language->get('box_information_contact')) . '</li>' .
                        '  <li>' . lc_link_object(lc_href_link(FILENAME_INFO, 'sitemap'), $lC_Language->get('box_information_sitemap')) . '</li>' .
                        '</ul>';
    }
  }
?>
