<?php
/*
  $Id: store.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
*/
require_once(DIR_FS_CATALOG . 'includes/classes/addons.php');
require_once(DIR_FS_CATALOG . 'includes/classes/transport.php');
require_once(DIR_FS_ADMIN . 'includes/applications/updates/classes/updates.php');

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
      
      $from_store = (isset($addon['from_store']) && $addon['from_store'] == '1') ? true : false;
      $featured = ($from_store && isset($addon['featured']) && $addon['featured'] == '1') ? '<span class="icon-star mid-margin-left icon-orange with-tooltip" title="' . $lC_Language->get('text_featured') . '" style="cursor:pointer; vertical-align:-35%;"></span>' : NULL;
      $inCloud = ($from_store) ? '<span class="mid-margin-left icon-cloud icon-green with-tooltip" title="' . $lC_Language->get('text_in_cloud') . '" style="vertical-align:-35%;"></span>' : NULL;
      
      if (  $type != NULL && ($type == $addon['type'] || ($type == 'featured' && $addon['featured'] == '1'))   ) {
        $mobileEnabled = (isset($addon['mobile']) && $addon['mobile'] == true) ? '<span class="mid-margin-left icon-mobile icon-blue with-tooltip" title="' . $lC_Language->get('text_mobile_enabled') . '" style="vertical-align:-40%;"></span>' : '';
        if ($from_store === false) {
          $thumb = '<div style="position:relative;">' . $addon['thumbnail'] . '<div class="version-tag"><span class="tag black-gradient">' . $addon['version'] . '</span></div></div>';
        } else {
          $thumb = '<div style="position:relative;"><img src="' . $addon['thumbnail'] . '" alt="' . $addon['title'] . '"><div class="version-tag"><span class="tag black-gradient">' . $addon['version'] . '</span></div></div>';
        }
        
        $title = '<div style="position:relative;"><strong>' . str_replace(' ', '&nbsp;', $addon['title']) . '</strong><br />' . lc_image('../images/stars_' . $addon['rating'] . '.png', sprintf($lC_Language->get('rating_from_5_stars'), $addon['rating']), null, null, 'class="mid-margin-top small-margin-bottom"') . $mobileEnabled . $inCloud . $featured . '<br /><small>' . str_replace(' ', '&nbsp;', $addon['author']) . '</small>';
        $desc = substr($addon['description'], 0, 300) . '...';     
       
        if ($addon['installed'] == '1') { 
          $bg = ($addon['enabled'] == '1') ? ' green-gradient' : ' silver-gradient'; 
          $action = '<button onclick="editAddon(\'' . $addon['code'] . '\',\'' . urlencode($addon['type']) . '\');" class="button icon-gear glossy' . $bg . '">Setup</button><div class="mid-margin-top"><a href="#"><span class="icon-search">More Info</span></a></div>';
        } else {  
          $action = '<button onclick="installAddon(\'' . $addon['code'] . '\',\'' . urlencode($addon['type']) . '\');" class="button icon-download orange-gradient glossy">Install</button><div class="mid-margin-top"><a href="#"><span class="icon-search">More Info</span></a></div>';
        }

        $result['aaData'][] = array("$thumb", "$title", "$desc", "$action");
      }
    } 

    return $result;
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
                             <div style="position:absolute; right:0; top:0;"><button id="uninstallButton" onclick="uninstallAddon(\'' . $addon->getAddonCode() . '\',\'' . urlencode($addon->getAddonTitle()) . '\');" class="button icon-undo red-gradient glossy"><span class="button-text">Uninstall</span></button></div>
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
          if (stristr($key, '_COST') || stristr($key, '_HANDLING') || stristr($key, '_PRICE') || stristr($key, '_FEE') || stristr($key, '_MINIMUM_ORDER')) {
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

      lC_Cache::clear('modules-addons');
      lC_Cache::clear('configuration');
      lC_Cache::clear('templates');
      lC_Cache::clear('addons');
      lC_Cache::clear('vqmoda');
      lC_Cache::clear('vqmods');

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
      $menu .= '<li style="cursor:pointer;" class="message-menu store-menu-' . strtolower($type['text']) . '" id="menuType' . ucwords($type['text']) . '">' . 
               '  <a href="javascript:void(0);" class="" id="menuLink' . (int)$type['id'] . '" onclick="showAddonType(\'' . (int)$type['id'] . '\', \'' . lc_output_string_protected($type['text']) . '\');">' . 
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
    
    $resultXML = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/1_0/store/types/', 'method' => 'post', 'parameters' => $request));

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
    
    $resultXML = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/1_0/store/addons/', 'method' => 'post', 'parameters' => $request));

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
        $GLOBALS[$class] = new $class();
        $addon['code'] = substr($ao['name'], 0, strpos($ao['name'], '/'));
        $addon['type'] = $GLOBALS[$class]->getAddonType();
        $addon['title'] = $GLOBALS[$class]->getAddonTitle();
        $addon['description'] = $GLOBALS[$class]->getAddonDescription();
        $addon['rating'] = $GLOBALS[$class]->getAddonRating();
        $addon['author'] = $GLOBALS[$class]->getAddonAuthor();
        $addon['authorWWW'] = $GLOBALS[$class]->getAddonAuthorWWW();
        $addon['thumbnail'] = $GLOBALS[$class]->getAddonThumbnail();
        $addon['version'] = $GLOBALS[$class]->getAddonVersion();
        $addon['compatibility'] = $GLOBALS[$class]->getCompatibility();
        $addon['installed'] = $GLOBALS[$class]->isInstalled();
        $addon['mobile'] = $GLOBALS[$class]->isMobileEnabled();
        $addon['enabled'] = $GLOBALS[$class]->isEnabled();
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
    global $lC_Database, $lC_Language, $lC_Vqmod;
    
    if ( !file_exists(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php') ) {
      // get the addon phar from the store
      self::_getAddonPhar($key);
      
      // apply the addon phar 
      lC_Updates_Admin::applyPackage(DIR_FS_WORK . 'addons/' . $key . '.phar');
    }
    
    if ( file_exists(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php') ) {

      include_once(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php');

      $addon = $key;
      $addon = new $addon();

      $addon->install();
      
      $modules_group = $addon->getAddonType() . '|' . $key;

      $code = $addon->getAddonType(); 
      if (is_dir(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/' . $addon->getAddonType())) {
        $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $addon->getAddonCode() . '/modules/' . $addon->getAddonType());
        $lC_DirectoryListing->setCheckExtension('php');
            
        foreach ( $lC_DirectoryListing->getFiles() as $ao ) { 
          if (isset($ao['name'])) {
            $code = substr($ao['name'], 0, strpos($ao['name'], '.'));
            break;  
          }        
        }
      }

      if (empty($code) === false) {     
        $Qinstall = $lC_Database->query('insert into :table_templates_boxes (title, code, author_name, author_www, modules_group) values (:title, :code, :author_name, :author_www, :modules_group)');
        $Qinstall->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
        $Qinstall->bindValue(':title', $addon->getAddonTitle());
        $Qinstall->bindValue(':code', $code);
        $Qinstall->bindValue(':author_name', $addon->getAddonAuthor());
        $Qinstall->bindValue(':author_www', $addon->getAddonAuthorWWW());
        $Qinstall->bindValue(':modules_group', $modules_group);
        $Qinstall->execute();
        
        self::_resetAddons();
        lC_Cache::clear('modules-addons');
        lC_Cache::clear('configuration');
        lC_Cache::clear('templates');
        lC_Cache::clear('addons');
        lC_Cache::clear('vqmoda');
        lC_Cache::clear('vqmods');

        return true;
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
      
      $code = '';
      foreach ( $lC_DirectoryListing->getFiles() as $ao ) { 
        if (isset($ao['name'])) {
          $code = substr($ao['name'], 0, strpos($ao['name'], '.'));
          break;  
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

      self::_resetAddons();
      lC_Cache::clear('modules-addons');
      lC_Cache::clear('configuration');
      lC_Cache::clear('templates');
      lC_Cache::clear('addons');
      lC_Cache::clear('vqmoda');
      lC_Cache::clear('vqmods');
      
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
  private static function _getAddonPhar($key) {

    $response = file_get_contents('https://api.loadedcommerce.com/1_0/get/' . $key . '?type=addon&ref=' . urlencode($_SERVER['SCRIPT_FILENAME']));

    return file_put_contents(DIR_FS_WORK . 'addons/update.phar', $response);    
  }
 /*
  * Reset the addons data array
  *
  * @access public
  * @return void
  */
  private static function _resetAddons() {
    if (isset($_SESSION['lC_Addons_data'])) unset($_SESSION['lC_Addons_data']);
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
?>