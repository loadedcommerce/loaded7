<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: error_log.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/error_log/classes/error_log.php'));

if ( !class_exists('lC_Summary') ) {
  include($lC_Vqmod->modCheck('includes/classes/summary.php'));
}

class lC_Summary_error_log extends lC_Summary {
  
  var $enabled = FALSE,
      $sort_order = 70;

  /* Class constructor */
  public function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/error_log.php');

    $this->_title = $lC_Language->get('summary_error_log_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'error_log');

    if ( lC_Access::hasAccess('error_log') ) {
      $this->_setData();
    }
  }

  /* Private methods */
  protected function _setData() {
    global $lC_Database, $lC_Language;

    if (!$this->enabled) {
      $this->_data = '';
    } else {
      $this->_data = '<div class="four-columns six-columns-tablet twelve-columns-mobile clear-both">' .
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