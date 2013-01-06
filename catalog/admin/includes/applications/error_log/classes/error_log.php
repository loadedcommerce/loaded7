<?php
/*
  $Id: error_log.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_ErrorLog_Admin class manages admin error log
*/
define('lC_ErrorLog_Admin_logfile', ini_get('error_log'));

class lC_ErrorLog_Admin {
 /*
  * Class constants
  */
  const logfile = lC_ErrorLog_Admin_logfile;
 /*
  * Returns the error log datatable data for listings
  *
  * @access public
  * @return array
  */ 
  public static function getAll() {

    $messages = array();
    if ( file_exists(self::logfile) ) {
      $messages = array_reverse(array_unique(file(self::logfile)));
    }

    /* Total Records */ 
    $result = array('aaData' => array(),
                    'iTotalRecords' => sizeof($messages),
                    'entries' => array());

    /* Filtering */
    if ($_GET['sSearch'] != "") {
      foreach ( $messages as $key => $message ) {
        if ( !preg_match('/^\[([0-9]{2})-([A-Za-z]{3})-([0-9]{4}) ([0-9]{2}):([0-5][0-9]):([0-5][0-9])\] (.*)' . preg_replace('/[^A-Za-z0-9s]/', '', $_GET['sSearch']) . '(.*)$/i', $message) ) {
          unset($messages[$key]);
        }
      }
    }
    /* Filtered Records */
    $result['iTotalDisplayRecords'] = sizeof($messages);

    /* Paging */
    if (isset($_GET['iDisplayStart'])) {
      $pageset = ($_GET['iDisplayLength'] > 0) ? floor($_GET['iDisplayStart'] / $_GET['iDisplayLength']) : 1;
      if ($_GET['iDisplayLength'] != -1) {
        $messages = array_slice($messages, ($_GET['iDisplayLength'] * ($pageset - 1)), $_GET['iDisplayLength']);
      }           
    }

    foreach ( $messages as $message ) {
      if ( preg_match('/^\[([0-9]{2})-([A-Za-z]{3})-([0-9]{4}) ([0-9]{2}):([0-5][0-9]):([0-5][0-9])\] (.*)$/', $message) ) {
        $edate = '<td>' . substr($message, 1, 20) . '</td>';  
        $message = '<td>' . substr($message, 23) . '</td>';  
        $result['aaData'][] = array("$edate", "$message");
        $result['entries'][] = array('date' => substr($message, 1, 20),
                                     'message' => substr($message, 23));          
      }
    }
    $result['sEcho'] = intval($_GET['sEcho']);

    return $result;
  }
 /*
  * Delete all error log entries
  *
  * @access public
  * @return boolean
  */
  public static function deleteAll() {
    if ( file_exists(self::logfile) ) {
      return unlink(self::logfile);
    }

    return true;
  }
}
?>