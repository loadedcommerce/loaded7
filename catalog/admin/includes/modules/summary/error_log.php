<?php
/*
  $Id: error_log.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
require('includes/applications/error_log/classes/error_log.php');

if ( !class_exists('lC_Summary') ) {
  include('includes/classes/summary.php');
}

class lC_Summary_error_log extends lC_Summary {
  
  var $enabled = FALSE,
      $sort_order = 50;

  /* Class constructor */
  function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/error_log.php');

    $this->_title = $lC_Language->get('summary_error_log_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'error_log');

    if ( lC_Access::hasAccess('error_log') ) {
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
      $counter = 0;
      foreach ( lc_toObjectInfo(lC_ErrorLog_Admin::getAll())->get('entries') as $log ) {
        $counter ++;
        $this->_data .= '    <li>' .
                        '      <span class="list-link icon-warning icon-red" title="' . $lC_Language->get('orders') . '">' .  
                        '        <strong>' . lc_output_string_protected($log['date'])  . '</strong> ' . lc_output_string_protected(substr($log['message'], 0, 20)) .
                        '      </span>' .
                        '    </li>';

        if ( $counter == 6 ) {
          break;
        }
      }
      $this->_data .= '  </ul>' . 
                      '</div>';
    }
  }
}
?>