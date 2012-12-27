<?php
/*
  $Id: mysqli.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Database_mysqli extends lC_Database {
    var $use_transactions = false,
        $use_fulltext = false,
        $use_fulltext_boolean = false;

    function __construct($server, $username, $password) {
      $this->server = $server;
      $this->username = $username;
      $this->password = $password;

      if ($this->is_connected === false) {
        $this->connect();
      }
    }

    function connect() {
      if (defined('USE_PCONNECT') && (USE_PCONNECT == 'true')) {
        $connect_function = 'mysqli_pconnect';
      } else {
        $connect_function = 'mysqli_connect';
      }

      if ($this->link = @$connect_function($this->server, $this->username, $this->password)) {
        $this->setConnected(true);

        if ( version_compare(mysqli_get_server_info($this->link), '5.0.2') >= 0 ) {
          $this->simpleQuery('set session sql_mode="STRICT_ALL_TABLES"');
        }

        return true;
      } else {
        $this->setError(mysqli_connect_error(), mysqli_connect_errno());

        return false;
      }
    }

    function disconnect() {
      if ($this->isConnected()) {
        if (@mysqli_close($this->link)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    }

    function selectDatabase($database) {
      if ($this->isConnected()) {
        if (@mysqli_select_db($this->link, $database)) {
          return true;
        } else {
          $this->setError(mysqli_error($this->link), mysqli_errno($this->link));

          return false;
        }
      } else {
        return false;
      }
    }

    function parseString($value) {
      return mysqli_real_escape_string($this->link, $value);
    }

    function simpleQuery($query, $debug = false) {
      global $lC_MessageStack, $lC_Services;

      if ($this->isConnected()) {
        $this->number_of_queries++;

        if ( ($debug === false) && ($this->debug === true) ) {
          $debug = true;
        }

        if (isset($lC_Services) && $lC_Services->isStarted('debug')) {
          if ( ($debug === false) && (SERVICE_DEBUG_OUTPUT_DB_QUERIES == '1') ) {
            $debug = true;
          }

          if (!lc_empty(SERVICE_DEBUG_EXECUTION_TIME_LOG) && (SERVICE_DEBUG_LOG_DB_QUERIES == '1')) {
            @error_log('QUERY ' . $query . "\n", 3, SERVICE_DEBUG_EXECUTION_TIME_LOG);
          }
        } elseif ($debug === true) {
          $debug = false;
        }

        if ($debug === true) {
          $time_start = $this->getMicroTime();
        }

        $resource = @mysqli_query($this->link, $query);

        if ($debug === true) {
          $time_end = $this->getMicroTime();

          $query_time = number_format($time_end - $time_start, 5);

          if ($this->debug === true) {
            $this->time_of_queries += $query_time;
          }

          echo '<div style="font-family: Verdana, Arial, sans-serif; font-size: 7px; font-weight: bold;">[<a href="#query' . $this->number_of_queries . '">#' . $this->number_of_queries . '</a>]</div>';

          $lC_MessageStack->add('debug', '<a name=\'query' . $this->number_of_queries . '\'></a>[#' . $this->number_of_queries . ' - ' . $query_time . 's] ' . $query, 'warning');
        }

        if ($resource !== false) {
          $this->error = false;
          $this->error_number = null;
          $this->error_query = null;

          if ( mysqli_warning_count($this->link) > 0 ) {
            $warning_query = @mysqli_query($this->link, 'show warnings');
            while ( $warning = @mysqli_fetch_row($warning_query) ) {
              @trigger_error(sprintf('[MYSQL] %s (%d): %s [QUERY] ' . $query, $warning[0], $warning[1], $warning[2]), E_USER_WARNING);
            }
            mysqli_free_result($warning_query);
          }

          return $resource;
        } else {
          $this->setError(mysqli_error($this->link), mysqli_errno($this->link), $query);

          return false;
        }
      } else {
        return false;
      }
    }

    function dataSeek($row_number, $resource) {
      return @mysqli_data_seek($resource, $row_number);
    }

    function randomQuery($query) {
      $query .= ' order by rand() limit 1';

      return $this->simpleQuery($query);
    }

    function randomQueryMulti($query) {
      $resource = $this->simpleQuery($query);

      $num_rows = $this->numberOfRows($resource);

      if ($num_rows > 0) {
        $random_row = lc_rand(0, ($num_rows - 1));

        $this->dataSeek($random_row, $resource);

        return $resource;
      } else {
        return false;
      }
    }

    function next($resource) {
      return @mysqli_fetch_assoc($resource);
    }

    function freeResult($resource) {
      return @mysqli_free_result($resource);
    }

    function nextID() {
      if ( is_numeric($this->nextID) ) {
        $id = $this->nextID;
        $this->nextID = null;

        return $id;
      } elseif ($id = @mysqli_insert_id($this->link)) {
        return $id;
      } else {
        $this->setError(mysqli_error($this->link), mysqli_errno($this->link));

        return false;
      }
    }

    function numberOfRows($resource) {
      return @mysqli_num_rows($resource);
    }

    function affectedRows() {
      return @mysqli_affected_rows($this->link);
    }

    function startTransaction() {
      $this->logging_transaction = true;

      if ($this->use_transactions === true) {
        return @mysqli_autocommit($this->link, false);
      }

      return false;
    }

    function commitTransaction() {
      if ($this->logging_transaction === true) {
        $this->logging_transaction = false;
        $this->logging_transaction_action = false;
      }

      if ($this->use_transactions === true) {
        $result = @mysqli_commit($this->link);

        @mysqli_autocommit($this->link, true);

        return $result;
      }

      return false;
    }

    function rollbackTransaction() {
      if ($this->logging_transaction === true) {
        $this->logging_transaction = false;
        $this->logging_transaction_action = false;
      }

      if ($this->use_transactions === true) {
        $result = @mysqli_rollback($this->link);

        @mysqli_autocommit($this->link, true);

        return $result;
      }

      return false;
    }

    function setBatchLimit($sql_query, $from, $maximum_rows) {
      return $sql_query . ' limit ' . $from . ', ' . $maximum_rows;
    }

    function getBatchSize($sql_query, $select_field = '*') {
      if (strpos($sql_query, 'SQL_CALC_FOUND_ROWS') !== false) {
        $bb = $this->query('select found_rows() as total');
      } else {
        $total_query = substr($sql_query, 0, strpos($sql_query, ' limit '));

        $pos_to = strlen($total_query);
        $pos_from = strpos($total_query, ' from ');

        if (($pos_group_by = strpos($total_query, ' group by ', $pos_from)) !== false) {
          if ($pos_group_by < $pos_to) {
            $pos_to = $pos_group_by;
          }
        }

        if (($pos_having = strpos($total_query, ' having ', $pos_from)) !== false) {
          if ($pos_having < $pos_to) {
            $pos_to = $pos_having;
          }
        }

        if (($pos_order_by = strpos($total_query, ' order by ', $pos_from)) !== false) {
          if ($pos_order_by < $pos_to) {
            $pos_to = $pos_order_by;
          }
        }

        $bb = $this->query('select count(' . $select_field . ') as total ' . substr($total_query, $pos_from, ($pos_to - $pos_from)));
      }

      return $bb->value('total');
    }

    function prepareSearch($columns) {
      if ($this->use_fulltext === true) {
        return 'match (' . implode(', ', $columns) . ') against (:keywords' . (($this->use_fulltext_boolean === true) ? ' in boolean mode' : '') . ')';
      } else {
        $search_sql = '(';

        foreach ($columns as $column) {
          $search_sql .= $column . ' like :keyword or ';
        }

        $search_sql = substr($search_sql, 0, -4) . ')';

        return $search_sql;
      }
    }
  }
?>