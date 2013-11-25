<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: administrators_log.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

if ( !class_exists('lC_Summary') ) {
  include($lC_Vqmod->modCheck('includes/classes/summary.php'));
}

class lC_Summary_administrators_log extends lC_Summary {

  var $enabled = FALSE,
      $sort_order = 60;
  
  /* Class constructor */
  public function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/administrators_log.php');
    $lC_Language->loadIniFile('administrators_log.php');

    $this->_title = $lC_Language->get('summary_administrators_log_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'administrators_log');

    if ( lC_Access::hasAccess('administrators_log') ) {
      $this->_setData();
    }
  }
  
  public function loadModal() {
    global $lC_Database, $lC_Language, $lC_Template, $lC_Vqmod;
    
    if ( is_dir('includes/applications/administrators_log/modal') ) {
      if ( file_exists('includes/applications/administrators_log/modal/info.php') ) include_once($lC_Vqmod->modCheck('includes/applications/administrators_log/modal/info.php'));
    }
  }  

  /* Private methods */
  protected function _setData() {
    global $lC_Database, $lC_Language;

    if (!$this->enabled) {
      $this->_data = '';
    } else {    
      $this->_data = '<div class="four-columns six-columns-tablet twelve-columns-mobile">' .
                     '  <h2 class="relative thin">' . $this->_title . '</h2>' .
                     '  <ul class="list spaced">';       

      $Qlog = $lC_Database->query('select count(al.id) as total, al.id, al.module, a.user_name, al.datestamp from :table_administrators_log al, :table_administrators a group by al.id order by al.id desc limit 6;');
      $Qlog->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
      $Qlog->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
      $Qlog->execute();

      while ( $Qlog->next() ) {
        $this->_data .= '    <li>' .
                        '      <span class="list-link icon-bullet-list icon-orange" title="' . $lC_Language->get('orders') . '">' .  
                        '        <strong>' . $Qlog->value('module') . ' (' . $Qlog->valueInt('total') . ')</strong>  <span class="anthracite">' . $Qlog->valueProtected('user_name') . '</span>' . 
                        '      </span>' .
                        '      <div class="absolute-right compact show-on-parent-hover">' .
                        '        <a href="' . ((int)($_SESSION['admin']['access']['administrators_log'] < 1) ? '#' : 'javascript://" onclick="showAdminLogInfo(\'' . $Qlog->valueInt('id') . '\')') . ';" title="' . $lC_Language->get('icon_info') . '" class="button icon-info with-tooltip ' . ((int)($_SESSION['admin']['access']['administrators_log'] < 1) ? ' disabled' : NULL) . '"></a>' . 
                        '      </div>' .
                        '    </li>';        
      }

      $this->_data .= '  </ul>' . 
                      '</div>';

      $Qlog->freeResult();
      
      $this->_data .= $this->loadModal();      
    }
  }   
}
?>