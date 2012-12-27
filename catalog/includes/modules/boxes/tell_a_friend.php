<?php
/*
  $Id: tell_a_friend.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_tell_a_friend extends lC_Modules {
    var $_title,
        $_code = 'tell_a_friend',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_tell_a_friend() {
      global $lC_Language;

      if (function_exists($lC_Language->injectDefinitions))$lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');
      
      $this->_title = $lC_Language->get('box_tell_a_friend_heading');
    }

    function initialize() {
      global $lC_Language, $lC_Template, $lC_Product;
      
      if (isset($lC_Product) && is_a($lC_Product, 'lC_Product') && ($lC_Template->getModule() != 'tell_a_friend')) {
        $this->_content = '<form name="tell_a_friend" id="tell_a_friend" action="' . lc_href_link(FILENAME_PRODUCTS, 'tell_a_friend&' . $lC_Product->getKeyword()) . '" method="post">' . "\n" .
                          lc_draw_input_field('to_email_address', null, 'style="width: 80%;"') . '&nbsp;<a onclick="$(\'#tell_a_friend\').submit();">' . lc_icon('icon_send.png') . '</a><br />' . $lC_Language->get('box_tell_a_friend_text') . "\n" . 
                          '</form>' . "\n";      
      }
    }
  }
?>