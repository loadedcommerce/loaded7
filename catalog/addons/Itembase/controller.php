<?php
/**
  @package    catalog::addons
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: controller.php v1.0 2013-08-08 datazen $
*/
class Itembase extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function Itembase() {
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'connectors';
   /**
    * The addon class name
    */    
    $this->_code = 'Itembase';
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_connectors_ib_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_connectors_ib_description');
   /**
    * The developers name
    */    
    $this->_author = 'itembase GmbH';
   /**
    * The developers web address
    */    
    $this->_authorWWW = 'http://www.itembase.com';
   /**
    * The addon version
    */     
    $this->_version = '4.1.0';
   /**
    * The Loaded 7 core compatibility version
    */     
    $this->_compatibility = '7.002.0.0'; // the addon is compatible with this core version and later   
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/itembase.png', $this->_title);
   /**
    * The addon enable/disable switch
    */
    $this->_enabled = (defined('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false;
   /**
    * Automatically install the module
    */ 
    $this->_auto_install = true;
  }
 /**
  * Checks to see if the addon has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_STATUS');
  }
 /**
  * Install the addon
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    $file = __DIR__ .'/itembase_export.php';
    $newFile = DIR_FS_CATALOG.'itembase_export.php';
    if (!copy($file, $newFile)) {
//          FB::log(  "failed to copy $file...\n");
    } else{
        //FB::log( "copy successful");
    }

    if (!$this->_checkStatus()) {
      //don't overwrite values in itembase keys table.
      if (!defined('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_API')) {
          $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Itembase API key', 'ADDONS_CONNECTORS_" . strtoupper($this->_code) . "_API', '', 'Enter your itembase API key.', '6', '10',now())");
      }
      if (!defined('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_SECRET')) {
          $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Itembase Secret key', 'ADDONS_CONNECTORS_" . strtoupper($this->_code) . "_SECRET', '', 'Enter your itembase Secret key.', '6', '20',now())");
      }

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable AddOn', 'ADDONS_CONNECTORS_" . strtoupper($this->_code) . "_STATUS', '1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Export transactions', 'ADDONS_CONNECTORS_" . strtoupper($this->_code) . "_TRANSACTION_EXPORT', '1', 'Export transactions.', '6', '30', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Export products', 'ADDONS_CONNECTORS_" . strtoupper($this->_code) . "_PRODUCT_EXPORT', '1', 'Export products.', '6', '30', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Debug mode', 'ADDONS_CONNECTORS_" . strtoupper($this->_code) . "_DEBUG', '-1', 'Debug mode.', '6', '30', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Itembase token', 'ADDONS_CONNECTORS_" . strtoupper($this->_code) . "_TOKEN', '', '', '6', '40',now())");

        lC_Cache::clear('configuration');

        $responseData = $this->itembaseRegistration();
        if (!isset($responseData['errors'])) {
            $lC_Database->simpleQuery("update " . TABLE_CONFIGURATION . " set configuration_value = '". $responseData['shops'][0]['api_key'] ."' where configuration_key = 'ADDONS_CONNECTORS_ITEMBASE_API'");
            $lC_Database->simpleQuery("update " . TABLE_CONFIGURATION . " set configuration_value = '". $responseData['shops'][0]['secret'] ."' where configuration_key = 'ADDONS_CONNECTORS_ITEMBASE_SECRET'");
            lC_Cache::clear('configuration');
        }

    }
  }
 /**
  * Check the addon install status
  *
  * @access public
  * @return void
  */
  private function _checkStatus() {
    $addons = '';
    if (file_exists('../includes/work/cache/addons.cache')) {
      $addons = @file_get_contents('../includes/work/cache/addons.cache');
    }

    return (strstr($addons, 'Itembase/controller.php') != '') ? true : false;
  }

    /**
   * remove the add-on module and SQL
   *
   * @access public
   */
  public function remove() {
      global $lC_Database;

      //don't remove Itembase keys from the database
      if ($apiArrayKey = array_search ('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_API', $this->getKeys())) {
          unset($this->_keys[$apiArrayKey]);
      }
      if ($secretArrayKey = array_search ('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_SECRET', $this->getKeys())) {
          unset($this->_keys[$secretArrayKey]);
      }

      $lC_Database->simpleQuery("delete from  " . TABLE_CONFIGURATION . " WHERE configuration_key = 'ADDONS_CONNECTORS_ITEMBASE_TOKEN'");

      parent::remove();
  }

 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array(
          'ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_STATUS',
          'ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_API',
          'ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_SECRET',
          'ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_TRANSACTION_EXPORT',
          'ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_PRODUCT_EXPORT',
          'ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_DEBUG'
      );
    }

    return $this->_keys;
  }

  public function getAddonBlurb() {
        global $lC_Database, $lC_Language;

        if ($this->_blurb != '') return ($this->_blurb);

        lC_Cache::clear('configuration');

        if ((defined('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_API') && @constant('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_API') === '') &&
            (defined('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_SECRET') && @constant('ADDONS_CONNECTORS_' . strtoupper($this->_code) . '_SECRET') === '')
        ) {

            $responseData = $this->itembaseRegistration();

            if (isset($responseData['errors'])) {
                $this->_blurb = '<p class="big-message margin-bottom orange-gradient anthracite"><span class="big-message-icon icon-speech icon-red"></span><strong class="red">ERRORS:</strong>';
                foreach($responseData['errors'] as $error) {
                    $this->_blurb .= '</br>' . $error;
                }
                $this->_blurb .= '</p>';
            } else {
                $lC_Database->simpleQuery("update " . TABLE_CONFIGURATION . " set configuration_value = '". $responseData['shops'][0]['api_key'] ."' where configuration_key = 'ADDONS_CONNECTORS_ITEMBASE_API'");
                $lC_Database->simpleQuery("update " . TABLE_CONFIGURATION . " set configuration_value = '". $responseData['shops'][0]['secret'] ."' where configuration_key = 'ADDONS_CONNECTORS_ITEMBASE_SECRET'");
                lC_Cache::clear('configuration');
                $this->_blurb = ' ';
            }
        }

        return ($this->_blurb);
    }

    /**
     * Register shop on itembase
     *
     * @return array
     */
    private function itembaseRegistration()
    {
        global $lC_Language;

        require_once(DIR_FS_CATALOG . '/addons/Itembase/modules/ItembaseCommon.php');

        $userName = explode(' ', STORE_OWNER);
        $user = array(
            'email' => STORE_OWNER_EMAIL_ADDRESS,
            'firstname' => isset($userName[0]) ? $userName[0] : '',
            'lastname' => isset($userName[1]) ? $userName[1] : '',
            'street' => '',
            'zip' => '',
            'town' => '',
            'state' => '',
            'country' => '',
            'telephone' => '',
            'fax' => ''
        );
        $shop[] = array(
            'shop_id' => 0,
            'shop_name' => STORE_NAME,
            'shop_url' => HTTP_SERVER . DIR_WS_HTTP_CATALOG,
            'street' => '',
            'zip' => '',
            'town' => '',
            'state' => '',
            'country' => '',
            'telephone' => '',
            'fax' => '',
            'email' => STORE_OWNER_EMAIL_ADDRESS,
        );
        $data = array(
            'user' => $user,
            'shops' => $shop,
            'lang' => substr($lC_Language->getCode(), 0, 2)
        );

        return \ItembaseCommon::shopRegistration('TG9hZGVkNw==', $data);

    }

}
