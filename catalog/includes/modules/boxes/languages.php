<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: languages.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_languages extends lC_Modules {
  var $_title,
      $_code = 'languages',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_languages() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_languages_heading');
  }

  public function initialize() {
    global $lC_Language, $request_type;

    $this->_content = '';
    foreach ($lC_Language->getAll() as $value) {  
      $this->_content .= '<li class="box-languages-selection">' . lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('language', 'currency')) . '&language=' . $value['code'], 'AUTO'), $lC_Language->showImage($value['code'])) . '</li>';
    }
  }
}
?>
