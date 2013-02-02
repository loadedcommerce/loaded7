<?php
/*
  $Id$

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2007 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Services_debug {
    function start() {
      global $lC_MessageStack, $lC_Language;
         
      if (SERVICE_DEBUG_CHECK_LOCALE == '1') {
        $setlocale = lc_setlocale(LC_TIME, explode(',', $lC_Language->getLocale()));

        if (($setlocale === false) || ($setlocale === null)) {
          $lC_MessageStack->add('debug', 'Error: Locale does not exist: ' . $lC_Language->getLocale(), 'error');
        }
      }

      if ((SERVICE_DEBUG_CHECK_INSTALLATION_MODULE == '1') && file_exists(dirname($_SERVER['SCRIPT_FILENAME']) . '/install')) {
        $lC_MessageStack->add('debug', sprintf($lC_Language->get('warning_install_directory_exists'), dirname($_SERVER['SCRIPT_FILENAME']) . '/install'), 'warning');
      }

      if ((SERVICE_DEBUG_CHECK_CONFIGURATION == '1') && file_exists(dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/config.php') && is_writeable(dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/config.php')) {
        $lC_MessageStack->add('debug', sprintf($lC_Language->get('warning_config_file_writeable'), dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/config.php'), 'warning');
      }

      if ((SERVICE_DEBUG_CHECK_SESSION_DIRECTORY == '1') && (STORE_SESSIONS == '')) {
        if (!is_dir($lC_Session->getSavePath())) {
          $lC_MessageStack->add('debug', sprintf($lC_Language->get('warning_session_directory_non_existent'), $lC_Session->getSavePath()), 'warning');
        } elseif (!is_writeable($lC_Session->getSavePath())) {
          $lC_MessageStack->add('debug', sprintf($lC_Language->get('warning_session_directory_not_writeable'), $lC_Session->getSavePath()), 'warning');
        }
      }

      if ((SERVICE_DEBUG_CHECK_SESSION_AUTOSTART == '1') && (bool)ini_get('session.auto_start')) {
        $lC_MessageStack->add('debug', $lC_Language->get('warning_session_auto_start'), 'warning');
      }

      if ((SERVICE_DEBUG_CHECK_DOWNLOAD_DIRECTORY == '1') && (DOWNLOAD_ENABLED == '1')) {
        if (!is_dir(DIR_FS_DOWNLOAD)) {
          $lC_MessageStack->add('debug', sprintf($lC_Language->get('warning_download_directory_non_existent'), DIR_FS_DOWNLOAD), 'warning');
        }
      }     

      return true;
    }

    function stop() {
      global $lC_MessageStack, $lC_Template, $lC_Language, $lC_Database;
             
      $time_start = explode(' ', PAGE_PARSE_START_TIME);
      $time_end = explode(' ', microtime());
      $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

      if (!lc_empty(SERVICE_DEBUG_EXECUTION_TIME_LOG)) {
        if (!@error_log(strftime('%c') . ' - ' . $_SERVER['REQUEST_URI'] . ' (' . $parse_time . 's)' . "\n", 3, SERVICE_DEBUG_EXECUTION_TIME_LOG)) {
          if (!file_exists(SERVICE_DEBUG_EXECUTION_TIME_LOG) || !is_writable(SERVICE_DEBUG_EXECUTION_TIME_LOG)) {
            $lC_MessageStack->add('debug', sprintf($lC_Language->get('debug_exection_time'), SERVICE_DEBUG_EXECUTION_TIME_LOG), 'error');
          }
        }
      }
      
      // additional info 
      if (isset($_SESSION['lC_Customer_data'])) {
        $lC_MessageStack->add('debug', sprintf($lC_Language->get('debug_exection_time'), $_SESSION['lC_Customer_data']['customers_group_id'], $_SESSION['lC_Customer_data']['customers_group_name']), 'warning'); 
      }    
      $lC_MessageStack->add('debug', sprintf($lC_Language->get('debug_current_template'), $_SESSION['template']['code']), 'info');
                   
      if (SERVICE_DEBUG_EXECUTION_DISPLAY == '1') {
        $lC_MessageStack->add('debug', sprintf($lC_Language->get('debug_exection_time'), $parse_time), 'info');
      }
   
      $lC_MessageStack->add('debug', $lC_Language->get('debug_notice'), 'info');      
      
      if ( $lC_Template->showDebugMessages() && ($lC_MessageStack->size('debug') > 0) ) {
        $_SESSION['debugStack'] = json_encode($lC_MessageStack->get('debug'));
      }
      
      return true;
    }
  }
?>
