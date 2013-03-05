<?php
/**
  $Id: session.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Services_session {
  function start() {
    global $request_type, $lC_Session, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/classes/session.php'));
    $lC_Session = lC_Session::load();

    if (SERVICE_SESSION_FORCE_COOKIE_USAGE == '1') {
      lc_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*90);

      if (isset($_COOKIE['cookie_test'])) {
        $lC_Session->start();
      }
    } elseif (SERVICE_SESSION_BLOCK_SPIDERS == '1') {
      $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
      $spider_flag = false;

      if (empty($user_agent) === false) {
        $spiders = file('includes/spiders.txt');

        foreach ($spiders as $spider) {
          if (empty($spider) === false) {
            if (strpos($user_agent, trim($spider)) !== false) {
              $spider_flag = true;
              break;
            }
          }
        }
      }

      if ($spider_flag === false) {
        $lC_Session->start();
      }
    } else {
      $lC_Session->start();
    }

// verify the ssl_session_id
    if ( ($request_type == 'SSL') && (SERVICE_SESSION_CHECK_SSL_SESSION_ID == '1') && (ENABLE_SSL == true) ) {
      if (isset($_SERVER['SSL_SESSION_ID']) && ctype_xdigit($_SERVER['SSL_SESSION_ID'])) {
        if (isset($_SESSION['SESSION_SSL_ID']) === false) {
          $_SESSION['SESSION_SSL_ID'] = $_SERVER['SSL_SESSION_ID'];
        }

        if ($_SESSION['SESSION_SSL_ID'] != $_SERVER['SSL_SESSION_ID']) {
          $lC_Session->destroy();

          lc_redirect(lc_href_link(FILENAME_INFO, 'ssl_check', 'AUTO'));
        }
      }
    }

// verify the browser user agent
    if (SERVICE_SESSION_CHECK_USER_AGENT == '1') {
      $http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

      if (isset($_SESSION['SESSION_USER_AGENT']) === false) {
        $_SESSION['SESSION_USER_AGENT'] = $http_user_agent;
      }

      if ($_SESSION['SESSION_USER_AGENT'] != $http_user_agent) {
        $lC_Session->destroy();

        lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
      }
    }

// verify the IP address
    if (SERVICE_SESSION_CHECK_IP_ADDRESS == '1') {
      if (isset($_SESSION['SESSION_IP_ADDRESS']) === false) {
        $_SESSION['SESSION_IP_ADDRESS'] = lc_get_ip_address();
      }

      if ($_SESSION['SESSION_IP_ADDRESS'] != lc_get_ip_address()) {
        $lC_Session->destroy();

        lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
      }
    }

    return true;
  }

  function stop() {
    global $lC_Session;

    $lC_Session->close();

    return true;
  }
}
?>