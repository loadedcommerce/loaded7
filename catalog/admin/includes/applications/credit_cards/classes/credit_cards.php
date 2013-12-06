<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: credit_cards.php v1.0 2013-08-08 datazen $
*/
class lC_Credit_cards_Admin {
 /*
  * Returns the credit cards datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;

    $lC_Language->loadIniFile('credit_cards.php');
    
    $media = $_GET['media'];    

    $Qcc = $lC_Database->query('select * from :table_credit_cards order by sort_order, credit_card_name');
    $Qcc->bindTable(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcc->execute();

    $result = array('entries' => array());
    $result = array('aaData' => array());
    while ( $Qcc->next() ) {
      $card = '<td>' . $Qcc->value('credit_card_name') . '</td>';
      $status = '<td><a href="javascript:void(0);" onclick="updateStatus(\'' . $Qcc->valueInt('id') . '\', \'' . $Qcc->value('credit_card_status') . '\')">' . (($Qcc->value('credit_card_status') == '1') ? '<span class="icon-tick icon-green icon-size2 with-tooltip" title="' . $lC_Language->get('deactivate') . '"></span>' : '<span class="icon-cross icon-red icon-size2 with-tooltip" title="' . $lC_Language->get('activate') . '"></span>') . '</a></td>';
      $sort = '<td>' . $Qcc->valueInt('sort_order') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editCard(\'' . $Qcc->valueInt('id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteCard(\'' . $Qcc->valueInt('id') . '\', \'' . urlencode($Qcc->valueProtected('credit_card_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$card", "$status", "$sort", "$action");
      $result['entries'][] = $Qcc->toArray();
    }

    $Qcc->freeResult();

    return $result;
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $id The credit card id
  * @access public
  * @return array
  */
  public static function getFormData($id = null) {

    $result = array();
    $result['ccData'] = array();
    if (isset($id) && $id != null) {
      $result['ccData'] = lC_Credit_cards_Admin::getData($id);
    }

    return $result;
  }
 /*
  * Returns the credit card type information
  *
  * @param integer $id The credit card id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database;

    $Qcc = $lC_Database->query('select * from :table_credit_cards where id = :id');
    $Qcc->bindTable(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcc->bindInt(':id', $id);
    $Qcc->execute();

    $result = $Qcc->toArray();

    $Qcc->freeResult();

    return $result;
  }
 /*
  * Saves the credit card information
  *
  * @param integer $id The credit card id used on update, null on insert
  * @param array $data An array containing the credit card type information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database;

    if ( is_numeric($id) ) {
      $Qcc = $lC_Database->query('update :table_credit_cards set credit_card_name = :credit_card_name, pattern = :pattern, credit_card_status = :credit_card_status, sort_order = :sort_order where id = :id');
      $Qcc->bindInt(':id', $id);
    } else {
      $Qcc = $lC_Database->query('insert into :table_credit_cards (credit_card_name, pattern, credit_card_status, sort_order) values (:credit_card_name, :pattern, :credit_card_status, :sort_order)');
    }

    $ccStatus = (isset($data['credit_card_status']) && !empty($data['credit_card_status'])) ? $data['credit_card_status'] : 0;

    $Qcc->bindTable(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcc->bindValue(':credit_card_name', $data['credit_card_name']);
    $Qcc->bindValue(':pattern', $data['pattern']);
    $Qcc->bindInt(':credit_card_status', $ccStatus);
    $Qcc->bindInt(':sort_order', $data['sort_order']);
    $Qcc->setLogging($_SESSION['module'], $id);
    $Qcc->execute();

    if ( $Qcc->affectedRows() ) {
      lC_Cache::clear('credit-cards');

      return true;
    }

    return false;
  }
 /*
  * Delete the credit card record
  *
  * @param integer $id The credit card id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $Qdel = $lC_Database->query('delete from :table_credit_cards where id = :id');
    $Qdel->bindTable(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qdel->bindInt(':id', $id);
    $Qdel->setLogging($_SESSION['module'], $id);
    $Qdel->execute();

    if ( $Qdel->affectedRows() ) {
      lC_Cache::clear('credit-cards');

      return true;
    }

    return false;
  }
 /*
  * Batch delete credit card records
  *
  * @param array $batch An array of credit card id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Credit_cards_Admin::delete($id);
    }
    return true;
  }
 /*
  * Update the credit card status
  *
  * @param integer $id The credit card id to update
  * @param boolean $cstatus Set true for active status, false for inactive status
  * @access public
  * @return boolean
  */
  public static function updateStatus($id, $cstatus) {
    global $lC_Database;

    $status = ($cstatus == '0') ? true : false;

    $Qcc = $lC_Database->query('update :table_credit_cards set credit_card_status = :credit_card_status where id = :id');
    $Qcc->bindTable(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcc->bindInt(':credit_card_status', $status);
    $Qcc->bindInt(':id', $id);
    $Qcc->setLogging($_SESSION['module'], $id);
    $Qcc->execute();

    if ( $Qcc->affectedRows() ) {
      lC_Cache::clear('credit-cards');

      return true;
    }
  }
}
?>