<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: help.php v1.0 2013-08-08 datazen $
*/
class lC_Search_Help extends lC_Template {

  /* Private variables */
  var $_module = 'help',
      $_group = 'search',
      $_page_title ,
      $_page_contents = 'help.php',
      $_has_header = false,
      $_has_footer = false,
      $_has_box_modules = false,
      $_has_content_modules = false,
      $_show_debug_messages = false;

  /* Class constructor */
  public function lC_Search_Help() {
    global $lC_Language, $lC_NavigationHistory;

    $this->_page_title = $lC_Language->get('search_heading');

    $lC_NavigationHistory->removeCurrentPage();
  }
}
?>