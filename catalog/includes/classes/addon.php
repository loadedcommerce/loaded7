<?php
/*
  $Id: addon.inc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
*/   
abstract class lC_Addon { 

  protected $_type,
            $_code,
            $_title,
            $_description,
            $_blurb,
            $_rating = '5',
            $_author,
            $_authorWWW,
            $_thumbnail,
            $_version,
            $_compatibility,
            $_enabled,
            $_mobile_enabled,
            $_auto_install = false,
            $_valid;
    
  public function isEnabled() {
    return $this->_enabled;
  }  
  
  public function isMobileEnabled() {
    return $this->_mobile_enabled;
  }    
  
  public function getAddonType() {
    return $this->_type;
  }
  
  public function getAddonCode() {
    return $this->_code;
  }
    
  public function getAddonTitle() {
    return $this->_title;
  }  
  
  public function getAddonDescription() {
    return $this->_description;
  } 
  
  public function getAddonBlurb() {
    return $this->_blurb;
  }    
  
  public function getAddonRating() {
    return $this->_rating;
  }  

  public function getAddonAuthor() {
    return $this->_author;
  }  
  
  public function getAddonAuthorWWW() {
    return $this->_authorWWW;
  }    
  
  public function getAddonThumbnail() {
    return $this->_thumbnail;
  }  
  
  public function getAddonVersion() {
    return $this->_version;
  }  
  
  public function getCompatibility() {
    return $this->_compatibility;
  }  
  
  public function isAutoInstall() {
    return $this->_auto_install;
  }   
  
  public function hasKeys() {
    static $has_keys;

    if (isset($has_keys) === false) {
      $has_keys = (sizeof($this->getKeys()) > 0) ? true : false;
    }

    return $has_keys;
  } 
 /**
  * Inject the language definitions
  *
  * @access protected
  * @return void
  */   
  protected function getDefinitions() {
    global $lC_Language;

    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->getAddonCode() . '/languages/' . $lC_Language->getCode() . '.xml')) {  
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->getAddonCode() . '/languages/' . $lC_Language->getCode() . '.xml');
    }  
  }   
 /**
  * Remove the module configuration keys
  *
  * @access public
  * @return array
  */  
  public function remove() {
    global $lC_Database, $lC_Language;

    if ($this->hasKeys()) {
      $Qdel = $lC_Database->query('delete from :table_configuration where configuration_key in (":configuration_key")');
      $Qdel->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qdel->bindRaw(':configuration_key', implode('", "', $this->getKeys()));
      $Qdel->execute();
    }

    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {
      foreach ($lC_Language->extractAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml') as $def) {
        $Qdel = $lC_Database->query('delete from :table_languages_definitions where definition_key = :definition_key and content_group = :content_group');
        $Qdel->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
        $Qdel->bindValue(':definition_key', $def['key']);
        $Qdel->bindValue(':content_group', $def['group']);
        $Qdel->execute();
      }
      
      lC_Cache::clear('languages');
    }
  }  
}
?>