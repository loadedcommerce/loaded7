<?php
/*
  $Id: session.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Services_session_Admin {
    var $title,
        $description,
        $uninstallable = false,
        $depends,
        $precedes;

    function lC_Services_session_Admin() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/services/session.php');

      $this->title = $lC_Language->get('services_session_title');
      $this->description = $lC_Language->get('services_session_description');
    }

    function install() {
      global $lC_Database;

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Session Expiration Time', 'SERVICE_SESSION_EXPIRATION_TIME', '30', 'The time (in minutes) to keep sessions active for. A value of 0 means until the browser is closed.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Force Cookie Usage', 'SERVICE_SESSION_FORCE_COOKIE_USAGE', '-1', 'Only start a session when cookies are enabled.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Block Search Engine Spiders', 'SERVICE_SESSION_BLOCK_SPIDERS', '-1', 'Block search engine spider robots from starting a session.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Check SSL Session ID', 'SERVICE_SESSION_CHECK_SSL_SESSION_ID', '-1', 'Check the SSL_SESSION_ID on every secure HTTPS page request.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Check User Agent', 'SERVICE_SESSION_CHECK_USER_AGENT', '-1', 'Check the browser user agent on every page request.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Check IP Address', 'SERVICE_SESSION_CHECK_IP_ADDRESS', '-1', 'Check the IP address on every page request.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Regenerate Session ID', 'SERVICE_SESSION_REGENERATE_ID', '-1', 'Regenerate the session ID when a customer logs on or creates an account.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    }

    function remove() {
      global $lC_Database;

      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_SESSION_EXPIRATION_TIME',
                   'SERVICE_SESSION_FORCE_COOKIE_USAGE',
                   'SERVICE_SESSION_BLOCK_SPIDERS',
                   'SERVICE_SESSION_CHECK_SSL_SESSION_ID',
                   'SERVICE_SESSION_CHECK_USER_AGENT',
                   'SERVICE_SESSION_CHECK_IP_ADDRESS',
                   'SERVICE_SESSION_REGENERATE_ID');
    }
  }
?>