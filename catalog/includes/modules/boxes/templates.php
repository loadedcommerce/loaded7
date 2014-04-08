<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: templates.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_templates extends lC_Modules {
  var $_title,
      $_code = 'templates',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_templates() {
    global $lC_Language;

    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');
    
    $this->_title = $lC_Language->get('box_templates_heading');
  }

  public function initialize() {
    global $lC_Session;

    $data = array();
    
    // added to allow a reset to the DEFAULT_TEMPLATE database setting
    $reset = array();
    $reset[] = array('id' => 'reset', 'text' => 'Clear Template Selection');
    
    foreach (lC_Template::getTemplates() as $template) {
      $data[] = array('id' => $template['code'], 'text' => $template['title']);
    }
    
    // merge the reset option into the templates dropdown selection array
    $data = array_merge($reset, $data);
    
    if (sizeof($data) > 1) {
      $hidden_get_variables = '';

      foreach ($_GET as $key => $value) {
        if ( ($key != 'template') && ($key != $lC_Session->getName()) && ($key != 'x') && ($key != 'y') ) {
          $hidden_get_variables .= lc_draw_hidden_field($key, $value);
        }
      }
      
      $this->_content = '<li class="box-templates-selection">' . $hidden_get_variables . lc_draw_pull_down_menu('template', $data, $_SESSION['template']['code'], 'class="box-templates-select" onchange="$(this).closest(\'form\').submit();"') . lc_draw_hidden_session_id_field() . '</li>';
    }
  }
}
?>