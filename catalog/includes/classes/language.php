<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: language.php v1.0 2013-08-08 datazen $
*/
class lC_Language {

  /* Private variables */
  var $_code,
      $_languages = array(),
      $_definitions = array();

  /* Class constructor */
  public function lC_Language() {
    global $lC_Database;

    $Qlanguages = $lC_Database->query('select * from :table_languages order by sort_order, name');
    $Qlanguages->bindTable(':table_languages', TABLE_LANGUAGES);
    $Qlanguages->setCache('languages');
    $Qlanguages->execute();

    while ($Qlanguages->next()) {
      $this->_languages[$Qlanguages->value('code')] = array('id' => $Qlanguages->valueInt('languages_id'),
                                                            'code' => $Qlanguages->value('code'),
                                                            'name' => $Qlanguages->value('name'),
                                                            'locale' => $Qlanguages->value('locale'),
                                                            'charset' => $Qlanguages->value('charset'),
                                                            'date_format_short' => $Qlanguages->value('date_format_short'),
                                                            'date_format_long' => $Qlanguages->value('date_format_long'),
                                                            'time_format' => $Qlanguages->value('time_format'),
                                                            'text_direction' => $Qlanguages->value('text_direction'),
                                                            'currencies_id' => $Qlanguages->valueInt('currencies_id'),
                                                            'numeric_separator_decimal' => $Qlanguages->value('numeric_separator_decimal'),
                                                            'numeric_separator_thousands' => $Qlanguages->value('numeric_separator_thousands'),
                                                            'parent_id' => $Qlanguages->valueInt('parent_id'));
    }

    $Qlanguages->freeResult();

    $this->set();
  }

  /* Public methods */
  public function load($key, $language_code = null) {
    global $lC_Database, $lC_Addons;

    if ( is_null($language_code) ) {
      $language_code = $this->_code;
    }

    if ( $this->_languages[$language_code]['parent_id'] > 0 ) {
      $this->load($key, $this->getCodeFromID($this->_languages[$language_code]['parent_id']));
    }

    $Qdef = $lC_Database->query('select * from :table_languages_definitions where languages_id = :languages_id and content_group = :content_group');
    $Qdef->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
    $Qdef->bindInt(':languages_id', $this->getData('id', $language_code));
    $Qdef->bindValue(':content_group', $key);
    $Qdef->setCache('languages-' . $language_code . '-' . $key);
    $Qdef->execute();

    while ($Qdef->next()) {
      $this->_definitions[$Qdef->value('definition_key')] = utf8_decode($Qdef->value('definition_value'));
    }

    $Qdef->freeResult();
    
    // inject the addons language defines
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
  
  public function injectAddonDefinitions($file, $language_code = null) {   
    if ( is_null($language_code) ) {
      $language_code = $this->_code;
    }

    if ( $this->_languages[$language_code]['parent_id'] > 0 ) {
      $this->injectAddonDefinitions($file, $this->getCodeFromID($this->_languages[$language_code]['parent_id']));
    }

    foreach ($this->extractAddonDefinitions($file) as $def) {
      $this->_definitions[$def['key']] = $def['value'];
    }                        
  }    
  
  public function &extractAddonDefinitions($xml) {
    $definitions = array();
    if ( file_exists($xml) ) {
      $lC_XML = new lC_XML(file_get_contents($xml));

      $definitions = $lC_XML->toArray();

      if (isset($definitions['language']['definitions']['definition'][0]) === false) {
        $definitions['language']['definitions']['definition'] = array($definitions['language']['definitions']['definition']);
      }

      $definitions = $definitions['language']['definitions']['definition'];
    }

    return $definitions;
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

    return lc_image('images/worldflags/' . $image_code . '.png', $this->_languages[$code]['name'], $width, $height, $parameters);
  }
}
?>