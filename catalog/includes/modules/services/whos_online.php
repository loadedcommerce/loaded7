<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: whos_online.php v1.0 2013-08-08 datazen $
*/
class lC_Services_whos_online {
  function start() {
    global $lC_Customer, $lC_Database;

    if ($lC_Customer->isLoggedOn()) {
      $wo_customer_id = $lC_Customer->getID();
      $wo_full_name = $lC_Customer->getName();
    } else {
      $wo_customer_id = '';
      $wo_full_name = 'Guest';

      if (SERVICE_WHOS_ONLINE_SPIDER_DETECTION == '1') {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        if (!empty($user_agent)) {
          $spiders = file('includes/spiders.txt');

          foreach ($spiders as $spider) {
            if (!empty($spider)) {
              if ((strpos($user_agent, trim($spider))) !== false) {
                $wo_full_name = $spider;
                break;
              }
            }
          }
        }
      }
    }

    $wo_session_id = session_id();
    $wo_ip_address = lc_get_ip_address();
    $wo_last_page_url = lc_output_string_protected(substr($_SERVER['REQUEST_URI'], 0, 255));

    $current_time = time();
    $xx_mins_ago = ($current_time - 900);

// remove entries that have expired
    $Qwhosonline = $lC_Database->query('delete from :table_whos_online where time_last_click < :time_last_click');
    $Qwhosonline->bindRaw(':table_whos_online', TABLE_WHOS_ONLINE);
    $Qwhosonline->bindValue(':time_last_click', $xx_mins_ago);
    $Qwhosonline->execute();

    $Qwhosonline = $lC_Database->query('select count(*) as count from :table_whos_online where session_id = :session_id');
    $Qwhosonline->bindRaw(':table_whos_online', TABLE_WHOS_ONLINE);
    $Qwhosonline->bindValue(':session_id', $wo_session_id);
    $Qwhosonline->execute();

    if ($Qwhosonline->valueInt('count') > 0) {
      $Qwhosonline = $lC_Database->query('update :table_whos_online set customer_id = :customer_id, full_name = :full_name, ip_address = :ip_address, time_last_click = :time_last_click, last_page_url = :last_page_url where session_id = :session_id');
      $Qwhosonline->bindRaw(':table_whos_online', TABLE_WHOS_ONLINE);
      $Qwhosonline->bindInt(':customer_id', $wo_customer_id);
      $Qwhosonline->bindValue(':full_name', $wo_full_name);
      $Qwhosonline->bindValue(':ip_address', $wo_ip_address);
      $Qwhosonline->bindValue(':time_last_click', $current_time);
      $Qwhosonline->bindValue(':last_page_url', $wo_last_page_url);
      $Qwhosonline->bindValue(':session_id', $wo_session_id);
      $Qwhosonline->execute();
    } else {
      $Qwhosonline = $lC_Database->query('insert into :table_whos_online (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values (:customer_id, :full_name, :session_id, :ip_address, :time_entry, :time_last_click, :last_page_url)');
      $Qwhosonline->bindRaw(':table_whos_online', TABLE_WHOS_ONLINE);
      $Qwhosonline->bindInt(':customer_id', $wo_customer_id);
      $Qwhosonline->bindValue(':full_name', $wo_full_name);
      $Qwhosonline->bindValue(':session_id', $wo_session_id);
      $Qwhosonline->bindValue(':ip_address', $wo_ip_address);
      $Qwhosonline->bindValue(':time_entry', $current_time);
      $Qwhosonline->bindValue(':time_last_click', $current_time);
      $Qwhosonline->bindValue(':last_page_url', $wo_last_page_url);
      $Qwhosonline->execute();
    }

    $Qwhosonline->freeResult();

    return true;
  }

  function stop() {
    return true;
  }
}
?>