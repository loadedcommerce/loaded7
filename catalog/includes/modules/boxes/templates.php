<?php
/*
  $Id: templates.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_templates extends lC_Modules {
    var $_title,
        $_code = 'templates',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_templates() {
      global $lC_Language;

      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');
      
      $this->_title = $lC_Language->get('box_templates_heading');
    }

    function initialize() {
      global $lC_Session;

      $data = array();

      foreach (lC_Template::getTemplates() as $template) {
        $data[] = array('id' => $template['code'], 'text' => $template['title']);
      }

      if (sizeof($data) > 1) {
        $hidden_get_variables = '';

        foreach ($_GET as $key => $value) {
          if ( ($key != 'template') && ($key != $lC_Session->getName()) && ($key != 'x') && ($key != 'y') ) {
            $hidden_get_variables .= lc_draw_hidden_field($key, $value);
          }
        }

        $this->_content = '<ul class="category" style="white-space:nowrap;"><form name="templates" action="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), null, 'AUTO', false) . '" method="get">' .
                          $hidden_get_variables . lc_draw_pull_down_menu('template', $data, $_SESSION['template']['code'], 'onchange="this.form.submit();" style="width: 100%"') . lc_draw_hidden_session_id_field() .
                          '</form></ul>';
      }
    }
  }
?>