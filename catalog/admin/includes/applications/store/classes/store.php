<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: store.php v1.0 2013-08-08 datazen $
*/
ini_set('error_reporting', 0);

global $lC_Vqmod;

require_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/classes/addons.php'));
require_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/classes/transport.php'));
require_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/updates/classes/updates.php'));
require_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/languages/classes/languages.php'));

if (!class_exists('lC_Store_Admin')) {
  class lC_Store_Admin { 
    /*
    * Returns the addons datatable data for listings
    *
    * @access public
    * @return array
    */
    public static function getAll() {
      global $lC_Database, $lC_Language, $_module;

      $lC_Language->loadIniFile('store.php');

      $media = $_GET['media'];

      $type = (isset($_GET['type']) && $_GET['type'] != NULL) ? strtolower($_GET['type']) : NULL;

      $Qaddons = self::getAvailbleAddons();    

      $result = array('aaData' => array());

      foreach ( $Qaddons as $key => $addon ) {
        /*VQMOD1*/
        $from_store = (isset($addon['from_store']) && $addon['from_store'] == '1') ? true : false;
        $featured = ($from_store && isset($addon['featured']) && $addon['featured'] == '1') ? '<span class="icon-star mid-margin-left icon-orange with-tooltip" title="' . $lC_Language->get('text_featured') . '" style="cursor:pointer; vertical-align:-35%;"></span>' : NULL;
        $inCloud = ($from_store) ? '<span class="mid-margin-left icon-cloud icon-green with-tooltip" title="' . $lC_Language->get('text_in_cloud') . '" style="vertical-align:-35%;"></span>' : NULL;
        $isInternal = (strstr($addon['title'], '(IO)')) ? '<span class="red strong">INTERNAL ONLY</span>' : NULL;
        $upsell = '';
        if (  $type != NULL && ($type == $addon['type'] || ($type == 'featured' && $addon['featured'] == '1'))   ) {
          $mobileEnabled = (isset($addon['mobile']) && $addon['mobile'] == true) ? '<span class="mid-margin-left icon-mobile icon-blue with-tooltip" title="' . $lC_Language->get('text_mobile_enabled') . '" style="vertical-align:-40%;"></span>' : '';

          $imgWidth = ($type == 'templates') ? '160px' : '80px';
          $imgHeight = ($type == 'templates') ? '120px' : '60px';
          if ($from_store === false) {
            $thumb = '<div style="position:relative;">' . $addon['thumbnail'] . '<div class="version-tag"><span class="tag black-gradient">' . $addon['version'] . '</span></div></div>';
          } else {
            $thumb = '<div style="position:relative;"><img width="' . $imgWidth . '" height="' . $imgHeight . '" src="' . $addon['thumbnail'] . '" alt="' . $addon['title'] . '"><div class="version-tag"><span class="tag black-gradient">' . $addon['version'] . '</span></div></div>';
          }
          if ($type == 'pro template pack') $upsell = '<div class="anthracite mid-margin-top">' . $lC_Language->get('text_free_with_pro_edition') . '</div>';
          $title = '<div style="position:relative;"><strong>' . str_replace(' ', '&nbsp;', $addon['title']) . '</strong><br />' . lc_image('../images/stars_' . $addon['rating'] . '.png', sprintf($lC_Language->get('rating_from_5_stars'), $addon['rating']), null, null, 'class="mid-margin-top small-margin-bottom no-margin-left"') . $mobileEnabled . $inCloud . $featured . '<br /><small>' . str_replace(' ', '&nbsp;', $addon['author']) . $upsell . '</small>';
          $desc = ($media != 'tablet-portrait' && $media != 'tablet-landscape') ? substr($addon['description'], 0, 300) . '...' : null;     

          //check if template or language
          $tlID = self::_isInstalled($addon['code'], $addon['type']);
          
          if ($addon['installed'] == '1' || $tlID) { 
            $bg = ($addon['enabled'] == '1' || $tlID) ? ' green-gradient' : ' silver-gradient'; 
            $action = '<button onclick="editAddon(\'' . $addon['code'] . '\',\'' . urlencode($addon['type']) . '\',\'' . $tlID . '\');" class="button icon-gear glossy' . $bg . '">' . $lC_Language->get('button_setup') . '</button><div class="mid-margin-top"><a href="#"><!-- span class="icon-search">More Info</span --></a></div>';
          } else {  
            $action = '<button onclick="installAddon(\'' . $addon['code'] . '\',\'' . urlencode($addon['type']) . '\');" class="button icon-download orange-gradient glossy">' . $lC_Language->get('button_install') . '</button><div class="mid-margin-top"><a href="#"><!-- span class="icon-search">More Info</span --></a></div>';
          }
          if ($isInternal != NULL) $action .= $isInternal;

          $result['aaData'][] = array("$thumb", "$title", "$desc", "$action");
        }
      } 

      return $result;
    }
    /*
    * Checks to see if the template or language addon is installed
    *
    * @access protected
    * @return boolean
    */    
    protected static function _isInstalled($key, $type) {
      global $lC_Database;
      
      $id = false;
      switch ($type) {
        case 'templates' :
          $code = end(explode('_', $key));
          $Qchk = $lC_Database->query('select id from :table_templates where code = :code');
          $Qchk->bindTable(':table_templates', TABLE_TEMPLATES);
          $Qchk->bindValue(':code', $code);
          $Qchk->execute(); 
          
          if ($Qchk->numberOfRows() > 0) {
            $id = $Qchk->valueInt('id');
            if (!file_exists(DIR_FS_CATALOG . 'templates/' . $code . '.php')) $id = false;
          }
          break;
          
        case 'languages' :
          $Qchk = $lC_Database->query('select languages_id from :table_languages where code = :code');
          $Qchk->bindTable(':table_languages', TABLE_LANGUAGES);
          $Qchk->bindValue(':code', $key);
          $Qchk->execute(); 
          
          if ($Qchk->numberOfRows() > 0) {
            $id = $Qchk->valueInt('languages_id');
            if (!file_exists(DIR_FS_CATALOG . 'includes/languages/' . $key . '.xml')) $id = false;
          }        
          break;
          
        default:
      }
      
      return $id;
    }
    /*
    * Return the addon information
    *
    * @param integer $id The addon name
    * @access public
    * @return array
    */
    public static function getData($name) {    
      global $lC_Database, $lC_Language, $lC_Vqmod, $lC_Currencies;

      $result = array();

      include_once(DIR_FS_CATALOG . 'addons/' . $name . '/controller.php');
      $addon = new $name();

      $blurb = ($addon->getAddonBlurb()) ? $addon->getAddonBlurb() : null;

      $result['desc'] = '<div class="margin-bottom" style="width:100%;">
                           <div class="float-left margin-right">' . $addon->getAddonThumbnail() . '</div>
                             <div style="width:90%;">
                               <div class="strong">' . $addon->getAddonTitle() . '</div>
                               <div>' . lc_image('../images/stars_' . $addon->getAddonRating() . '.png', sprintf($lC_Language->get('rating_from_5_stars'), $addon->getAddonRating()), null, null, 'class="mid-margin-top small-margin-bottom"') . '</div>
                               <div><small>' . $addon->getAddonAuthor() . '</small></div>
                               <div style="position:absolute; right:0; top:0;"><button id="uninstallButton" onclick="uninstallAddon(\'' . $addon->getAddonCode() . '\',\'' . urlencode($addon->getAddonTitle()) . '\', \'' . $addon->getAddonType() . '\');" class="button icon-undo red-gradient glossy"><span>Uninstall</span></button></div>
                              </div>
                            </div>' . $blurb . '
                          </div>';

      $cnt = 0;
      $keys = '';
      foreach ( $addon->getKeys() as $key ) {
        $Qkey = $lC_Database->query('select configuration_title, configuration_value, configuration_description, use_function, set_function from :table_configuration where configuration_key = :configuration_key');
        $Qkey->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qkey->bindValue(':configuration_key', $key);
        $Qkey->execute();
        $keys .= '<label for="' . $Qkey->value('configuration_title') . '" class="label"><strong>' . $Qkey->value('configuration_title') . '</strong></label>';
        if ( !lc_empty($Qkey->value('set_function')) ) {
          $keys .= lc_call_user_func($Qkey->value('set_function'), $Qkey->value('configuration_value'), $key);
        } else {
          if (stristr($key, 'password')) {
            $keys .= lc_draw_password_field('configuration[' . $key . ']', 'class="input" onfocus="this.select();"', $Qkey->value('configuration_value'));
          } else {
            if (preg_match('/(_COST|_HANDLING|_PRICE|_FEE|_MINIMUM_ORDER)$/i',$key)) {
              $keys .= '<div class="inputs" style="display:inline; padding:8px 0;">' .
              '  <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
              lc_draw_input_field('configuration[' . $key . ']', @number_format($Qkey->value('configuration_value'), DECIMAL_PLACES), 'class="input-unstyled" onfocus="this.select();"') .
              '</div>'; 
            } else {
              $keys .= lc_draw_input_field('configuration[' . $key . ']', $Qkey->value('configuration_value'), 'class="input" onfocus="this.select();"');
            }
          }
        }
        $keys .= '<span class="info-spot on-left margin-left"><span class="icon-info-round icon-silver"></span><span class="info-bubble">' . $Qkey->value('configuration_description') . '</span></span><br /><br />';
        $cnt++;
      }
      $result['keys'] = substr($keys, 0, strrpos($keys, '<br /><br />'));
      $result['totalKeys'] = $cnt;

      return $result;
    }  
    /*
    * Save the addon information
    *
    * @param array $data An array containing the addon information
    * @access public
    * @return boolean
    */
    public static function save($data) {
      global $lC_Database;

      $error = false;

      $lC_Database->startTransaction();

      foreach ( $data['configuration'] as $key => $value ) {
        $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindValue(':configuration_value', is_array($data['configuration'][$key]) ? implode(',', $data['configuration'][$key]) : $value);
        $Qupdate->bindValue(':configuration_key', $key);
        $Qupdate->setLogging($_SESSION['module']);
        $Qupdate->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
          break;
        }
      }

      if ( $error === false ) {
        $lC_Database->commitTransaction();

        self::_resetAddons(); 

        return true;
      }

      $lC_Database->rollbackTransaction();

      return false;
    }  

    /*
    * Returns the app store menu listing
    *
    * @access public
    * @return string
    */
    public static function drawMenu() {
      foreach ( self::getAllTypes() as $key => $type ) {
        $menu .= '<li style="cursor:pointer;" class="message-menu store-menu-' . strtolower(str_replace(' ', '-', $type['text'])) . '" id="menuType' . str_replace(' ', '', ucwords($type['text'])) . '">' . 
        '  <a href="javascript:void(0);" class="" id="menuLink' . (int)$type['id'] . '" onclick="showAddonType(\'' . lc_output_string_protected($type['text']) . '\');">' . 
        '    <span class="message-status" style="padding-top:8px;"><img src="' . $type['icon'] . '" alt="' . $type['text'] . '"></span>' .
        '     <br><strong>' . lc_output_string_protected($type['text']) . '</strong>' .
        '   </a>' .
        ' </li>';               
      }

      return $menu;
    }
    /*
    * Get all the app store types
    *
    * @access public
    * @return array
    */
    public static function getAllTypes() {

      $request = array('storeName' => STORE_NAME,
                       'storeWWW' => HTTP_SERVER . DIR_WS_HTTP_CATALOG,
                       'storeSSL' => HTTPS_SERVER . DIR_WS_HTTPS_CATALOG);

      $checksum = hash('sha256', json_encode($request));
      $request['checksum'] = $checksum;

      $api_version = (defined('API_VERSION') && API_VERSION != NULL) ? API_VERSION : '1_0';
      $resultXML = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/' . $api_version . '/store/types/?ref=' . $_SERVER['SCRIPT_FILENAME'], 'method' => 'post', 'parameters' => $request, 'timeout' => 10));

      $types = utility::xml2arr($resultXML);     

      return $types['data'];
    }  
    /*
    * Get all the app store available add ons
    *
    * @access public
    * @return array
    */
    public static function getAvailbleAddons() {

      $installed = self::getInstalledAddons();

      $request = array('storeName' => STORE_NAME,
                       'storeWWW' => HTTP_SERVER . DIR_WS_HTTP_CATALOG,
                       'storeSSL' => HTTPS_SERVER . DIR_WS_HTTPS_CATALOG);

      $checksum = hash('sha256', json_encode($request));
      $request['checksum'] = $checksum;
      $request['instID'] = INSTALLATION_ID;

      $api_version = (defined('API_VERSION') && API_VERSION != NULL) ? API_VERSION : '1_0';
      $resultXML = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/' . $api_version . '/store/addons/?ref=' . $_SERVER['SCRIPT_FILENAME'], 'method' => 'post', 'parameters' => $request, 'timeout' => 10));

      $available = utility::xml2arr($resultXML);  

      $addons = array();
      $codeArr = array();
      foreach($installed as $key => $val) {
        $codeArr[$val['code']] = true;  
        $addons[] = array('name' => $val['name'],
                          'code' => $val['code'],
                          'type' => $val['type'],
                          'title' => $val['title'],
                          'description' => $val['description'],
                          'rating' => $val['rating'],
                          'author' => $val['author'],
                          'authorWWW' => $val['authorWWW'],
                          'thumbnail' => $val['thumbnail'],
                          'version' => $val['version'],
                          'compatibility' => $val['compatibility'],
                          'installed' => $val['installed'],
                          'mobile' => $val['mobile'],
                          'enabled' => $val['enabled'],
                          'from_store' => false,
                          'featured' => false,
                          'featured_in_group' => false);
      }

      foreach($available['data'] as $key => $val) {

        if (array_key_exists($val['code'], $codeArr)) continue;

        $addons[] = array('name' => $val['code'] . '/controller.php',
                          'code' => $val['code'],
                          'type' => $val['type'],
                          'title' => self::_decodeText($val['title']),
                          'description' => self::_decodeText($val['description']),
                          'rating' => $val['rating'],
                          'author' => $val['author'],
                          'authorWWW' => $val['authorWWW'],
                          'thumbnail' => $val['thumbnail'],
                          'version' => $val['version'],
                          'compatibility' => $val['compatibility'],
                          'installed' => $val['installed'],
                          'mobile' => $val['mobile'],
                          'enabled' => $val['enabled'],
                          'from_store' => true,
                          'featured' => $val['featured'],
                          'featured_in_group' => $val['featured_in_group']);
      }    

      return $addons;
    } 

    private static function _decodeText($text) {
      return str_replace('-AMP-', '&', urldecode($text));
    }  
    /*
    * Get the locally installed addons
    *
    * @access public
    * @return array
    */
    public static function getInstalledAddons() {   
      global $lC_Vqmod;

      $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons');
      $lC_DirectoryListing->setRecursive(true);
      $lC_DirectoryListing->setIncludeDirectories(false);
      $lC_DirectoryListing->setAddDirectoryToFilename(true);
      //  $lC_DirectoryListing->setStats(true);
      $lC_DirectoryListing->setCheckExtension('php');

      $addons = array();
      foreach ( $lC_DirectoryListing->getFiles() as $addon ) { 
        $ao = utility::cleanArr($addon);
        if (!stristr($ao['name'], 'controller.php')) continue;      

        $class = substr($ao['name'], 0, strpos($ao['name'], '/'));   
        if (file_exists(DIR_FS_CATALOG . 'addons/' . $ao['name'])) {
          include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/' . $ao['name']));
          $aoData = new $class();
          $addon['code'] = substr($ao['name'], 0, strpos($ao['name'], '/'));
          $addon['type'] = $aoData->getAddonType();
          $addon['title'] = $aoData->getAddonTitle();
          $addon['description'] = $aoData->getAddonDescription();
          $addon['rating'] = $aoData->getAddonRating();
          $addon['author'] = $aoData->getAddonAuthor();
          $addon['authorWWW'] = $aoData->getAddonAuthorWWW();
          $addon['thumbnail'] = $aoData->getAddonThumbnail();
          $addon['version'] = $aoData->getAddonVersion();
          $addon['compatibility'] = $aoData->getCompatibility();
          $addon['installed'] = $aoData->isInstalled();
          $addon['mobile'] = $aoData->isMobileEnabled();
          $addon['enabled'] = $aoData->isEnabled();
          $addons[] = $addon;
        }
      }

      usort($addons, "self::_usortAddonsByRating");   

      return $addons;
    }  
    /*
    * Install the addon module
    *
    * @param string $key A string containing the addon name
    * @access public
    * @return boolean
    */
    public static function install($key) {
      global $lC_Database, $lC_Language, $lC_Vqmod, $lC_Addons;
                                           
      $isTemplate = (strstr($key, 'lC_Template_')) ? true : false;
      if ($isTemplate) {
        $key = str_replace('lC_Template_', '', $key);
        if ( !file_exists(DIR_FS_ADMIN . 'includes/templates/' . $key . '.php') ) {
          // get the addon phar from the store
          self::getAddonPhar($key, 'template');

          // apply the addon phar 
          if (file_exists(DIR_FS_WORK . 'addons/' . $key . '.phar')) {
            lC_Updates_Admin::applyPackage(DIR_FS_WORK . 'addons/' . $key . '.phar', 'template');
          }
        }      
        self::_resetAddons();

        return true;

      } else { // is addon or language

        if ( !file_exists(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php') ) {
          // get the addon phar from the store
          self::getAddonPhar($key);   

          $phar = new Phar(DIR_FS_WORK . 'addons/' . $key . '.phar', 0);
          $meta = $phar->getMetadata();   
         
          // apply the addon phar 
          if (file_exists(DIR_FS_WORK . 'addons/' . $key . '.phar')) {
            lC_Updates_Admin::applyPackage(DIR_FS_WORK . 'addons/' . $key . '.phar');
          }
          
          if ($meta['type'] == 'language') {
            return true;            
          }          
        }

        // sanity check to see if the object is already installed
        $okToInstall = true;

        $Qchk = $lC_Database->query("select id from :table_templates_boxes where modules_group LIKE '%" . $key . "%'");
        $Qchk->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
        $Qchk->execute();

        if ($Qchk->numberOfRows() > 0) $okToInstall = false;

        $Qchk->freeResult();
            
        if ( file_exists(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php') && $okToInstall === true) {
          include_once(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php');

          $addon = $key;
          $addon = new $addon();

          $modules_group = $addon->getAddonType() . '|' . $key;
                      
          $addon->install();

          $code = $addon->getAddonType(); 
          $title = $addon->getAddonTitle();
          // check for payment or shipping modules and adjust addon $code to module $code
          if (is_dir(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/payment/')) {
            $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/payment/');
            $lC_DirectoryListing->setCheckExtension('php');

            foreach ( $lC_DirectoryListing->getFiles() as $ao ) { 
              if (isset($ao['name'])) {
                $code = substr($ao['name'], 0, strpos($ao['name'], '.'));
                $title = str_replace('_', ' ', $key);
                $modules_group = 'payment|' . $key;
                break;  
              }        
            }
          } else if (is_dir(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/shipping/')) {
            $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/shipping/');
            $lC_DirectoryListing->setCheckExtension('php');

            foreach ( $lC_DirectoryListing->getFiles() as $ao ) { 
              if (isset($ao['name'])) {
                $code = substr($ao['name'], 0, strpos($ao['name'], '.'));
                $title = str_replace('_', ' ', $key);
                $modules_group = 'shipping|' . $key;
                break;  
              }        
            }
          } 

          if (empty($code) === false) {     
            $Qdel = $lC_Database->query('delete from :table_templates_boxes where modules_group = :modules_group');
            $Qdel->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
            $Qdel->bindValue(':modules_group', $modules_group);
            $Qdel->execute();          

            $Qinstall = $lC_Database->query('insert into :table_templates_boxes (title, code, author_name, author_www, modules_group) values (:title, :code, :author_name, :author_www, :modules_group)');
            $Qinstall->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
            $Qinstall->bindValue(':title', $title);
            $Qinstall->bindValue(':code', $code);
            $Qinstall->bindValue(':author_name', $addon->getAddonAuthor());
            $Qinstall->bindValue(':author_www', $addon->getAddonAuthorWWW());
            $Qinstall->bindValue(':modules_group', $modules_group);
            $Qinstall->execute();

            self::_resetAddons();

            return true;
          }
        }
      }

      return false;
    }
    /*
    * Uninstall the payment module
    *
    * @param string $key A string containing the payment module name
    * @access public
    * @return boolean
    */
    public static function uninstall($key) {
      global $lC_Database, $lC_Language, $lC_Vqmod;

      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php') ) {

        include_once(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php');

        $addon = new $key();
        $addon->remove();

        $modules_group = $addon->getAddonType() . '|' . $key;

        $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/' . $addon->getAddonType());
        $lC_DirectoryListing->setCheckExtension('php');

        $code = $addon->getAddonType(); 
        // check for payment or shipping modules and adjust addon $code to module $code
        if (is_dir(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/payment/')) {
          $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/payment/');
          $lC_DirectoryListing->setCheckExtension('php');

          foreach ( $lC_DirectoryListing->getFiles() as $ao ) { 
            if (isset($ao['name'])) {
              $code = substr($ao['name'], 0, strpos($ao['name'], '.'));
              break;  
            }        
          }
        } else if (is_dir(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/shipping/')) {
          $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/shipping/');
          $lC_DirectoryListing->setCheckExtension('php');

          foreach ( $lC_DirectoryListing->getFiles() as $ao ) { 
            if (isset($ao['name'])) {
              $code = substr($ao['name'], 0, strpos($ao['name'], '.'));
              break;  
            }        
          }
        }      

        $Qdel = $lC_Database->query('delete from :table_templates_boxes where code = :code and modules_group = :modules_group');
        $Qdel->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
        $Qdel->bindValue(':code', $code);
        $Qdel->bindValue(':modules_group', $addon->getAddonType() . '|' . $key);
        $Qdel->execute();

        if ($addon->hasKeys()) {
          $Qdel = $lC_Database->query('delete from :table_configuration where configuration_key in (":configuration_key")');
          $Qdel->bindTable(':table_configuration', TABLE_CONFIGURATION);
          $Qdel->bindRaw(':configuration_key', implode('", "', $addon->getKeys()));
          $Qdel->execute();
        }   

        // phsically remove the add-on
        if (isset($key) && empty($key) === false) $_SESSION['deleteAddon'] = $key;   

        self::_resetAddons();

        return true;
      }

      return false;
    } 
    /*
    * Reset the addons data array
    *
    * @access public
    * @return void
    */  
    public static function getAddonPhar($key, $type = 'addon') {  
      // remove the old phar if it exists
      if (file_exists(DIR_FS_WORK . 'addons/' . $key . '.phar')) unlink(DIR_FS_WORK . 'addons/' . $key . '.phar'); 

      $api_version = (defined('API_VERSION') && API_VERSION != NULL) ? API_VERSION : '1_0';

      // add the pubkey
      $pubkey = file_get_contents(DIR_FS_WORK . 'addons/update.phar.pubkey');
      file_put_contents(DIR_FS_WORK . 'addons/' . $key . '.phar.pubkey', $pubkey);      
      
      $response = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/' . $api_version . '/get/' . $key . '?type=addon&ver=' . utility::getVersion() . '&ref=' . urlencode($_SERVER['SCRIPT_FILENAME']), 'method' => 'get', 'timeout' => 10));

      if (strlen($response) == 0) {
        ini_set('default_socket_timeout', 10); // set the timeout to 10 seconds
        $response = file_get_contents('https://api.loadedcommerce.com/' . $api_version . '/get/' . $key . '?type=addon&ver=' . utility::getVersion() . '&ref=' . urlencode($_SERVER['SCRIPT_FILENAME']));
        ini_set('default_socket_timeout', 0); // set the timeout back to server default
      }

      return file_put_contents(DIR_FS_WORK . 'addons/' . $key . '.phar', $response);    
    }
    /*
    * Reset the addons data array
    *
    * @access public
    * @return void
    */
    private static function _resetAddons() {
      if (isset($_SESSION['lC_Addons_Admin_data'])) unset($_SESSION['lC_Addons_Admin_data']);
      lC_Cache::clear('modules-addons');
      lC_Cache::clear('modules-payment');
      lC_Cache::clear('configuration');
      lC_Cache::clear('templates');
      lC_Cache::clear('addons');
      lC_Cache::clear('vqmoda');
      lC_Cache::clear('vqmods');    
    } 
    /*
    * Sort addons listing by rating
    *
    * @param array  $a  The first comparator
    * @param array  $b  The second comparator
    * @access public
    * @return integer
    */  
    private static function _usortAddonsByRating($a, $b) {
      return $a['rating'] == $b['rating'] ? 0 : $a['rating'] > $b['rating'] ? -1 : 1;
    } 
  }
}
?>