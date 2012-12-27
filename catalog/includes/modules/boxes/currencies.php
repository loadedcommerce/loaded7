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

  class lC_Boxes_currencies extends lC_Modules {
    var $_title,
        $_code = 'currencies',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_currencies() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_currencies_heading');
    }

    function initialize() {
      global $lC_Session, $lC_Currencies;

      $data = array();

      foreach ($lC_Currencies->currencies as $key => $value) {
        $data[] = array('id' => $key, 'text' => $value['title']);
      }

      if (sizeof($data) > 1) {
        $hidden_get_variables = '';

        foreach ($_GET as $key => $value) {
          if ( ($key != 'currency') && ($key != $lC_Session->getName()) && ($key != 'x') && ($key != 'y') ) {
            $hidden_get_variables .= lc_draw_hidden_field($key, $value);
          }
        }

        $this->_content = '<ul class="category departments"><form name="currencies" action="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), null, 'AUTO', false) . '" method="get">' .
                          $hidden_get_variables .
                          lc_draw_pull_down_menu('currency', $data, $_SESSION['currency'], 'onchange="this.form.submit();" style="width: 100%"') .
                          lc_draw_hidden_session_id_field() .
                          '</form></ul><br><br>';
      }
    }
  }
?>
