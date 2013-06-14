<?php
/**
  $Id: language.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('../includes/classes/language.php'));

class lC_Language_Admin extends lC_Language {

  /* Public methods */
  public function loadIniFile($filename = null, $comment = '#', $language_code = null) {
    global $lC_Addons;
    
    if ( is_null($language_code) ) {
      $language_code = $this->_code;
    }

    if ( $this->_languages[$language_code]['parent_id'] > 0 ) {
      $this->loadIniFile($filename, $comment, $this->getCodeFromID($this->_languages[$language_code]['parent_id']));
    }

    if ( is_null($filename) ) {
      if ( file_exists('includes/languages/' . $language_code . '.php') ) {
        $contents = file('includes/languages/' . $language_code . '.php');
      } else {
        return array();
      }
    } else {
      if ( substr(realpath('includes/languages/' . $language_code . '/' . $filename), 0, strlen(realpath('includes/languages/' . $language_code))) != realpath('includes/languages/' . $language_code) ) {
        return array();
      }

      if ( !file_exists('includes/languages/' . $language_code . '/' . $filename) ) {
        return array();
      }

      $contents = file('includes/languages/' . $language_code . '/' . $filename);
      
    }

    $ini_array = array();

    foreach ( $contents as $line ) {
      $line = trim($line);

      $firstchar = substr($line, 0, 1);

      if ( !empty($line) && ( $firstchar != $comment) ) {
        $delimiter = strpos($line, '=');

        if ( $delimiter !== false ) {
          $key = trim(substr($line, 0, $delimiter));
          $value = trim(substr($line, $delimiter + 1));

          $ini_array[$key] = $value;
        } elseif ( isset($key) ) {
          $ini_array[$key] .= trim($line);
        }
      }
    }

    unset($contents);

    $this->_definitions = array_merge($this->_definitions, $ini_array);
    
    // include the addons language defines
    if (isset($lC_Addons)) {    
      $aoArr = $lC_Addons->getAddons('enabled');
      if (is_array($aoArr)) {
        foreach ($aoArr as $ao => $aoData) {
          $file = DIR_FS_CATALOG . 'addons/' . $ao . '/languages/' . $language_code . '.xml';
          if (file_exists($file)) {
            $this->injectAddonDefinitions($file, $language_code);
          }
        }
      }    
    }  
  }

  public function injectDefinitions($file, $language_code = null) {
    if ( is_null($language_code) ) {
      $language_code = $this->_code;
    }

    if ( $this->_languages[$language_code]['parent_id'] > 0 ) {
      $this->injectDefinitions($file, $this->getCodeFromID($this->_languages[$language_code]['parent_id']));
    }

    foreach ($this->extractDefinitions($language_code . '/' . $file) as $def) {
      $this->_definitions[$def['key']] = $def['value'];
    }
  }

  public function &extractDefinitions($xml) {
    $definitions = array();

    if ( file_exists(dirname(__FILE__) . '/../../../includes/languages/' . $xml) ) {
      $lC_XML = new lC_XML(file_get_contents(dirname(__FILE__) . '/../../../includes/languages/' . $xml));

      $definitions = $lC_XML->toArray();

      if (isset($definitions['language']['definitions']['definition'][0]) === false) {
        $definitions['language']['definitions']['definition'] = array($definitions['language']['definitions']['definition']);
      }

      $definitions = $definitions['language']['definitions']['definition'];
    }

    return $definitions;
  }

  function getData($id, $key = null) {
    global $lC_Database;

    $Qlanguage = $lC_Database->query('select * from :table_languages where languages_id = :languages_id');
    $Qlanguage->bindTable(':table_languages', TABLE_LANGUAGES);
    $Qlanguage->bindInt(':languages_id', $id);
    $Qlanguage->execute();

    $result = $Qlanguage->toArray();

    $Qlanguage->freeResult();

    if ( empty($key) ) {
      return $result;
    } else {
      return $result[$key];
    }
  }

  function getID($code = null) {
    global $lC_Database;

    if ( empty($code) ) {
      return $this->_languages[$this->_code]['id'];
    }

    $Qlanguage = $lC_Database->query('select languages_id from :table_languages where code = :code');
    $Qlanguage->bindTable(':table_languages', TABLE_LANGUAGES);
    $Qlanguage->bindValue(':code', $code);
    $Qlanguage->execute();

    $result = $Qlanguage->toArray();

    $Qlanguage->freeResult();

    return $result['languages_id'];
  }

  function getCode($id = null) {
    global $lC_Database;

    if ( empty($id) ) {
      return $this->_code;
    }

    $Qlanguage = $lC_Database->query('select code from :table_languages where languages_id = :languages_id');
    $Qlanguage->bindTable(':table_languages', TABLE_LANGUAGES);
    $Qlanguage->bindValue(':languages_id', $id);
    $Qlanguage->execute();

    $result = $Qlanguage->toArray();

    $Qlanguage->freeResult();

    return $result['code'];
  }

  function showImage($code = null, $width = '16', $height = '10', $parameters = null) {
    if ( empty($code) ) {
      $code = $this->_code;
    }

    $image_code = strtolower(substr($code, 3));

    if ( !is_numeric($width) ) {
      $width = 16;
    }

    if ( !is_numeric($height) ) {
      $height = 10;
    }

    $name = ($this->_languages[$code]['charset'] == 'utf-8') ? utf8_encode($this->_languages[$code]['name']) : $this->_languages[$code]['name']; 
    return lc_image('../images/worldflags/' . $image_code . '.png', $name, $width, $height, $parameters);
  }

  function isDefined($key) {
    return isset($this->_definitions[$key]);
  } 
}
?>