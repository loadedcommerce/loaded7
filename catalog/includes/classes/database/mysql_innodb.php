<?php
/*
  $Id: mysql_innodb.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  require('mysql.php');

  class lC_Database_mysql_innodb extends lC_Database_mysql {
    var $use_transactions = true,
        $use_fulltext = false,
        $use_fulltext_boolean = false;

    function lC_Database_mysql_innodb($server, $username, $password) {
      $this->lC_Database_mysql($server, $username, $password);
    }

    function prepareSearch($columns) {
      $search_sql = '(';

      foreach ($columns as $column) {
        $search_sql .= $column . ' like :keyword or ';
      }

      $search_sql = substr($search_sql, 0, -4) . ')';

      return $search_sql;
    }
  }
?>