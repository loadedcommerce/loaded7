<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: tell_a_friend.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_tell_a_friend extends lC_Modules {
  var $_title,
      $_code = 'tell_a_friend',
      $_author_name = 'LoadedCommerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_tell_a_friend() {
    global $lC_Language;

    if (function_exists($lC_Language->injectDefinitions))$lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');
    
    $this->_title = $lC_Language->get('box_tell_a_friend_heading');
  }

  public function initialize() {
    global $lC_Language, $lC_Template, $lC_Product;
    
    if (isset($lC_Product) && is_a($lC_Product, 'lC_Product') && ($lC_Template->getModule() != 'tell_a_friend')) {
      
      $this->_content = '<li class="box-tell-a-friend-input">' . lc_draw_input_field('to_email_address', null, 'class="box-tell-a-friend-select"') . '&nbsp;<a class="box-tell-a-friend-submit" onclick="$(this).closest(\'form\').submit();"></a></li>' . "\n" .
                        '<li class="box-tell-a-friend-text">' . $lC_Language->get('box_tell_a_friend_text') . '</li>' . "\n";
    }
  }
}
?>