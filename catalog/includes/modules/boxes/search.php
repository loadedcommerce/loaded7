<?php
/**
  @package    catalog::search::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: search.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_search extends lC_Modules {
  var $_title,
      $_code = 'search',
      $_author_name = 'LoadedCommerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_search() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_search_heading');
  }

  public function initialize() {
    global $lC_Language;

    $this->_title_link = lc_href_link(FILENAME_SEARCH);

    $this->_content = '<li class="box-search-input">' . lc_draw_input_field('keywords', null, 'id="box-keywords"') . '&nbsp;<a id="box-search-submit" onclick="$(\'#box-search-form\').submit();"></a></li>' . "\n" .
                      '<li class="box-search-text">' . sprintf($lC_Language->get('box_search_text'), lc_href_link(FILENAME_SEARCH)) . '</li>' . "\n";
  }
}
?>