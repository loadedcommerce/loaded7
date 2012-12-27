<?php
/*
  $Id: languages.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Languages_Admin class manages languages
*/

class lC_Languages_Admin {
 /*
  * Returns the languages datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $Qlanguages = $lC_Database->query('select SQL_CALC_FOUND_ROWS * from :table_languages order by sort_order, name');
    $Qlanguages->bindTable(':table_languages', TABLE_LANGUAGES);
    $Qlanguages->execute();

    $result = array('aaData' => array());
    while ( $Qlanguages->next() ) {
      $Qdef = $lC_Database->query('select count(*) as total_definitions from :table_languages_definitions where languages_id = :languages_id');
      $Qdef->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
      $Qdef->bindInt(':languages_id', $Qlanguages->valueInt('languages_id'));
      $Qdef->execute();

      $default = ($Qlanguages->value('code') == DEFAULT_LANGUAGE) ? '<small class="tag purple-gradient glossy small-margin-left">' . $lC_Language->get('default') . '</small>' : '';
      $name = ($Qlanguages->value('charset') == 'utf-8') ? utf8_encode($Qlanguages->valueProtected('name')) : $Qlanguages->valueProtected('name');
      $lang = '<td><a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'languages=' . $Qlanguages->valueInt('languages_id')) . '"><span class="icon-folder icon-orange"></span>&nbsp;' . $name . ' ' . $default . '</a></td>';
      $code = '<td>' . $lC_Language->showImage($Qlanguages->value('code')) . ' ' . $Qlanguages->value('code') . '</td>';
      $total = '<td>' . $Qdef->valueInt('total_definitions') . '</td>';

      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? '#' : 'javascript://" onclick="editLanguage(\'' . $Qlanguages->valueInt('languages_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="exportLanguage(\'' . $Qlanguages->valueInt('languages_id') . '\', \'' . urlencode($Qlanguages->valueProtected('title')) . '\')') . '" class="button icon-outbox with-tooltip' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_export') . '"></a>
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="deleteLanguage(\'' . $Qlanguages->valueInt('languages_id') . '\', \'' . urlencode($Qlanguages->valueProtected('title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$lang", "$code", "$total", "$action");
      $result['entries'][] = array_merge($Qlanguages->toArray(), $Qdef->toArray());
    }

    $Qlanguages->freeResult();

    return $result;
  }
 /*
  * Returns the languages export data
  *
  * @param integer $id The language id
  * @access public
  * @return array
  */
  public static function getExportData($id) {

    $lC_ObjectInfo = new lC_ObjectInfo(lC_Languages_Admin::get($id));

    $result = array();
    $groups_array = array();
    foreach ( lc_toObjectInfo(lC_Languages_Admin::getDefinitionGroups($lC_ObjectInfo->getInt('languages_id')))->get('entries') as $group ) {
      $groups_array[$group['content_group']] = $group['content_group'];
    }
    $result['groupsArray'] = $groups_array;

    return $result;
  }
 /*
  * Returns the languages import data
  *
  * @access public
  * @return array
  */
  public static function getImportData() {
    $result = array();
    $languages_array = array();
    foreach ( lC_Languages_Admin::getDirectoryListing() as $directory ) {
      $languages_array[$directory] = $directory;
    }
    $result['languagesArray'] = $languages_array;

    return $result;
  }
 /*
  * Return the language information
  *
  * @param integer $id The language id
  * @access public
  * @return array
  */
  public static function getData($id = null) {
    global $lC_Language, $lC_Currencies;

    $result = array();
    $languages_array = array('0' => $lC_Language->get('none'));
    foreach ( lc_toObjectInfo(lC_Languages_Admin::getAll(-1))->get('entries') as $l ) {
      if ( $l['languages_id'] != $id ) {
        $languages_array[$l['languages_id']] = $l['name'] . ' (' . $l['code'] . ')';
      }
    }
    $result['languagesArray'] = $languages_array;

    $currencies_array = array();
    foreach ( lc_toObjectInfo(lC_Currencies_Admin::getAll(-1))->get('entries') as $c ) {
      $currencies_array[$c['currencies_id']] = $c['title'];
    }
    $result['currenciesArray'] = $currencies_array;

    if (isset($id) && $id != null) {
      $result['languageData'] = lC_Languages_Admin::get($id);
    }

    return $result;
  }
 /*
  * Return the language definition
  *
  * @param integer $id The language id
  * @param integer $key The language definition id
  * @access public
  * @return array
  */

  public static function get($id, $key = null) {
    global $lC_Database;

    $result = false;

    $Qlanguage = $lC_Database->query('select * from :table_languages where');

    if ( is_numeric($id) ) {
      $Qlanguage->appendQuery('languages_id = :languages_id');
      $Qlanguage->bindInt(':languages_id', $id);
    } else {
      $Qlanguage->appendQuery('code = :code');
      $Qlanguage->bindValue(':code', $id);
    }

    $Qlanguage->bindTable(':table_languages', TABLE_LANGUAGES);
    $Qlanguage->execute();

    if ( $Qlanguage->numberOfRows() === 1 ) {
      $Qdef = $lC_Database->query('select count(*) as total_definitions from :table_languages_definitions where languages_id = :languages_id');
      $Qdef->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
      $Qdef->bindInt(':languages_id', $Qlanguage->valueInt('languages_id'));
      $Qdef->execute();

      $result = array_merge($Qlanguage->toArray(), $Qdef->toArray());
      if ($Qlanguage->value('charset') == 'utf-8') $result['name'] = utf8_encode($result['name']);

      if ( !empty($key) && isset($result[$key]) ) {
        $result = $result[$key];
      }
    }

    return $result;
  }
 /*
  * Check to see if language id exists
*   *
  * @param integer $id The language id
  * @access public
  * @return boolean
  */
  public static function exists($id) {
    return ( self::get($id) !== false );
  }
 /*
  * Return the language definitions for a group
  *
  * @param integer $id The language definition group id
  * @access public
  * @return array
  */
  public static function getDefinitionGroup($group) {
    global $lC_Database;

    $result = array('entries' => array());

    $Qgroup = $lC_Database->query('select languages_id, count(*) as total_entries from :table_languages_definitions where content_group = :content_group group by languages_id');
    $Qgroup->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
    $Qgroup->bindValue(':content_group', $group);
    $Qgroup->execute();

    while ( $Qgroup->next() ) {
      $result['entries'][] = $Qgroup->toArray();
    }

    $result['total'] = $Qgroup->numberOfRows();

    $Qgroup->freeResult();

    return $result;
  }
  /*
  * Return the language definition groups
  *
  * @param integer $language_id The language id
  * @access public
  * @return array
  */
  public static function getDefinitionGroups($language_id) {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $Qgroups = $lC_Database->query('select distinct content_group, count(*) as total_entries from :table_languages_definitions where languages_id = :languages_id group by content_group order by content_group');
    $Qgroups->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
    $Qgroups->bindInt(':languages_id', $language_id);
    $Qgroups->execute();

    $result = array('aaData' => array());
    while ( $Qgroups->next() ) {
      $group = '<td><a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'languages=' . $language_id . '&group=' . $Qgroups->value('content_group')) . '"><span class="icon-folder icon-orange"></span>&nbsp;' . $Qgroups->value('content_group') . '</a></td>';
      $total = '<td>' . $Qgroups->valueInt('total_entries') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact" style="white-space:nowrap;">
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="deleteGroup(\'' . $Qgroups->value('content_group') . '\', \'' . urlencode($Qgroups->value('content_group')) . '\')') . '" class="button icon-trash' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_delete')) . '</a>
                 </span></td>';

      $result['aaData'][] = array("$group", "$total", "$action");
      $result['entries'][] = $Qgroups->toArray();
    }

    $Qgroups->freeResult();

    return $result;
  }
 /*
  * Return the language definition group data
  *
  * @param integer $id The language definition group id
  * @access public
  * @return array
  */
  public static function getDefinitionGroupsData($id) {
    global $lC_Language;

    $lC_ObjectInfo = new lC_ObjectInfo(lC_Languages_Admin::getDefinitionGroup($id));

    $result = array();
    $result['languageEntries'] = '';
    foreach ( $lC_ObjectInfo->get('entries') as $l ) {
      $result['languageEntries'] = lC_Languages_Admin::get($l['languages_id'], 'name') . ': <b>' . $id . ': ' . (int)$l['total_entries'] . '</b> ' . $lC_Language->get('definitions') . '<br />';
    }

    return $result;
  }
 /*
  * Check to see if language definition group exists
  *
  * @param integer $language_id The language id
  * @param integer $group The language definition group id
  * @access public
  * @return boolean
  */
  public static function isDefinitionGroup($language_id, $group) {
    global $lC_Database;

    $Qgroup = $lC_Database->query('select id from :table_languages_definitions where languages_id = :languages_id and content_group = :content_group limit 1');
    $Qgroup->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
    $Qgroup->bindInt(':languages_id', $language_id);
    $Qgroup->bindValue(':content_group', $group);
    $Qgroup->execute();

    $result = false;

    if ( $Qgroup->numberOfRows() === 1 ) {
      $result = true;
    }

    $Qgroup->freeResult();

    return $result;
  }
 /*
  * Delete the language definition group
  *
  * @param integer $group The language definition group id
  * @access public
  * @return boolean
  */
  public static function deleteDefinitionGroup($group) {
    global $lC_Database;

    $Qdel = $lC_Database->query('delete from :table_languages_definitions where content_group = :content_group');
    $Qdel->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
    $Qdel->bindValue(':content_group', $group);
    $Qdel->setLogging($_SESSION['module']);
    $Qdel->execute();

    if ( !$lC_Database->isError() ) {
      lC_Cache::clear('languages');

      return true;
    }

    return false;
  }
 /*
  * Get the language definition data used on the dialog forms
  *
  * @param integer $id The language definition id
  * @access public
  * @return array
  */
  public static function getDefinitionsFormData($id = null) {
    global $lC_Language;

    $result = array();
    $result['definitionValue'] = '';
    foreach ( $lC_Language->getAll() as $l ) {
      $result['definitionValue'] .= '<span class="input" style="width:90%"><label for="value[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_textarea_field('value[' . $l['id'] . ']', null, 38, 5, 'class="input-unstyled"') . '</span>';
    }

    $groups_array = array();
    foreach ( lc_toObjectInfo(lC_Languages_Admin::getDefinitionGroups($_GET['languages']))->get('entries') as $value ) {
      $groups_array[] = array('id' => $value['content_group'],
                              'text' => $value['content_group']);
    }
    $result['groupsSelection'] = '';
    if ( !empty($groups_array) ) {
      $result['groupsSelection'] = lc_draw_pull_down_menu('group', $groups_array, null, 'class="input with-small-padding"');
    }

    if (isset($id) && $id != null) {
      $result['definitionData'] = lC_Languages_Admin::getDefinition($id);
      $result['editDefinitionHtml'] = '<label for="def[' . $result['definitionData']['definition_key'] . ']"><strong>' . $result['definitionData']['definition_key'] . '</strong></label>' . lc_draw_textarea_field('def[' . $result['definitionData']['definition_key'] . ']', $result['definitionData']['definition_value'], 38, 5, 'class="input full-width"');
    }

    return $result;
  }
 /*
  * Get the language definition information
  *
  * @param integer $id The language definition id
  * @access public
  * @return array
  */
  public static function getDefinition($id) {
    global $lC_Database;

    $Qdef = $lC_Database->query('select * from :table_languages_definitions where id = :id');
    $Qdef->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
    $Qdef->bindInt(':id', $id);
    $Qdef->execute();

    $result = $Qdef->toArray();

    $Qdef->freeResult();

    return $result;
  }
 /*
  * Get the language definitions for a group
  *
  * @param integer $language_id The language id
  * @param string $group The language definition group id
  * @access public
  * @return array
  */
  public static function getDefinitions($language_id, $group) {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $Qdefs = $lC_Database->query('select * from :table_languages_definitions where languages_id = :languages_id and');

    if ( is_array($group) ) {
      $Qdefs->appendQuery('content_group in :content_group');
      $Qdefs->bindRaw(':content_group', '("' . implode('", "', $group) . '")');
    } else {
      $Qdefs->appendQuery('content_group = :content_group');
      $Qdefs->bindValue(':content_group', $group);
    }

    $Qdefs->appendQuery('order by content_group, definition_key');
    $Qdefs->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
    $Qdefs->bindInt(':languages_id', $language_id);
    $Qdefs->execute();

    $result = array('aaData' => array());
    while ( $Qdefs->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qdefs->valueInt('id') . '" id="' . $Qdefs->valueInt('id') . '"></td>';
      $key = '<td>' . $Qdefs->value('definition_key') . '</td>';
      $value = '<td>' . $Qdefs->value('definition_value') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? '#' : 'javascript://" onclick="editDefinition(\'' . $Qdefs->valueInt('id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="deleteDefinition(\'' . $Qdefs->valueInt('id') . '\', \'' . urlencode($Qdefs->valueProtected('definition_value')) . '\')') . '" class="button icon-trash' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$key", "$value", "$action");
      $result['entries'][] = $Qdefs->toArray();
    }

    $Qdefs->freeResult();

    return $result;
  }
 /*
  * Insert a language definition
  *
  * @param string $group The language definition group id
  * @param array $data The language definition data
  * @access public
  * @return boolean
  */
  public static function insertDefinition($group, $data) {
    global $lC_Database, $lC_Language;

    $error = false;

    if (isset($data['group_new']) && !empty($data['group_new'])) $group = $data['group_new'];

    $lC_Database->startTransaction();

    foreach ( lc_toObjectInfo(self::getAll(-1))->get('entries') as $l) {
      $Qdef = $lC_Database->query('insert into :table_languages_definitions (languages_id, content_group, definition_key, definition_value) values (:languages_id, :content_group, :definition_key, :definition_value)');
      $Qdef->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
      $Qdef->bindInt(':languages_id', $l['languages_id']);
      $Qdef->bindValue(':content_group', $group);
      $Qdef->bindValue(':definition_key', $data['key']);
      $Qdef->bindValue(':definition_value', $data['value'][$l['languages_id']]);
      $Qdef->setLogging($_SESSION['module']);
      $Qdef->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }

      lC_Cache::clear('languages-' . $l['code'] . '-' . $group);
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Update a language definition
  *
  * @param integer $language_id The language id
  * @param string $group The language definition group id
  * @param array $data The language definition data
  * @access public
  * @return boolean
  */
  public static function updateDefinitions($language_id, $group, $data) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    foreach ( $data['def'] as $key => $value ) {
      $Qupdate = $lC_Database->query('update :table_languages_definitions set definition_value = :definition_value where definition_key = :definition_key and languages_id = :languages_id and content_group = :content_group');
      $Qupdate->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
      $Qupdate->bindValue(':definition_value', $value);
      $Qupdate->bindValue(':definition_key', $key);
      $Qupdate->bindInt(':languages_id', $language_id);
      $Qupdate->bindValue(':content_group', $group);
      $Qupdate->setLogging($_SESSION['module'], $language_id);
      $Qupdate->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('languages-' . self::get($language_id, 'code') . '-' . $group);

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Delete a language definition
  *
  * @param integer $language_id The language id
  * @param string $group The language definition group id
  * @param array $keys The language definition keys
  * @access public
  * @return boolean
  */
  public static function deleteDefinitions($language_id, $group, $keys) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    foreach ( $keys as $id ) {
      $Qdel = $lC_Database->query('delete from :table_languages_definitions where id = :id');
      $Qdel->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
      $Qdel->bindValue(':id', $id);
      $Qdel->setLogging($_SESSION['module'], $id);
      $Qdel->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('languages-' . self::get($language_id, 'code') . '-' . $group);

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Update a language
  *
  * @param integer $id The language id
  * @param array $data The language data
  * @param array $default Set the language to be default
  * @access public
  * @return boolean
  */
  public static function update($id, $data, $default = false) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    $name = ($data['charset'] == 'utf-8') ? lc_output_utf8_decoded($data['name']) : $data['name'];

    $Qlanguage = $lC_Database->query('update :table_languages set name = :name, code = :code, locale = :locale, charset = :charset, date_format_short = :date_format_short, date_format_long = :date_format_long, time_format = :time_format, text_direction = :text_direction, currencies_id = :currencies_id, numeric_separator_decimal = :numeric_separator_decimal, numeric_separator_thousands = :numeric_separator_thousands, parent_id = :parent_id, sort_order = :sort_order where languages_id = :languages_id');
    $Qlanguage->bindTable(':table_languages', TABLE_LANGUAGES);
    $Qlanguage->bindValue(':name', $name);
    $Qlanguage->bindValue(':code', $data['code']);
    $Qlanguage->bindValue(':locale', $data['locale']);
    $Qlanguage->bindValue(':charset', $data['charset']);
    $Qlanguage->bindValue(':date_format_short', $data['date_format_short']);
    $Qlanguage->bindValue(':date_format_long', $data['date_format_long']);
    $Qlanguage->bindValue(':time_format', $data['time_format']);
    $Qlanguage->bindValue(':text_direction', $data['text_direction']);
    $Qlanguage->bindInt(':currencies_id', $data['currencies_id']);
    $Qlanguage->bindValue(':numeric_separator_decimal', $data['numeric_separator_decimal']);
    $Qlanguage->bindValue(':numeric_separator_thousands', $data['numeric_separator_thousands']);
    $Qlanguage->bindInt(':parent_id', $data['parent_id']);
    $Qlanguage->bindInt(':sort_order', $data['sort_order']);
    $Qlanguage->bindInt(':languages_id', $id);
    $Qlanguage->setLogging($_SESSION['module'], $id);
    $Qlanguage->execute();

    if ( $lC_Database->isError() ) {
      $error = true;
    }

    if ( $error === false ) {
      if ( $default === true ) {
        $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindValue(':configuration_value', $data['code']);
        $Qupdate->bindValue(':configuration_key', 'DEFAULT_LANGUAGE');
        $Qupdate->setLogging($_SESSION['module'], $id);
        $Qupdate->execute();

        if ( $lC_Database->isError() === false ) {
          if ( $Qupdate->affectedRows() ) {
            lC_Cache::clear('configuration');
          }
        } else {
          $error = true;
        }
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('languages');

      return true;
    } else {
      $lC_Database->rollbackTransaction();
    }

    return false;
  }
 /*
  * Delete a language
  *
  * @param integer $id The language id
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    if ( self::get($id, 'code') != DEFAULT_LANGUAGE ) {
      $error = false;

      $lC_Database->startTransaction();

      $Qcategories = $lC_Database->query('delete from :table_categories_description where language_id = :language_id');
      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qcategories->bindInt(':language_id', $id);
      $Qcategories->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }

      if ( $error === false ) {
        $Qproducts = $lC_Database->query('delete from :table_products_description where language_id = :language_id');
        $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qproducts->bindInt(':language_id', $id);
        $Qproducts->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qproducts = $lC_Database->query('delete from :table_product_attributes where languages_id = :languages_id');
        $Qproducts->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
        $Qproducts->bindInt(':languages_id', $id);
        $Qproducts->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qproducts = $lC_Database->query('delete from :table_products_variants_groups where languages_id = :languages_id');
        $Qproducts->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
        $Qproducts->bindInt(':languages_id', $id);
        $Qproducts->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qproducts = $lC_Database->query('delete from :table_products_variants_values where languages_id = :languages_id');
        $Qproducts->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
        $Qproducts->bindInt(':languages_id', $id);
        $Qproducts->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qmanufacturers = $lC_Database->query('delete from :table_manufacturers_info where languages_id = :languages_id');
        $Qmanufacturers->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
        $Qmanufacturers->bindInt(':languages_id', $id);
        $Qmanufacturers->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qmanufacturers = $lC_Database->query('delete from :table_reviews where languages_id = :languages_id');
        $Qmanufacturers->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qmanufacturers->bindInt(':languages_id', $id);
        $Qmanufacturers->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qstatus = $lC_Database->query('delete from :table_orders_status where language_id = :language_id');
        $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
        $Qstatus->bindInt(':language_id', $id);
        $Qstatus->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qgroup = $lC_Database->query('delete from :table_products_images_groups where language_id = :language_id');
        $Qgroup->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
        $Qgroup->bindInt(':language_id', $id);
        $Qgroup->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qgroup = $lC_Database->query('delete from :table_shipping_availability where languages_id = :languages_id');
        $Qgroup->bindTable(':table_shipping_availability', TABLE_SHIPPING_AVAILABILITY);
        $Qgroup->bindInt(':languages_id', $id);
        $Qgroup->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qgroup = $lC_Database->query('delete from :table_weight_classes where language_id = :language_id');
        $Qgroup->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
        $Qgroup->bindInt(':language_id', $id);
        $Qgroup->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qlanguages = $lC_Database->query('delete from :table_languages where languages_id = :languages_id');
        $Qlanguages->bindTable(':table_languages', TABLE_LANGUAGES);
        $Qlanguages->bindInt(':languages_id', $id);
        $Qlanguages->setLogging($_SESSION['module'], $id);
        $Qlanguages->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qdefinitions = $lC_Database->query('delete from :table_languages_definitions where languages_id = :languages_id');
        $Qdefinitions->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
        $Qdefinitions->bindInt(':languages_id', $id);
        $Qdefinitions->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $lC_Database->commitTransaction();

        lC_Cache::clear('languages');

        return true;
      } else {
        $lC_Database->rollbackTransaction();
      }
    }

    return false;
  }
 /*
  * Batch delete languages
  *
  * @param array $batch The language id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    global $lC_Language, $lC_Currencies;

    $lC_Language->loadIniFile('languages.php');

    $result = array();
    foreach ( $batch as $id ) {
      $data = lC_Languages_Admin::get($id);
      if ( $data['code'] == DEFAULT_LANGUAGE ) {
        $result['namesString'] = $lC_Language->get('introduction_delete_language_invalid');
      } else {
        lC_Languages_Admin::delete($id);
      }
    }
    return $result;
  }
 /*
  * Export a language
  *
  * @param integer $id The language id
  * @param string $groups The language definition groups
  * @param boolean $include_language_data Include the language data in the export
  * @access public
  * @return file download
  */
  public static function export($id, $groups, $include_language_data = true) {
    global $lC_Database;

    $language = self::get($id);

    $export_array = array();

    if ( $include_language_data === true ) {
      $export_array['language']['data'] = array('title-CDATA' => $language['name'],
                                                'code-CDATA' => $language['code'],
                                                'locale-CDATA' => $language['locale'],
                                                'character_set-CDATA' => $language['charset'],
                                                'text_direction-CDATA' => $language['text_direction'],
                                                'date_format_short-CDATA' => $language['date_format_short'],
                                                'date_format_long-CDATA' => $language['date_format_long'],
                                                'time_format-CDATA' => $language['time_format'],
                                                'default_currency-CDATA' => lC_Currencies_Admin::getData($language['currencies_id'], 'code'),
                                                'numerical_decimal_separator-CDATA' => $language['numeric_separator_decimal'],
                                                'numerical_thousands_separator-CDATA' => $language['numeric_separator_thousands']);

      if ( $language['parent_id'] > 0 ) {
        $export_array['language']['data']['parent_language_code'] = lC_Languages_Admin::get($language['parent_id'], 'code');
      }
    }

    foreach ( lc_toObjectInfo(self::getDefinitions($id, $groups))->get('entries') as $def ) {
      $export_array['language']['definitions']['definition'][] = array('key' => $def['definition_key'],
                                                                       'value-CDATA' => $def['definition_value'],
                                                                       'group' => $def['content_group']);
    }

    $lC_XML = new lC_XML($export_array, $language['charset']);
    $xml = $lC_XML->toXML();

    header('Content-disposition: attachment; filename=' . $language['code'] . '.xml');
    header('Content-Type: application/force-download');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . strlen($xml));
    header('Pragma: no-cache');
    header('Expires: 0');

    echo $xml;

    exit;
  }
 /*
  * Get a directory listing of the languages directory
  *
  * @access public
  * @return array
  */
  public static function getDirectoryListing() {
    global $lC_Currencies;

    $result = array();

    $lC_DirectoryListing = new lC_DirectoryListing('../includes/languages');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('xml');

    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      $result[] = substr($file['name'], 0, strrpos($file['name'], '.'));
    }

    return $result;
  }
 /*
  * Import a language file
  *
  * @param string $file The language file name
  * @param integer $type The type of import; add, insert, update
  * @access public
  * @return boolean
  */
  public static function import($file, $type) {
    global $lC_Database, $lC_Currencies;

    if ( file_exists('../includes/languages/' . $file . '.xml') ) {
      $lC_XML = new lC_XML(file_get_contents('../includes/languages/' . $file . '.xml'));
      $source = $lC_XML->toArray();

      $language = array('name' => $source['language']['data']['title'],
                        'code' => $source['language']['data']['code'],
                        'locale' => $source['language']['data']['locale'],
                        'charset' => $source['language']['data']['character_set'],
                        'date_format_short' => $source['language']['data']['date_format_short'],
                        'date_format_long' => $source['language']['data']['date_format_long'],
                        'time_format' => $source['language']['data']['time_format'],
                        'text_direction' => $source['language']['data']['text_direction'],
                        'currency' => $source['language']['data']['default_currency'],
                        'numeric_separator_decimal' => $source['language']['data']['numerical_decimal_separator'],
                        'numeric_separator_thousands' => $source['language']['data']['numerical_thousands_separator'],
                        'parent_language_code' => (isset($source['language']['data']['parent_language_code']) ? $source['language']['data']['parent_language_code'] : ''),
                        'parent_id' => 0);

      if ( lC_Currencies_Admin::exists($language['currency']) === false ) {
        $language['currency'] = DEFAULT_CURRENCY;
      }

      if ( !empty($language['parent_language_code']) && self::exists($language['parent_language_code']) ) {
        $language['parent_id'] = self::get($language['parent_language_code'], 'languages_id');
      }

      $definitions = array();

      if ( isset($source['language']['definitions']['definition']) ) {
        $definitions = $source['language']['definitions']['definition'];

        if ( isset($definitions['key']) && isset($definitions['value']) && isset($definitions['group']) ) {
          $definitions = array(array('key' => $definitions['key'],
                                     'value' => $definitions['value'],
                                     'group' => $definitions['group']));
        }
      }

      unset($source);

      $error = false;
      $add_category_and_product_placeholders = true;

      $lC_Database->startTransaction();

      $language_id = self::get($language['code'], 'languages_id');

      if ( $language_id !== false ) {
        $add_category_and_product_placeholders = false;

        $Qlanguage = $lC_Database->query('update :table_languages set name = :name, code = :code, locale = :locale, charset = :charset, date_format_short = :date_format_short, date_format_long = :date_format_long, time_format = :time_format, text_direction = :text_direction, currencies_id = :currencies_id, numeric_separator_decimal = :numeric_separator_decimal, numeric_separator_thousands = :numeric_separator_thousands, parent_id = :parent_id where languages_id = :languages_id');
        $Qlanguage->bindInt(':languages_id', $language_id);
      } else {
        $Qlanguage = $lC_Database->query('insert into :table_languages (name, code, locale, charset, date_format_short, date_format_long, time_format, text_direction, currencies_id, numeric_separator_decimal, numeric_separator_thousands, parent_id) values (:name, :code, :locale, :charset, :date_format_short, :date_format_long, :time_format, :text_direction, :currencies_id, :numeric_separator_decimal, :numeric_separator_thousands, :parent_id)');
      }

      $Qlanguage->bindTable(':table_languages', TABLE_LANGUAGES);
      $Qlanguage->bindValue(':name', $language['name']);
      $Qlanguage->bindValue(':code', $language['code']);
      $Qlanguage->bindValue(':locale', $language['locale']);
      $Qlanguage->bindValue(':charset', $language['charset']);
      $Qlanguage->bindValue(':date_format_short', $language['date_format_short']);
      $Qlanguage->bindValue(':date_format_long', $language['date_format_long']);
      $Qlanguage->bindValue(':time_format', $language['time_format']);
      $Qlanguage->bindValue(':text_direction', $language['text_direction']);
      $Qlanguage->bindInt(':currencies_id', lC_Currencies_Admin::getData($language['currency'], 'currencies_id'));
      $Qlanguage->bindValue(':numeric_separator_decimal', $language['numeric_separator_decimal']);
      $Qlanguage->bindValue(':numeric_separator_thousands', $language['numeric_separator_thousands']);
      $Qlanguage->bindInt(':parent_id', $language['parent_id']);
      $Qlanguage->setLogging($_SESSION['module'], ($language_id !== false ? $language_id : null));
      $Qlanguage->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      } else {
        if ( $language_id === false ) {
          $language_id = $lC_Database->nextID();
        }

        $default_language_id = lC_Languages_Admin::get(DEFAULT_LANGUAGE, 'languages_id');

        if ( $type == 'replace' ) {
          $Qdel =  $lC_Database->query('delete from :table_languages_definitions where languages_id = :languages_id');
          $Qdel->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
          $Qdel->bindInt(':languages_id', $language_id);
          $Qdel->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }
      }

      if ( $error === false ) {
        $lC_DirectoryListing = new lC_DirectoryListing('../includes/languages/' . $file);
        $lC_DirectoryListing->setRecursive(true);
        $lC_DirectoryListing->setIncludeDirectories(false);
        $lC_DirectoryListing->setAddDirectoryToFilename(true);
        $lC_DirectoryListing->setCheckExtension('xml');

        foreach ( $lC_DirectoryListing->getFiles() as $files ) {
          $definitions = array_merge($definitions, lC_Language_Admin::extractDefinitions($file . '/' . $files['name']));
        }

        foreach ( $definitions as $def ) {
          $insert = false;
          $update = false;

          if ( $type == 'replace' ) {
            $insert = true;
          } else {
            $Qcheck = $lC_Database->query('select definition_key, content_group from :table_languages_definitions where definition_key = :definition_key and languages_id = :languages_id and content_group = :content_group');
            $Qcheck->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
            $Qcheck->bindValue(':definition_key', $def['key']);
            $Qcheck->bindInt(':languages_id', $language_id);
            $Qcheck->bindValue(':content_group', $def['group']);
            $Qcheck->execute();

            if ( $Qcheck->numberOfRows() > 0 ) {
              if ( $type == 'update' ) {
                $update = true;
              }
            } elseif ( $type == 'add' ) {
              $insert = true;
            }
          }

          if ( ($insert === true) || ($update === true) ) {
            if ( $insert === true ) {
              $Qdef = $lC_Database->query('insert into :table_languages_definitions (languages_id, content_group, definition_key, definition_value) values (:languages_id, :content_group, :definition_key, :definition_value)');
            } else {
              $Qdef = $lC_Database->query('update :table_languages_definitions set content_group = :content_group, definition_key = :definition_key, definition_value = :definition_value where definition_key = :definition_key and languages_id = :languages_id and content_group = :content_group');
              $Qdef->bindValue(':definition_key', $def['key']);
              $Qdef->bindValue(':content_group', $def['group']);
            }
            $Qdef->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
            $Qdef->bindInt(':languages_id', $language_id);
            $Qdef->bindValue(':content_group', $def['group']);
            $Qdef->bindValue(':definition_key', $def['key']);
            $Qdef->bindValue(':definition_value', $def['value']);
            $Qdef->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }
      }

      if ( $add_category_and_product_placeholders === true ) {
        if ( $error === false ) {
          $Qcategories = $lC_Database->query('select categories_id, categories_name from :table_categories_description where language_id = :language_id');
          $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qcategories->bindInt(':language_id', $default_language_id);
          $Qcategories->execute();

          while ( $Qcategories->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_categories_description (categories_id, language_id, categories_name) values (:categories_id, :language_id, :categories_name)');
            $Qinsert->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
            $Qinsert->bindInt(':categories_id', $Qcategories->valueInt('categories_id'));
            $Qinsert->bindInt(':language_id', $language_id);
            $Qinsert->bindValue(':categories_name', $Qcategories->value('categories_name'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qproducts = $lC_Database->query('select products_id, products_name, products_description, products_keyword, products_tags, products_url from :table_products_description where language_id = :language_id');
          $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
          $Qproducts->bindInt(':language_id', $default_language_id);
          $Qproducts->execute();

          while ( $Qproducts->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_keyword, products_tags, products_url) values (:products_id, :language_id, :products_name, :products_description, :products_keyword, :products_tags, :products_url)');
            $Qinsert->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
            $Qinsert->bindInt(':products_id', $Qproducts->valueInt('products_id'));
            $Qinsert->bindInt(':language_id', $language_id);
            $Qinsert->bindValue(':products_name', $Qproducts->value('products_name'));
            $Qinsert->bindValue(':products_description', $Qproducts->value('products_description'));
            $Qinsert->bindValue(':products_keyword', $Qproducts->value('products_keyword'));
            $Qinsert->bindValue(':products_tags', $Qproducts->value('products_tags'));
            $Qinsert->bindValue(':products_url', $Qproducts->value('products_url'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qattributes = $lC_Database->query('select products_id, value from :table_product_attributes where languages_id = :languages_id');
          $Qattributes->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
          $Qattributes->bindInt(':languages_id', $default_language_id);
          $Qattributes->execute();

          while ( $Qattributes->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_product_attributes (products_id, languages_id, value) values (:products_id, :languages_id, :value)');
            $Qinsert->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
            $Qinsert->bindInt(':products_id', $Qattributes->valueInt('products_id'));
            $Qinsert->bindInt(':languages_id', $language_id);
            $Qinsert->bindValue(':value', $Qattributes->value('value'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qgroups = $lC_Database->query('select id, title, sort_order, module from :table_products_variants_groups where languages_id = :languages_id');
          $Qgroups->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
          $Qgroups->bindInt(':languages_id', $default_language_id);
          $Qgroups->execute();

          while ( $Qgroups->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_products_variants_groups (id, languages_id, title, sort_order, module) values (:id, :languages_id, :title, :sort_order, :module)');
            $Qinsert->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
            $Qinsert->bindInt(':id', $Qgroups->valueInt('id'));
            $Qinsert->bindInt(':languages_id', $language_id);
            $Qinsert->bindValue(':title', $Qgroups->value('title'));
            $Qinsert->bindInt(':sort_order', $Qgroups->valueInt('sort_order'));
            $Qinsert->bindValue(':module', $Qgroups->value('module'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qvalues = $lC_Database->query('select id, products_variants_groups_id, title, sort_order from :table_products_variants_values where languages_id = :languages_id');
          $Qvalues->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
          $Qvalues->bindInt(':languages_id', $default_language_id);
          $Qvalues->execute();

          while ( $Qvalues->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_products_variants_values (id, languages_id, products_variants_groups_id, title, sort_order) values (:id, :languages_id, :products_variants_groups_id, :title, :sort_order)');
            $Qinsert->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
            $Qinsert->bindInt(':id', $Qvalues->valueInt('id'));
            $Qinsert->bindInt(':languages_id', $language_id);
            $Qinsert->bindInt(':products_variants_groups_id', $Qvalues->valueInt('products_variants_groups_id'));
            $Qinsert->bindValue(':title', $Qvalues->value('title'));
            $Qinsert->bindInt(':sort_order', $Qvalues->valueInt('sort_order'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qmanufacturers = $lC_Database->query('select manufacturers_id, manufacturers_url from :table_manufacturers_info where languages_id = :languages_id');
          $Qmanufacturers->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
          $Qmanufacturers->bindInt(':languages_id', $default_language_id);
          $Qmanufacturers->execute();

          while ( $Qmanufacturers->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_manufacturers_info (manufacturers_id, languages_id, manufacturers_url) values (:manufacturers_id, :languages_id, :manufacturers_url)');
            $Qinsert->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
            $Qinsert->bindInt(':manufacturers_id', $Qmanufacturers->valueInt('manufacturers_id'));
            $Qinsert->bindInt(':languages_id', $language_id);
            $Qinsert->bindValue(':manufacturers_url', $Qmanufacturers->value('manufacturers_url'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qstatus = $lC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where language_id = :language_id');
          $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
          $Qstatus->bindInt(':language_id', $default_language_id);
          $Qstatus->execute();

          while ( $Qstatus->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_orders_status (orders_status_id, language_id, orders_status_name) values (:orders_status_id, :language_id, :orders_status_name)');
            $Qinsert->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
            $Qinsert->bindInt(':orders_status_id', $Qstatus->valueInt('orders_status_id'));
            $Qinsert->bindInt(':language_id', $language_id);
            $Qinsert->bindValue(':orders_status_name', $Qstatus->value('orders_status_name'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qstatus = $lC_Database->query('select id, status_name from :table_orders_transactions_status where language_id = :language_id');
          $Qstatus->bindTable(':table_orders_transactions_status', TABLE_ORDERS_TRANSACTIONS_STATUS);
          $Qstatus->bindInt(':language_id', $default_language_id);
          $Qstatus->execute();

          while ( $Qstatus->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_orders_transactions_status (id, language_id, status_name) values (:id, :language_id, :status_name)');
            $Qinsert->bindTable(':table_orders_transactions_status', TABLE_ORDERS_TRANSACTIONS_STATUS);
            $Qinsert->bindInt(':id', $Qstatus->valueInt('id'));
            $Qinsert->bindInt(':language_id', $language_id);
            $Qinsert->bindValue(':status_name', $Qstatus->value('status_name'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qstatus = $lC_Database->query('select id, title, css_key from :table_shipping_availability where languages_id = :languages_id');
          $Qstatus->bindTable(':table_shipping_availability', TABLE_SHIPPING_AVAILABILITY);
          $Qstatus->bindInt(':languages_id', $default_language_id);
          $Qstatus->execute();

          while ( $Qstatus->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_shipping_availability (id, languages_id, title, css_key) values (:id, :languages_id, :title, :css_key)');
            $Qinsert->bindTable(':table_shipping_availability', TABLE_SHIPPING_AVAILABILITY);
            $Qinsert->bindInt(':id', $Qstatus->valueInt('id'));
            $Qinsert->bindInt(':languages_id', $language_id);
            $Qinsert->bindValue(':title', $Qstatus->value('title'));
            $Qinsert->bindValue(':css_key', $Qstatus->value('css_key'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qstatus = $lC_Database->query('select weight_class_id, weight_class_key, weight_class_title from :table_weight_classes where language_id = :language_id');
          $Qstatus->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
          $Qstatus->bindInt(':language_id', $default_language_id);
          $Qstatus->execute();

          while ( $Qstatus->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_weight_classes (weight_class_id, weight_class_key, language_id, weight_class_title) values (:weight_class_id, :weight_class_key, :language_id, :weight_class_title)');
            $Qinsert->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
            $Qinsert->bindInt(':weight_class_id', $Qstatus->valueInt('weight_class_id'));
            $Qinsert->bindValue(':weight_class_key', $Qstatus->value('weight_class_key'));
            $Qinsert->bindInt(':language_id', $language_id);
            $Qinsert->bindValue(':weight_class_title', $Qstatus->value('weight_class_title'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }

        if ( $error === false ) {
          $Qgroup = $lC_Database->query('select id, title, code, size_width, size_height, force_size from :table_products_images_groups where language_id = :language_id');
          $Qgroup->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
          $Qgroup->bindInt(':language_id', $default_language_id);
          $Qgroup->execute();

          while ( $Qgroup->next() ) {
            $Qinsert = $lC_Database->query('insert into :table_products_images_groups (id, language_id, title, code, size_width, size_height, force_size) values (:id, :language_id, :title, :code, :size_width, :size_height, :force_size)');
            $Qinsert->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
            $Qinsert->bindInt(':id', $Qgroup->valueInt('id'));
            $Qinsert->bindInt(':language_id', $language_id);
            $Qinsert->bindValue(':title', $Qgroup->value('title'));
            $Qinsert->bindValue(':code', $Qgroup->value('code'));
            $Qinsert->bindInt(':size_width', $Qgroup->value('size_width'));
            $Qinsert->bindInt(':size_height', $Qgroup->value('size_height'));
            $Qinsert->bindInt(':force_size', $Qgroup->value('force_size'));
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('languages');

      return true;
    } else {
      $lC_Database->rollbackTransaction();
    }

    return false;
  }
}
?>