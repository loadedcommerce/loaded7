<?php
/*
  $Id: search.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_search extends lC_Modules {
    var $_title,
        $_code = 'search',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_search() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_search_heading');
    }

    function initialize() {
      global $lC_Language;

      $this->_title_link = lc_href_link(FILENAME_SEARCH);

      $this->_content = '<form name="search" action="' . lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false) . '" method="get">' .
                        lc_draw_input_field('keywords', null, 'style="width: 80%;" maxlength="30"') . '&nbsp;' . lc_draw_hidden_session_id_field() . lc_draw_image_submit_button('button_quick_find.gif', $lC_Language->get('box_search_heading')) . '<br />' . sprintf($lC_Language->get('box_search_text'), lc_href_link(FILENAME_SEARCH)) .
                        '</form>';
    }
  }
?>