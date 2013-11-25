<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: mysqli_innodb.php v1.0 2013-08-08 datazen $
*/
require('mysqli.php');

class lC_Database_mysqli_innodb extends lC_Database_mysqli {
  var $use_transactions = true,
      $use_fulltext = false,
      $use_fulltext_boolean = false;

  public function __construct($server, $username, $password) {
    parent::__construct($server, $username, $password);
  }

  public function prepareSearch($columns) {
    $search_sql = '(';

    foreach ($columns as $column) {
      $search_sql .= $column . ' like :keyword or ';
    }

    $search_sql = substr($search_sql, 0, -4) . ')';

    return $search_sql;
  }
}
?>