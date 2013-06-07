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

  public    $_type,
            $_code,
            $_title,
            $_description,
            $_rating = '5',
            $_author,
            $_thumbnail,
            $_version,
            $_compatibility,
            $_enabled,
            $_valid;
  
  final public function isValid() {
    return true;
  }  
  
  public function isEnabled() {
    return $this->_enabled;
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
  
  public function getAddonRating() {
    return $this->_rating;
  }  

  public function getAddonAuthor() {
    return $this->_author;
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
  
  public function hasKeys() {
    static $has_keys;

    if (isset($has_keys) === false) {
      $has_keys = (sizeof($this->getKeys()) > 0) ? true : false;
    }

    return $has_keys;
  }  
}
?>