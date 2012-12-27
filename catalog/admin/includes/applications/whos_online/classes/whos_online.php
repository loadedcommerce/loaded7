<?php
/*
  $Id: whos_online.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Whos_online_Admin class manages whos online feature
*/
class lC_Whos_online_Admin {
 /*
  * Returns the whos online datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() { 
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $lC_Currencies = new lC_Currencies();
    $lC_Tax = new lC_Tax_Admin();
    $lC_GeoIP = lC_GeoIP_Admin::load();
    if ( $lC_GeoIP->isInstalled() ) {
      $lC_GeoIP->activate();
    }
    $xx_mins_ago = time() - 900;
    // remove entries that have expired
    $Qdelete = $lC_Database->query('delete from :table_whos_online where time_last_click < :time_last_click');
    $Qdelete->bindTable(':table_whos_online', TABLE_WHOS_ONLINE);
    $Qdelete->bindValue(':time_last_click', $xx_mins_ago);
    $Qdelete->execute();
       
    $result = array('aaData' => array());                

    $Qwho = $lC_Database->query('select customer_id, full_name, ip_address, time_entry, time_last_click, session_id from :table_whos_online order by time_last_click desc');
    $Qwho->bindTable(':table_whos_online', TABLE_WHOS_ONLINE);
    $Qwho->execute();

    while ( $Qwho->next() ) {
      if (STORE_SESSIONS == 'database') {
        $Qsession = $lC_Database->query('select value from :table_sessions where id = :id');
        $Qsession->bindTable(':table_sessions', TABLE_SESSIONS);
        $Qsession->bindValue(':id', $Qwho->value('session_id'));
        $Qsession->execute();
        $session_data = trim($Qsession->value('value'));
      } else {
        if ( file_exists($lC_Session->getSavePath() . '/sess_' . $Qwho->value('session_id')) && ( filesize($lC_Session->getSavePath() . '/sess_' . $Qwho->value('session_id')) > 0 ) ) {
          $session_data = trim(file_get_contents($lC_Session->getSavePath() . '/sess_' . $Qwho->value('session_id')));
        }
      }
      $navigation = unserialize(lc_get_serialized_variable($session_data, 'lC_NavigationHistory_data', 'array'));
      $last_page = (is_array($navigation)) ? end($navigation) : 0;
      $currency = unserialize(lc_get_serialized_variable($session_data, 'currency', 'string'));
      $cart = unserialize(lc_get_serialized_variable($session_data, 'lC_ShoppingCart_data', 'array'));

      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qwho->value('session_id') . '" id="' . $Qwho->value('session_id') . '"></td>';
      if ( $lC_GeoIP->isActive() && $lC_GeoIP->isValid($Qwho->value('ip_address')) ) {
        $who = '<td>' . lc_image('../images/worldflags/' . $lC_GeoIP->getCountryISOCode2($Qwho->value('ip_address')) . '.png', $lC_GeoIP->getCountryName($Qwho->value('ip_address')) . ', ' . $Qwho->value('ip_address'), 18, 12) . ' ' . strtoupper($lC_GeoIP->getCountryISOCode2($Qwho->value('ip_address'))) . ', ' . $Qwho->value('ip_address') . '</td>';
      } else {
        $who = '<td>' . lc_image('images/pixel_trans.gif', $Qwho->value('ip_address'), 18, 12) . ' ' . $Qwho->value('ip_address') . '</td>';
      }
      $online = '<td>' . gmdate('H:i:s', time() - $Qwho->value('time_entry')) . '</td>';
      $customers = '<td>' . $Qwho->value('full_name') . ' (' . $Qwho->valueInt('customer_id') . ')</td>';
      $click = '<td>' . @date('H:i:s', $Qwho->value('time_last_click')) . '</td>';
      $url = '<td>' . $last_page['page'] . '</td>';
      $total = '<td>' . $lC_Currencies->format($cart['total_cost'], true, $currency) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['whos_online'] < 2) ? '#' : 'javascript://" onclick="showInfo(\'' . $Qwho->value('session_id') . '\')') . '" class="button icon-question-round icon-blue' . ((int)($_SESSION['admin']['access']['whos_online'] < 2) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('button_info')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['whos_online'] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qwho->value('session_id') . '\',\'' . $Qwho->value('full_name') . '\')"') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['whos_online'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';         

      $result['aaData'][] = array("$check", "$who", "$online", "$customers", "$click", "$url", "$total", "$action");      
    }
    
    return $result;
  }
 /*
  * Return the whos online information
  *
  * @param integer $id The session id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database;

    $lC_Currencies = new lC_Currencies();
    $lC_Tax = new lC_Tax_Admin();
    $lC_GeoIP = lC_GeoIP_Admin::load();
    if ( $lC_GeoIP->isInstalled() ) {
      $lC_GeoIP->activate();
    }

    $Qwho = $lC_Database->query('select * from :table_whos_online where session_id = :session_id');
    $Qwho->bindTable(':table_whos_online', TABLE_WHOS_ONLINE);
    $Qwho->bindValue(':session_id', $id);
    $Qwho->execute();

    $data = $Qwho->toArray();

    $data['timeOnline'] = gmdate('H:i:s', time() - $Qwho->value('time_entry'));
    $data['ipAddress'] = $Qwho->value('ip_address');
    if ( $lC_GeoIP->isActive() && $lC_GeoIP->isValid($Qwho->value('ip_address')) ) {
      $data['ipAddress'] .= '<p>' . implode('<br />', $lC_GeoIP->getData($Qwho->value('ip_address'))) . '</p>';
    }
    $data['entryTime'] = @date('H:i:s', $Qwho->value('time_entry'));
    $data['lastClick'] = @date('H:i:s', $Qwho->value('time_last_click'));

    $Qwho->freeResult();

    // get cart contents
    if ( STORE_SESSIONS == 'database' ) {
      $Qsession = $lC_Database->query('select value from :table_sessions where id = :id');
      $Qsession->bindTable(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindValue(':id', $id);
      $Qsession->execute();
      $session_data = trim($Qsession->value('value'));
    } else {
      if ( file_exists($lC_Session->getSavePath() . '/sess_' . $id) && ( filesize($lC_Session->getSavePath() . '/sess_' . $id) > 0 ) ) {
        $session_data = trim(file_get_contents($lC_Session->getSavePath() . '/sess_' . $id));
      }
    }
    $navigation = unserialize(lc_get_serialized_variable($session_data, 'lC_NavigationHistory_data', 'array'));
    $last_page = end($navigation);
    $last_page_url = $last_page['page'];
    if ( isset($last_page['get']['osCsid']) ) {
      unset($last_page['get']['osCsid']);
    }
    if ( sizeof($last_page['get']) > 0 ) {
      $last_page_url .= '?' . lc_array_to_string($last_page['get']);
    }
    $currency = unserialize(lc_get_serialized_variable($session_data, 'currency', 'string'));
    $cart = unserialize(lc_get_serialized_variable($session_data, 'lC_ShoppingCart_data', 'array'));

    $data['cartContents'] = '';
    if ( !empty($cart['contents']) ) {
      $data['cartContents'] .= '<tr>' . "\n" .
                               '  <td colspan="2">&nbsp;</td>' . "\n" .
                               '</tr>' . "\n" .
                               '<tr>' . "\n" .
                               '  <td width="200px" valign="top"><b>' . $lC_Language->get('field_shopping_cart_contents') . '</b></td>' . "\n" .
                               '  <td><table border="0" cellspacing="0" cellpadding="2">' . "\n";
      foreach ($cart['contents'] as $product) {
        $data['cartContents'] .= '<tr>' . "\n" .
                                 '  <td align="right">' . $product['quantity'] . ' x</td>' . "\n" .
                                 '  <td>' . $product['name'] . '</td>' . "\n" .
                                 '</tr>' . "\n";
      }
      $data['cartContents'] .= '  </table></td>' . "\n" .
                               '</tr>' . "\n" .
                               '<tr>' . "\n" .
                               '  <td width="200px"><b>' . $lC_Language->get('field_shopping_cart_total') . '</b></td>' . "\n" .
                               '  <td>' . $lC_Currencies->format($cart['total_cost'], true, $currency) . '</td>' . "\n" .
                               '</tr>' . "\n";
    }

    return $data;
  }
 /*
  * Delete the whos online session
  *
  * @param integer $id The session id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Session, $lC_Database;

    $lC_Session->delete($id);

    $Qwho = $lC_Database->query('delete from :table_whos_online where session_id = :session_id');
    $Qwho->bindTable(':table_whos_online', TABLE_WHOS_ONLINE);
    $Qwho->bindValue(':session_id', $id);
    $Qwho->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete whos online sessions
  *
  * @param array $batch The session id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Whos_online_Admin::delete($id);
    }
    return true;
  } 
}
?>