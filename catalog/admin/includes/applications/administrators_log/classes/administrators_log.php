<?php
/*
  $Id: administrators_log.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Administrators_log_Admin class manages the administrator log
*/
class lC_Administrators_log_Admin { 
 /*
  * Returns the administrators log datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() { 
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $result = array('aaData' => array());

    /* Total Records */
    $QresultTotal = $lC_Database->query("SELECT sum(al.id) as total  
                                           from :table_administrators_log al, 
                                                :table_administrators a 
                                         WHERE al.administrators_id = a.id group by al.id order by al.id desc");

    $QresultTotal->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
    $QresultTotal->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $QresultTotal->execute();
    $result['iTotalRecords'] = $QresultTotal->valueInt('total');
    $QresultTotal->freeResult(); 

    /* Paging */
    $sLimit = "";
    if (isset($_GET['iDisplayStart'])) {
      if ($_GET['iDisplayLength'] != -1) {
        $sLimit = " LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
      }
    }

    /* Ordering */
    if (isset($_GET['iSortCol_0'])) {
      $sOrder = " ORDER BY ";
      for ($i=0 ; $i < (int)$_GET['iSortingCols'] ; $i++ ) {
        $sOrder .= lC_Administrators_log_Admin::fnColumnToField($_GET['iSortCol_'.$i]) . " " . $_GET['sSortDir_'.$i] .", ";
      }
      $sOrder = substr_replace( $sOrder, "", -2 );
    }

    /* Filtering */
    $sWhere = "WHERE al.administrators_id = a.id ";
    if ($_GET['sSearch'] != "") {
      $sWhere = " and (al.module LIKE '%" . $_GET['sSearch'] . "%' OR " .
                      "al.module_id LIKE '%" . $_GET['sSearch'] . "%' OR " .
                      "al.module_action LIKE '%" . $_GET['sSearch'] . "%' OR " . 
                      "a.user_name LIKE '%" . $_GET['sSearch'] . "%') ";
    }

    /* Total Filtered Records */
    $QresultFilterTotal = $lC_Database->query("SELECT sum(al.id) as total  
                                                 from :table_administrators_log al, 
                                                      :table_administrators a " . 
                                               $sWhere . " group by al.id order by al.id desc ");

    $QresultFilterTotal->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
    $QresultFilterTotal->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $QresultFilterTotal->execute();         
    $result['iTotalDisplayRecords'] = $QresultFilterTotal->valueInt('total'); 
    $QresultFilterTotal->freeResult(); 

    /* Main Listing Query */
    $Qresult = $lC_Database->query("SELECT al.id, al.module, al.module_action, al.module_id, al.action, a.user_name, unix_timestamp(al.datestamp) as datestamp 
                                     from :table_administrators_log al, 
                                          :table_administrators a " . 
                                    $sWhere . ' group by al.id ' . $sOrder . $sLimit);

    $Qresult->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
    $Qresult->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qresult->execute();         

    while ( $Qresult->next() ) {
      $module = '<td><a href="javascript(void);" onClick="showInfo(\'' . $Qresult->valueInt('id') . '\')"><span class="icon-folder icon-orange"></span>&nbsp;' . $Qresult->value('module') . '</a></td>'; 
      $id = '<td>' . $Qresult->valueInt('module_id') . '</td>';
      $type = '<td>' . $Qresult->value('module_action') . '</td>';
      $user = '<td>' . $Qresult->value('user_name') . '</td>';
      $date = '<td>' . @date('d M Y H:i:s', $Qresult->value('datestamp')) . '</td>';  
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['administrators_log'] < 3) ? '#' : 'javascript://" onclick="showAdminLogInfo(\'' . $Qresult->valueInt('id') . '\')') . '" class="button icon-info-round icon-blue ' . ((int)($_SESSION['admin']['access']['administrators_log'] < 3) ? 'disabled' : NULL) . '"></a>
                 </span></td>';  

      $result['aaData'][] = array("$module", "$id", "$type", "$user", "$date", "$action");          
    }
    $result['sEcho'] = intval($_GET['sEcho']);

    $Qresult->freeResult(); 

    return $result;
  }
 /*
  * Returns the administrator log entry information
  *
  * @param integer $id The administrator log id
  * @access public
  * @return array
  */
  public static function getAdminLogData($id) {
    global $lC_Database;

    $Qlog = $lC_Database->query('select al.id, al.module, al.module_action, al.module_id, al.action, a.user_name, unix_timestamp(al.datestamp) as datestamp from :table_administrators_log al, :table_administrators a where al.id = :id and al.administrators_id = a.id limit 1');
    $Qlog->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
    $Qlog->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qlog->bindInt(':id', $id);
    $Qlog->execute();

    $data = $Qlog->toArray();
    $data['date'] = @date('M d Y H:i:s', $Qlog->value('datestamp'));

    $Qlog->freeResult();

    $Qentries = $lC_Database->query('select action, field_key, old_value, new_value from :table_administrators_log where id = :id');
    $Qentries->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
    $Qentries->bindInt(':id', $id);
    $Qentries->execute();
    $log_html = '';
    while ( $Qentries->next() ) {
      switch ( $Qentries->value('action') ) {
        case 'delete':
          $bgColor = '#E23832';
          $color = '#fff';          
          break;
        case 'insert':
          $bgColor = '#96E97A';
          $color = '#000';         
          break;
        default:
          $bgColor = '#FFC881';
          $color = '#000';
          break;
      }
      $log_html .= '<tr><td valign="top" style="background-color:' . $bgColor . '; color:' . $color . ';">' . $Qentries->valueProtected('field_key') . '</td><td valign="top" style="background-color:' . $bgColor . '; color:' . $color . ';">' . nl2br($Qentries->valueProtected('old_value')) . '</td><td valign="top" style="background-color:' . $bgColor . '; color:' . $color . ';">' . nl2br($Qentries->valueProtected('new_value')) . '</td></tr>';
    }

    $Qentries->freeResult();

    $data['log_html'] = $log_html;

    return $data;
  }
 /*
  * Insert an administrator log record
  *
  * @param string $module The administrator module name
  * @param string $module_action The administrator module action
  * @param integer $module_id The administrator module id
  * @param string $action The administrator module action
  * @param integer $transaction_id The administrator log id
  * @access public
  * @return boolean
  */
  public static function insert($module, $module_action, $module_id, $action, $log, $transaction_id) {
    global $lC_Database;

    if ( is_numeric($transaction_id) ) {
      $log_id = $transaction_id;
    } else {
      $Qlog = $lC_Database->query('select max(id) as id from :table_administrators_log');
      $Qlog->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
      $Qlog->execute();

      $log_id = $Qlog->valueInt('id') + 1;

      if ( $transaction_id === true ) {
        $lC_Database->logging_transaction = $log_id;
      }
    }

    foreach ( $log as $entry ) {
      $Qlog = $lC_Database->query('insert into :table_administrators_log (id, module, module_action, module_id, field_key, old_value, new_value, action, administrators_id, datestamp) values (:id, :module, :module_action, :module_id, :field_key, :old_value, :new_value, :action, :administrators_id, now())');
      $Qlog->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
      $Qlog->bindInt(':id', $log_id);
      $Qlog->bindValue(':module', $module);
      $Qlog->bindValue(':module_action', $module_action);
      $Qlog->bindInt(':module_id', $module_id);
      $Qlog->bindValue(':field_key', $entry['key']);
      $Qlog->bindValue(':old_value', $entry['old']);
      $Qlog->bindValue(':new_value', $entry['new']);
      $Qlog->bindValue(':action', $action);
      $Qlog->bindInt(':administrators_id', $_SESSION['admin']['id']);
      $Qlog->execute();
    }

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Delete the administrator log record
  *
  * @param integer $id The administrator log id to delete
  * @access public
  * @return boolean
  */ 
  public static function delete($id) {
    global $lC_Database;

    $Qlog = $lC_Database->query('delete from :table_administrators_log where id = :id');
    $Qlog->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
    $Qlog->bindInt(':id', $id);
    $Qlog->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete administrator log records
  *
  * @param array $batch The administrator log id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Administrators_log_Admin::delete($id);
    }
    return true;
  } 
 /*
  * Delete all administrator log records
  *
  * @access public
  * @return boolean
  */
  public static function deleteAll() {
    global $lC_Database;

    $Qlog = $lC_Database->query('truncate table :table_administrators_log');
    $Qlog->bindTable(':table_administrators_log', TABLE_ADMINISTRATORS_LOG);
    $Qlog->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Return the administrator log sort column
  *
  * @param integer $i The datatable column id
  * @access private
  * @return string
  */
  private static function fnColumnToField($i) {
   if ( $i == 0 )
    return "al.module";
   else if ( $i == 1 )
    return "al.module_id";
   else if ( $i == 2 )
    return "al.module_action";
   else if ( $i == 3 )
    return "a.user_name";
   else if ( $i == 4 )
    return "al.datestamp";
  }
}
?>