<?php
/*
  $Id: administrators_log.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ( !class_exists('lC_Summary') ) {
  include('includes/classes/summary.php');
}

class lC_Summary_administrators_log extends lC_Summary {

  var $enabled = FALSE,
      $sort_order = 40;
  
  /* Class constructor */
  function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/administrators_log.php');
    $lC_Language->loadIniFile('administrators_log.php');

    $this->_title = $lC_Language->get('summary_administrators_log_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'administrators_log');

    if ( lC_Access::hasAccess('administrators_log') ) {
      $this->_setData();
    }
  }

  /* Private methods */
  function _setData() {
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
                        '        <strong>' . $Qlog->value('module') . ' (' . $Qlog->valueInt('total') . ')</strong> ' . $Qlog->valueProtected('user_name') .
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
    
  function loadModal() {
    global $lC_Database, $lC_Language, $lC_Template;
    
    if ( is_dir('includes/applications/administrators_log/modal') ) {
      if ( file_exists('includes/applications/administrators_log/modal/info.php') ) include_once('includes/applications/administrators_log/modal/info.php');
    }
  }   
}
?>