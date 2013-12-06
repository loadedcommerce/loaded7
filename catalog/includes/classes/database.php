<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: database.php v1.0 2013-08-08 datazen $
*/
class lC_Database {
  var $is_connected = false,
      $link,
      $error_reporting = true,
      $error = false,
      $error_number,
      $error_query,
      $server,
      $username,
      $password,
      $database,
      $port,
      $driver_options = array(),
      $debug = false,
      $number_of_queries = 0,
      $time_of_queries = 0,
      $nextID = null,
      $logging_transaction = false,
      $logging_transaction_action = false;

  public function &connect($server, $username, $password, $type = DB_DATABASE_CLASS) {
    require('database/' . $type . '.php');

    $class = 'lC_Database_' . $type;
    $object = new $class($server, $username, $password);

    return $object;
  }

  public function setConnected($boolean) {
    if ($boolean === true) {
      $this->is_connected = true;
    } else {
      $this->is_connected = false;
    }
  }

  public function isConnected() {
    if ($this->is_connected === true) {
      return true;
    } else {
      return false;
    }
  }

  public function &query($query) {
    $lC_Database_Result = new lC_Database_Result($this);
    $lC_Database_Result->setQuery($query);

    return $lC_Database_Result;
  }

  public function setError($error, $error_number = '', $query = '') {
    global $lC_MessageStack;

    if ($this->error_reporting === true) {
      $this->error = $error;
      $this->error_number = $error_number;
      $this->error_query = $query;

      //trigger_error('[MYSQL] ' . $this->error . ' (' . $this->error_number . '): [QUERY] ' . $this->error_query, E_USER_WARNING);

      if (isset($lC_MessageStack)) {
        $lC_MessageStack->add('debug', $this->getError());
      }
    }
  }

  public function isError() {
    if ($this->error === false) {
      return false;
    } else {
      return true;
    }
  }

  public function getError() {
    if ($this->isError()) {
      $error = '';

      if (!empty($this->error_number)) {
        $error .= $this->error_number . ': ';
      }

      $error .= $this->error;

      if (!empty($this->error_query)) {
        $error .= '; ' . htmlentities($this->error_query);
      }

      return $error;
    } else {
      return false;
    }
  }

  public function setErrorReporting($boolean) {
    if ($boolean === true) {
      $this->error_reporting = true;
    } else {
      $this->error_reporting = false;
    }
  }

  public function setDebug($boolean) {
    if ($boolean === true) {
      $this->debug = true;
    } else {
      $this->debug = false;
    }
  }
  
  public function file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
    return mb_convert_encoding($content, 'UTF-8',
      mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
  }    

  public function importSQL($sql_file, $database, $table_prefix = -1) {
    if ($this->selectDatabase($database)) {
      
      if ( file_exists($sql_file) ) {
        $import_queries = $this->file_get_contents_utf8($sql_file);                       
      } else {
        trigger_error(sprintf(ERROR_SQL_FILE_NONEXISTENT, $sql_file));

        return false;
      }

      @set_time_limit(0);        
      
      $sql_queries = array();
      $sql_length = strlen($import_queries);
      $pos = strpos($import_queries, ';');

      for ($i=$pos; $i<$sql_length; $i++) {
        // remove comments
        if ($import_queries[0] == '#') {
          $import_queries = ltrim(substr($import_queries, strpos($import_queries, "\n")));
          $sql_length = strlen($import_queries);
          $i = strpos($import_queries, ';')-1;
          continue;
        }

        if ($import_queries[($i+1)] == "\n") {
          $next = '';

          for ($j=($i+2); $j<$sql_length; $j++) {
            if (!empty($import_queries[$j])) {
              $next = substr($import_queries, $j, 6);

              if ($next[0] == '#') {
                // find out where the break position is so we can remove this line (#comment line)
                for ($k=$j; $k<$sql_length; $k++) {
                  if ($import_queries[$k] == "\n") {
                    break;
                  }
                }

                $query = substr($import_queries, 0, $i+1);

                $import_queries = substr($import_queries, $k);

                // join the query before the comment appeared, with the rest of the dump
                $import_queries = $query . $import_queries;
                $sql_length = strlen($import_queries);
                $i = strpos($import_queries, ';')-1;
                continue 2;
              }

              break;
            }
          }

          if (empty($next)) { // get the last insert query
            $next = 'insert';
          }

          if ((strtoupper($next) == 'DROP T') || (strtoupper($next) == 'CREATE') || (strtoupper($next) == 'INSERT')) {
            $next = '';

            $sql_query = trim(substr($import_queries, 0, $i));

            if ($table_prefix !== -1) {
              if (strtoupper(substr($sql_query, 0, 24)) == 'DROP TABLE IF EXISTS LC_') {
                $sql_query = 'DROP TABLE IF EXISTS ' . $table_prefix . substr($sql_query, 24);
              } elseif (strtoupper(substr($sql_query, 0, 16)) == 'CREATE TABLE LC_') {
                $sql_query = 'CREATE TABLE ' . $table_prefix . substr($sql_query, 16);
              } elseif (strtoupper(substr($sql_query, 0, 30)) == 'CREATE TABLE IF NOT EXISTS LC_') {
                $sql_query = 'CREATE TABLE ' . $table_prefix . substr($sql_query, 30);                  
              } elseif (strtoupper(substr($sql_query, 0, 15)) == 'INSERT INTO LC_') {
                $sql_query = 'INSERT INTO ' . $table_prefix . substr($sql_query, 15);
              }
            }

            $sql_queries[] = trim($sql_query);

            $import_queries = ltrim(substr($import_queries, $i+1));
            $sql_length = strlen($import_queries);
            $i = strpos($import_queries, ';')-1;
          }
        }
      }

      for ($i=0, $n=sizeof($sql_queries); $i<$n; $i++) {
        $this->simpleQuery($sql_queries[$i]);

        if ($this->isError()) {
          break;
        }
      }

    }

    if ($this->isError()) {
      return false;
    } else {
      return true;
    }
  }

  public function hasCreatePermission($database) {
    $db_created = false;

    if (empty($database)) {
      $this->setError(ERROR_DB_NO_DATABASE_SELECTED);

      return false;
    }

    $this->setErrorReporting(false);

    if ($this->selectDatabase($database) === false) {
      $this->setErrorReporting(true);

      if ($this->simpleQuery('create database ' . $database)) {
        $db_created = true;
      }
    }

    $this->setErrorReporting(true);

    if ($this->isError() === false) {
      if ($this->selectDatabase($database)) {
        if ($this->simpleQuery('create table LoadedCommerceTestTable1536f ( temp_id int )')) {
          if ($db_created === true) {
            $this->simpleQuery('drop database ' . $database);
          } else {
            $this->simpleQuery('drop table LoadedCommerceTestTable1536f');
          }
        }
      }
    }

    if ($this->isError()) {
      return false;
    } else {
      return true;
    }
  }

  public function numberOfQueries() {
    return $this->number_of_queries;
  }

  public function timeOfQueries() {
    return $this->time_of_queries;
  }

  public function getMicroTime() {
    list($usec, $sec) = explode(' ', microtime());

    return ((float)$usec + (float)$sec);
  }
}

class lC_Database_Result {
  var $db_class,
      $sql_query,
      $query_handler,
      $result,
      $rows,
      $affected_rows,
      $cache_key,
      $cache_expire,
      $cache_data,
      $cache_read = false,
      $debug = false,
      $batch_query = false,
      $batch_number,
      $batch_rows,
      $batch_size,
      $batch_to,
      $batch_from,
      $batch_select_field,
      $logging = false,
      $logging_module,
      $logging_module_id,
      $logging_fields = array(),
      $logging_changed = array(),
      $_db_tables = array();

  public function __construct(&$db_class) {
    $this->db_class =& $db_class;
  }

  public function setQuery($query) {
    $this->sql_query = $query;
  }

  public function appendQuery($query) {
    $this->sql_query .= ' ' . $query;
  }

  public function removeQuery($query) {
    $this->sql_query = str_replace($query, "", $this->sql_query);
  }

  public function getQuery() {
    return $this->sql_query;
  }

  public function setDebug($boolean) {
    if ($boolean === true) {
      $this->debug = true;
    } else {
      $this->debug = false;
    }
  }

  public function valueMixed($column, $type = 'string') {
    if (!isset($this->result)) {
      $this->next();
    }

    switch ($type) {
      case 'protected':
        return lc_output_string_protected($this->result[$column]);
        break;
      case 'int':
        return (int)$this->result[$column];
        break;
      case 'decimal':
        return (float)$this->result[$column];
        break;
      case 'string':
      default:
        return $this->result[$column];
    }
  }

  public function value($column) {
    return $this->valueMixed($column, 'string');
  }

  public function valueProtected($column) {
    return $this->valueMixed($column, 'protected');
  }

  public function valueInt($column) {
    return $this->valueMixed($column, 'int');
  }

  public function valueDecimal($column) {
    return $this->valueMixed($column, 'decimal');
  }

  public function bindValueMixed($place_holder, $value, $type = 'string', $log = true) {
    if ($log === true) {
      $this->logging_fields[substr($place_holder, 1)] = $value;
    }

    switch ($type) {
      case 'int':
        $value = intval($value);
        break;
      case 'float':
        $value = floatval($value);
        break;
      case 'raw':
        break;
      case 'date':
        if ( empty($value) ) {
          $value = 'null';
        } else {
          $value = "'" . $this->db_class->parseString(trim($value)) . "'";
        }
        break;
      case 'string':
      default:
        $value = "'" . $this->db_class->parseString(trim($value)) . "'";
    }

    $this->bindReplace($place_holder, $value);
  }

  public function bindReplace($place_holder, $value) {
    $pos = strpos($this->sql_query, $place_holder);

    if ($pos !== false) {
      $length = strlen($place_holder);
      $character_after_place_holder = substr($this->sql_query, $pos+$length, 1);

      if (($character_after_place_holder === false) || preg_match('/[ ,)"]/', $character_after_place_holder)) {
        $this->sql_query = substr_replace($this->sql_query, $value, $pos, $length);
      }
    }
  }

  public function bindValue($place_holder, $value) {
    $this->bindValueMixed($place_holder, $value, 'string');
  }

  public function bindInt($place_holder, $value) {
    $this->bindValueMixed($place_holder, $value, 'int');
  }

  public function bindFloat($place_holder, $value) {
    $this->bindValueMixed($place_holder, $value, 'float');
  }

  public function bindRaw($place_holder, $value) {
    $this->bindValueMixed($place_holder, $value, 'raw');
  }

  public function bindDate($place_holder, $value) {
    $this->bindValueMixed($place_holder, $value, 'date');
  }

  public function bindTable($place_holder, $value = null) {
    if ( empty($value) ) { // remove :table_ from $place_holder and prefix it with the database name prefix value
      if ( !isset($this->_db_tables[$place_holder]) ) {
        $this->_db_tables[$place_holder] = DB_TABLE_PREFIX . substr($place_holder, 7);
      }

      $value = $this->_db_tables[$place_holder];
    }

    $this->bindValueMixed($place_holder, $value, 'raw', false);
  }

  public function next() {
    if ($this->cache_read === true) {
      list(, $this->result) = each($this->cache_data);
    } else {
      if (!isset($this->query_handler)) {
        $this->execute();
      }

      $this->result = $this->db_class->next($this->query_handler);

      if (isset($this->cache_key)) {
        $this->cache_data[] = $this->result;
      }
    }

    return $this->result;
  }

  public function freeResult() {
    global $lC_Cache;

    if ($this->cache_read === false) {
      if (preg_match('/^SELECT/i', $this->sql_query)) {
        $this->db_class->freeResult($this->query_handler);
      }

      if (isset($this->cache_key)) {
        $lC_Cache->write($this->cache_data, $this->cache_key);
      }
    }

    unset($this);
  }

  public function numberOfRows() {
    if (!isset($this->rows)) {
      if (!isset($this->query_handler)) {
        $this->execute();
      }

      if (isset($this->cache_key) && ($this->cache_read === true)) {
        $this->rows = sizeof($this->cache_data);
      } else {
        $this->rows = $this->db_class->numberOfRows($this->query_handler);
      }
    }

    return $this->rows;
  }

  public function affectedRows() {
    if (!isset($this->affected_rows)) {
      if (!isset($this->query_handler)) {
        $this->execute();
      }

      $this->affected_rows = $this->db_class->affectedRows();
    }

    return $this->affected_rows;
  }

  public function execute() {
    global $lC_Cache;

    if (isset($this->cache_key)) {
      if ($lC_Cache->read($this->cache_key, $this->cache_expire)) {
        $this->cache_data = $lC_Cache->getCache();

        $this->cache_read = true;
      }
    }

    if ($this->cache_read === false) {
      if ($this->logging === true) {
        $this->logging_action = substr($this->sql_query, 0, strpos($this->sql_query, ' '));

        if ($this->logging_action == 'update') {
          $db = preg_split('/ /', $this->sql_query, 3);
          $this->logging_database = $db[1];

          $test = $this->db_class->simpleQuery('select ' . implode(', ', array_keys($this->logging_fields)) . ' from ' . $this->logging_database . substr($this->sql_query, lc_strrpos_string($this->sql_query, ' where ')));

          while ($result = $this->db_class->next($test)) {
            foreach ($this->logging_fields as $key => $value) {
              if ($result[$key] != $value) {
                $this->logging_changed[] = array('key' => $this->logging_database . '.' . $key, 'old' => $result[$key], 'new' => $value);
              }
            }
          }
        } elseif ($this->logging_action == 'insert') {
          $db = preg_split('/ /', $this->sql_query, 4);
          $this->logging_database = $db[2];

          foreach ($this->logging_fields as $key => $value) {
            $this->logging_changed[] = array('key' => $this->logging_database . '.' . $key, 'old' => '', 'new' => $value);
          }
        } elseif ($this->logging_action == 'delete') {
          $db = preg_split('/ /', $this->sql_query, 4);
          $this->logging_database = $db[2];

          $del = $this->db_class->simpleQuery('select * from ' . $this->logging_database . ' ' . $db[3]);
          while ($result = $this->db_class->next($del)) {
            foreach ($result as $key => $value) {
              $this->logging_changed[] = array('key' => $this->logging_database . '.' . $key, 'old' => $value, 'new' => '');
            }
          }
        }
      }

      $this->query_handler = $this->db_class->simpleQuery($this->sql_query, $this->debug);

      if ($this->logging === true) {
        if ($this->db_class->logging_transaction_action === false) {
          $this->db_class->logging_transaction_action = $this->logging_action;
        }

        if ($this->affectedRows($this->query_handler) > 0) {
          if (!empty($this->logging_changed)) {
            if ( ($this->logging_action == 'insert') && !is_numeric($this->logging_module_id) ) {
              $this->logging_module_id = $this->db_class->nextID();
              $this->setNextID($this->logging_module_id);
            }

            if ( class_exists('lC_Administrators_log_Admin') ) {
              lC_Administrators_log_Admin::insert($this->logging_module, $this->db_class->logging_transaction_action, $this->logging_module_id, $this->logging_action, $this->logging_changed, $this->db_class->logging_transaction);
            }
          }
        }
      }

      if ($this->batch_query === true) {
        $this->batch_size = $this->db_class->getBatchSize($this->sql_query, $this->batch_select_field);

        $this->batch_to = ($this->batch_rows * $this->batch_number);
        if ($this->batch_to > $this->batch_size) {
          $this->batch_to = $this->batch_size;
        }

        $this->batch_from = ($this->batch_rows * ($this->batch_number - 1));
        if ($this->batch_to == 0) {
          $this->batch_from = 0;
        } else {
          $this->batch_from++;
        }
      }

      return $this->query_handler;
    }
  }

  public function executeRandom() {
    return $this->query_handler = $this->db_class->randomQuery($this->sql_query);
  }

  public function executeRandomMulti() {
    return $this->query_handler = $this->db_class->randomQueryMulti($this->sql_query);
  }

  public function setCache($key, $expire = 0) {
    $this->cache_key = $key;
    $this->cache_expire = $expire;
  }

  public function setLogging($module, $id = null) {
    $this->logging = true;
    $this->logging_module = $module;
    $this->logging_module_id = $id;
  }

  public function setNextID($id) {
    $this->db_class->nextID = $id;
  }

  public function toArray() {
    if (!isset($this->result)) {
      $this->next();
    }

    return $this->result;
  }

  public function prepareSearch($keywords, $columns, $embedded = false) {
    if ($embedded === true) {
      $this->sql_query .= ' and ';
    }

    $keywords_array = explode(' ', $keywords);

    if ($this->db_class->use_fulltext === true) {
      if ($this->db_class->use_fulltext_boolean === true) {
        $keywords = '';

        foreach ($keywords_array as $keyword) {
          if ((substr($keyword, 0, 1) != '-') && (substr($keyword, 0, 1) != '+')) {
            $keywords .= '+';
          }

          $keywords .= $keyword . ' ';
        }

        $keywords = substr($keywords, 0, -1);
      }

      $this->sql_query .= $this->db_class->prepareSearch($columns);
      $this->bindValue(':keywords', $keywords);
    } else {
      foreach ($keywords_array as $keyword) {
        $this->sql_query .= $this->db_class->prepareSearch($columns);

        foreach ($columns as $column) {
          $this->bindValue(':keyword', '%' . $keyword . '%');
        }

        $this->sql_query .= ' and ';
      }

      $this->sql_query = substr($this->sql_query, 0, -5);
    }
  }

  public function setBatchLimit($batch_number = 1, $maximum_rows = 20, $select_field = '') {    
    $this->batch_query = true;
    $this->batch_number = (is_numeric($batch_number) ? $batch_number : 1);
    $this->batch_rows = $maximum_rows;
    $this->batch_select_field = (empty($select_field) ? '*' : $select_field);

    $from = max(($this->batch_number * $maximum_rows) - $maximum_rows, 0);

    $this->sql_query = $this->db_class->setBatchLimit($this->sql_query, $from, $maximum_rows);

  }

  public function getBatchSize() {
    return $this->batch_size;
  }

  public function isBatchQuery() {
    if ($this->batch_query === true) {
      return true;
    }

    return false;
  }

  public function getBatchTotalPages($text) {
    return sprintf($text, $this->batch_from, $this->batch_to, $this->batch_size);
  }

  public function getBatchPageLinks($batch_keyword = 'page', $parameters = '', $with_pull_down_menu = true) {
    $string = '<li>' . $this->getBatchPreviousPageLink($batch_keyword, $parameters) . '</li>';

    if ( $with_pull_down_menu === true ) {
      $string .= '<li>' . $this->getBatchPagesPullDownMenu($batch_keyword, $parameters) . '</li>';
    }

    $string .= '<li>' . $this->getBatchNextPageLink($batch_keyword, $parameters) . '</li>';

    return $string;
  }

  public function getBatchPagesPullDownMenu($batch_keyword = 'page', $parameters = '') {
    global $lC_Language;

    $number_of_pages = ceil($this->batch_size / $this->batch_rows);

    if ( $number_of_pages > 1 ) {
      $pages_array = array();

      for ( $i = 1; $i <= $number_of_pages; $i++ ) {
        $pages_array[] = array('id' => $i,
                               'text' => $i);
      }

      $hidden_parameter = '';

      if ( !empty($parameters) ) {
        $parameters = explode('&', $parameters);

        foreach ( $parameters as $parameter ) {
          $keys = explode('=', $parameter, 2);

          if ( $keys[0] != $batch_keyword ) {
            $hidden_parameter .= lc_draw_hidden_field($keys[0], (isset($keys[1]) ? $keys[1] : ''));
          }
        }
      }
               
      $string = '<form action="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME'])) . '" action="get">' . $hidden_parameter .
                '<a href="javascript:void(0);">' . sprintf($lC_Language->get('result_set_current_page'), lc_draw_pull_down_menu($batch_keyword, $pages_array, $this->batch_number, 'onchange="this.form.submit();"'), $number_of_pages) . '</a>' .
                lc_draw_hidden_session_id_field() . '</form>';
    } else {
      $string = '<a href="javascript:void(0);">' . sprintf($lC_Language->get('result_set_current_page'), 1, 1) . '</a>';
    }

    return $string;
  }

  public function getBatchPreviousPageLink($batch_keyword = 'page', $parameters = '') {
    global $lC_Language;

    $get_parameter = '';

    if ( !empty($parameters) ) {
      $parameters = explode('&', $parameters);

      foreach ( $parameters as $parameter ) {
        $keys = explode('=', $parameter, 2);

        if ( $keys[0] != $batch_keyword ) {
          $get_parameter .= $keys[0] . (isset($keys[1]) ? '=' . $keys[1] : '') . '&';
        }
      }
    }

    if ( defined('LC_IN_ADMIN') && ( LC_IN_ADMIN === true ) ) {
      $back_string = lc_icon('nav_back.png');
      $back_grey_string = lc_icon('nav_back_grey.png');
    } else {
      $back_string = (file_exists(DIR_FS_TEMPLATE . 'images/icons/16/nav_back.png')) ? lc_icon('nav_back.png', null, null, 'style="vertical-align:-10%;"') : $lC_Language->get('result_set_previous_page');
      $back_grey_string = (file_exists(DIR_FS_TEMPLATE . 'images/icons/16/nav_back_grey.png')) ? lc_icon('nav_back_grey.png', null, null, 'style="vertical-align:-10%;"') : $lC_Language->get('result_set_previous_page');
    }

    if ( $this->batch_number > 1 ) {
      $string = lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $get_parameter . $batch_keyword . '=' . ($this->batch_number - 1)), $back_string);
    } else {
      $string = '<a href="javascript:void(0);">' . $back_grey_string . '</a>';
    }

    $string .= '&nbsp;';

    return $string;
  }

  public function getBatchNextPageLink($batch_keyword = 'page', $parameters = '') {
    global $lC_Language;

    $number_of_pages = ceil($this->batch_size / $this->batch_rows);

    $get_parameter = '';

    if ( !empty($parameters) ) {
      $parameters = explode('&', $parameters);

      foreach ( $parameters as $parameter ) {
        $keys = explode('=', $parameter, 2);

        if ( $keys[0] != $batch_keyword ) {
          $get_parameter .= $keys[0] . (isset($keys[1]) ? '=' . $keys[1] : '') . '&';
        }
      }
    }

    if ( defined('LC_IN_ADMIN') && ( LC_IN_ADMIN === true ) ) {
      $forward_string = lc_icon('nav_forward.png');
      $forward_grey_string = lc_icon('nav_forward_grey.png');
    } else {  
      $forward_string =  (file_exists(DIR_FS_TEMPLATE . 'images/icons/16/nav_forward.png')) ? lc_icon('nav_forward.png', null, null, 'style="vertical-align:-10%;"') : $lC_Language->get('result_set_next_page');
      $forward_grey_string =  (file_exists(DIR_FS_TEMPLATE . 'images/icons/16/nav_forward_grey.png')) ? lc_icon('nav_forward_grey.png', null, null, 'style="vertical-align:-10%;"') : $lC_Language->get('result_set_next_page');          
    }

    $string = '&nbsp;';

    if ( ( $this->batch_number < $number_of_pages ) && ( $number_of_pages != 1 ) ) {
      $string .= lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $get_parameter . $batch_keyword . '=' . ($this->batch_number + 1)), $forward_string);
    } else {
      $string .= '<a href="javascript:void(0);">' . $forward_grey_string . '</a>';
    }

    return $string;
  }
}
?>