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
class lC_Store_Admin { 
 /*
  * Returns the addons datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;
    
    $media = $_GET['media'];

    $Qaddons = self::getAvailbleAddons(); 
    
    $result = array('aaData' => array());
    
    foreach ( $Qaddons as $key => $addon ) {
      $thumb = '<div style="position:relative;">' . $addon['thumbnail'] . '<div class="version-tag"><span class="tag black-gradient">' . $addon['version'] . '</span></div></div>';
      $title = '<div style="position:relative;"><strong>' . str_replace(' ', '&nbsp;', $addon['title']) . '</strong><br />' . lc_image('../images/stars_' . $addon['rating'] . '.png', sprintf($lC_Language->get('rating_from_5_stars'), $addon['rating']), null, null, 'class="mid-margin-top small-margin-bottom"') . '<br /><small>' . str_replace(' ', '&nbsp;', $addon['author']) . '</small>';
      $desc = substr($addon['description'], 0, 300) . '...';
      
      if ($addon['installed'] == '1') {  
        $action = '<button onclick="uninstallAddon(\'' . $addon['code'] . '\',\'' . urlencode($addon['title']) . '\');" class="button icon-gear green-gradient glossy">Setup</button><div class="mid-margin-top"><a href="#"><span class="icon-search">More Info</span></a></div>';
      } else {  
        $action = '<button onclick="installAddon(\'' . $addon['code'] . '\');" class="button icon-gear orange-gradient glossy">Install</button><div class="mid-margin-top"><a href="#"><span class="icon-search">More Info</span></a></div>';
      }

      $result['aaData'][] = array("$thumb", "$title", "$desc", "$action");
    } 

    return $result;
  }
 /*
  * Returns the app store menu listing
  *
  * @access public
  * @return string
  */
  public static function drawMenu() {
    foreach ( self::getAllTypes() as $key => $type ) {
      
      $menu .= '<li class="message-menu ' . 'store-menu-' . strtolower($type['text']) . '" id="menuType' . ucwords($type['text']) . '">' .
               '  <span class="message-status" style="padding-top:14px;">' .
               '     <a href="javascript://" onclick="showType(\'' . (int)$type['id'] . '\', \'' . lc_output_string_protected($type['text']) . '\');" class="new-message" title=""></a>' .
               '   </span>' .
               '   <a id="menuLink' . (int)$type['id'] . '" href="javascript://" onclick="showType(\'' . (int)$type['id'] . '\', \'' . lc_output_string_protected($type['text']) . '\');">' .
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

    $types = array(array('id' => '0', 'text' => 'Payment', 'icon' => 'payment.png'),
                   array('id' => '1', 'text' => 'Shipping', 'icon' => 'shipping.png'),
                   array('id' => '2', 'text' => 'Themes', 'icon' => 'themes.png'),
                   array('id' => '3', 'text' => 'Checkout', 'icon' => 'checkout.png'),
                   array('id' => '4', 'text' => 'Catalog', 'icon' => 'catalog.png'),
                   array('id' => '5', 'text' => 'Admin', 'icon' => 'admin.png'),
                   array('id' => '6', 'text' => 'Reports', 'icon' => 'reports.png'),
                   array('id' => '7', 'text' => 'Connectors', 'icon' => 'connectors.png'),
                   array('id' => '8', 'text' => 'Other', 'icon' => 'other.png'));

    return $types;
  }  
 /*
  * Get all the app store available add ons
  *
  * @access public
  * @return array
  */
  public static function getAvailbleAddons() {
    
    $installed = self::getInstalledAddons();
    
    $desc = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. Sed lectus.';

    $available = array(array('code' => 'Other_Payments',
                             'type' => 'payment', 
                             'title' => 'Other Payments', 
                             'description' => $desc, 
                             'rating' => '4', 
                             'author' => 'Even More Corp.', 
                             'thumbnail' => 'p3.png', 
                             'version' => '5.0.3'),
                             
                       array('code' => 'More_Payments',
                             'type' => 'payment', 
                             'title' => 'More Payments', 
                             'description' => $desc, 
                             'rating' => '3', 
                             'author' => 'Acme, Inc.', 
                             'thumbnail' => 'p4.png', 
                             'version' => '3.1.4'),
                             
                       array('code' => 'IOU_Payments', 
                             'type' => 'payment', 
                             'title' => 'I.O.U. Payments', 
                             'description' => $desc, 
                             'rating' => '3', 
                             'author' => 'Wadayawant, Inc.', 
                             'thumbnail' => 'p5.png', 
                             'version' => '5.7.7'),
                             
                       array('code' => 'Please_Pay_Me', 
                             'type' => 'payment', 
                             'title' => 'Please Pay Me', 
                             'description' => $desc, 
                             'rating' => '2', 
                             'author' => 'Morners, Inc.', 
                             'thumbnail' => 'p6.png', 
                             'version' => '1.0.1'),
                       );

    $result = array_merge((array)$installed, (array)$available);
    
    return $result;
  } 
  
 /*
  * Get the locally installed addons
  *
  * @access public
  * @return array
  */
  public static function getInstalledAddons() {    
    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons');
    $lC_DirectoryListing->setRecursive(true);
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setAddDirectoryToFilename(true);
  //  $lC_DirectoryListing->setStats(true);
    $lC_DirectoryListing->setCheckExtension('php');

    $addons = array();
    foreach ( $lC_DirectoryListing->getFiles() as $addon ) { 
      $ao = utility::cleanArr($addon);
      if ($ao['name'] == 'inc/bootstrap.php') continue;
      $class = substr($ao['name'], 0, strpos($ao['name'], '/'));   
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $ao['name'])) {
        include_once(DIR_FS_CATALOG . 'addons/' . $ao['name']);
        $GLOBALS[$class] = new $class();
        $addon['code'] = substr($ao['name'], 0, strpos($ao['name'], '/'));
        $addon['type'] = $GLOBALS[$class]->getAddonType();
        $addon['title'] = $GLOBALS[$class]->getAddonTitle();
        $addon['description'] = $GLOBALS[$class]->getAddonDescription();
        $addon['rating'] = $GLOBALS[$class]->getAddonRating();
        $addon['author'] = $GLOBALS[$class]->getAddonAuthor();
        $addon['thumbnail'] = $GLOBALS[$class]->getAddonThumbnail();
        $addon['version'] = $GLOBALS[$class]->getAddonVersion();
        $addon['installed'] = $GLOBALS[$class]->isInstalled();
        $addon['enabled'] = $GLOBALS[$class]->isEnabled();
        $addon['valid'] = $GLOBALS[$class]->isValid();        
        $addons[] = $addon;
      }
    }

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

    if ( file_exists(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php') ) {
      //$lC_Language->injectDefinitions(DIR_FS_CATALOG . 'addons/' . $key . '.xml');

      include(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php');

      $addon = $key;
      $addon = new $addon();

      $addon->install();

      lC_Cache::clear('modules-addons');
      lC_Cache::clear('configuration');

      return true;
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
      //$lC_Language->injectDefinitions('modules/payment/' . $key . '.xml');

      include(DIR_FS_CATALOG . 'addons/' . $key . '/controller.php');

      $addon = new $key();
      $addon->remove();

      lC_Cache::clear('modules-addons');
      lC_Cache::clear('configuration');

      return true;
    }

    return false;
  }  
}
?>