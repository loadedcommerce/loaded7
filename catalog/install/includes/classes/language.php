<?php
/*
  $Id: language.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_LanguageInstall {

/* Private variables */
   var $_code,
       $_languages = array(),
       $_definitions = array();
  
/* Class constructor */

  public function lC_LanguageInstall() {
    $lC_DirectoryListing = new lC_DirectoryListing('../includes/languages');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('xml');

    foreach ($lC_DirectoryListing->getFiles() as $file) {
      $lC_XML = new lC_XML(file_get_contents('../includes/languages/' . $file['name']));
      $lang = $lC_XML->toArray();

      $this->_languages[$lang['language']['data']['code']] = array('name' => $lang['language']['data']['title'],
                                                                   'code' => $lang['language']['data']['code'],
                                                                   'charset' => $lang['language']['data']['character_set']);
    }

    unset($lang);

    $language = (isset($_GET['language']) && !empty($_GET['language']) ? $_GET['language'] : '');

    $this->set($language);

    $this->loadIniFile();
    $this->loadIniFile(basename($_SERVER['SCRIPT_FILENAME']));
  }
  
  public function getBrowserSetting() {
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      $browser_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

      $languages = array('ar' => 'ar([-_][[:alpha:]]{2})?|arabic',
                         'bg' => 'bg|bulgarian',
                         'br' => 'pt[-_]br|brazilian portuguese',
                         'ca' => 'ca|catalan',
                         'cs' => 'cs|czech',
                         'da' => 'da|danish',
                         'de' => 'de([-_][[:alpha:]]{2})?|german',
                         'el' => 'el|greek',
                         'en' => 'en([-_][[:alpha:]]{2})?|english',
                         'es' => 'es([-_][[:alpha:]]{2})?|spanish',
                         'et' => 'et|estonian',
                         'fi' => 'fi|finnish',
                         'fr' => 'fr([-_][[:alpha:]]{2})?|french',
                         'gl' => 'gl|galician',
                         'he' => 'he|hebrew',
                         'hu' => 'hu|hungarian',
                         'id' => 'id|indonesian',
                         'it' => 'it|italian',
                         'ja' => 'ja|japanese',
                         'ko' => 'ko|korean',
                         'ka' => 'ka|georgian',
                         'lt' => 'lt|lithuanian',
                         'lv' => 'lv|latvian',
                         'nl' => 'nl([-_][[:alpha:]]{2})?|dutch',
                         'no' => 'no|norwegian',
                         'pl' => 'pl|polish',
                         'pt' => 'pt([-_][[:alpha:]]{2})?|portuguese',
                         'ro' => 'ro|romanian',
                         'ru' => 'ru|russian',
                         'sk' => 'sk|slovak',
                         'sr' => 'sr|serbian',
                         'sv' => 'sv|swedish',
                         'th' => 'th|thai',
                         'tr' => 'tr|turkish',
                         'uk' => 'uk|ukrainian',
                         'tw' => 'zh[-_]tw|chinese traditional',
                         'zh' => 'zh|chinese simplified');

      foreach ($browser_languages as $browser_language) {
        foreach ($languages as $key => $value) {
          if (preg_match('/^(' . $value . ')(;q=[0-9]\\.[0-9])?$/i', $browser_language) && $this->exists($key)) {
            return $key;
          }
        }
      }
    }

    return false;
  }   
  
  public function loadIniFile($filename = null, $comment = '#', $language_code = null) {
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
  } 
  
  public function get($key) {
    if (isset($this->_definitions[$key])) {
      return $this->_definitions[$key];
    }

    return $key;
  }

  public function set($code = '') {
    $this->_code = $code;

    if (empty($this->_code)) {
      if (isset($_SESSION['language'])) {
        $this->_code = $_SESSION['language'];
      } elseif (isset($_COOKIE['language'])) {
        $this->_code = $_COOKIE['language'];
      } else {
        $this->_code = $this->getBrowserSetting();
      }
    }

    if (empty($this->_code) || ($this->exists($this->_code) === false)) {
      $this->_code = DEFAULT_LANGUAGE;
    }

    if (!isset($_COOKIE['language']) || (isset($_COOKIE['language']) && ($_COOKIE['language'] != $this->_code))) {
      lc_setcookie('language', $this->_code, time()+60*60*24*90);
    }

    if ((isset($_SESSION['language']) === false) || (isset($_SESSION['language']) && ($_SESSION['language'] != $this->_code))) {
      $_SESSION['language'] = $this->_code;
    }
  } 
  
  public function exists($code) {
    return array_key_exists($code, $this->_languages);
  }

  public function getAll() {
    return $this->_languages;
  }

  public function getData($key, $language = '') {
    if (empty($language)) {
      $language = $this->_code;
    }

    return $this->_languages[$language][$key];
  }

  public function getCodeFromID($id) {
    foreach ($this->_languages as $code => $lang) {
      if ($lang['id'] == $id) {
        return $code;
      }
    }
  }

  public function getID() {
    return $this->_languages[$this->_code]['id'];
  }

  public function getName() {
    return $this->_languages[$this->_code]['name'];
  }

  public function getCode() {
    return $this->_code;
  }

  public function getLocale() {
    return $this->_languages[$this->_code]['locale'];
  }

  public function getCharacterSet() {
    return $this->_languages[$this->_code]['charset'];
  }

  public function getDateFormatShort($with_time = false) {
    if ($with_time === true) {
      return $this->_languages[$this->_code]['date_format_short'] . ' ' . $this->getTimeFormat();
    }

    return $this->_languages[$this->_code]['date_format_short'];
  }

  public function getDateFormatLong() {
    return $this->_languages[$this->_code]['date_format_long'];
  }

  public function getTimeFormat() {
    return $this->_languages[$this->_code]['time_format'];
  }

  public function getTextDirection() {
    return $this->_languages[$this->_code]['text_direction'];
  }

  public function getCurrencyID() {
    return $this->_languages[$this->_code]['currencies_id'];
  }

  public function getNumericDecimalSeparator() {
    return $this->_languages[$this->_code]['numeric_separator_decimal'];
  }

  public function getNumericThousandsSeparator() {
    return $this->_languages[$this->_code]['numeric_separator_thousands'];
  }     

  public function showImage($code = null, $width = '16', $height = '10', $parameters = null) {
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

    return lc_image('../images/worldflags/' . $image_code . '.png', $this->_languages[$code]['name'], $width, $height, $parameters);
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
}
?>