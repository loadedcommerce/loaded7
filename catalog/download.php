<?php
/**
  @package    catalog
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: download.php v1.0 2013-08-08 datazen $
*/
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

include('includes/application_top.php');

if ($lC_Customer->isLoggedOn() == false) die;

// Check download.php was called with proper GET parameters
if ((isset($_GET['order']) && !is_numeric($_GET['order'])) || (isset($_GET['id']) && !is_numeric($_GET['id'])) ) {
  die;
}

// Check that order_id, customer id and filename match
$Qdownloads = $lC_Database->query('select date_format(o.date_purchased, "%Y-%m-%d") as date_purchased_day, opd.download_maxdays, opd.download_count, opd.download_maxdays, opd.orders_products_filename from :table_orders o, :table_orders_products op, :table_orders_products_download opd where o.customers_id = :customers_id and o.orders_id = :orders_id and o.orders_id = op.orders_id and op.orders_products_id = opd.orders_products_id and opd.orders_products_download_id = :orders_products_download_id and opd.orders_products_filename != ""');
$Qdownloads->bindTable(':table_orders', TABLE_ORDERS);
$Qdownloads->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
$Qdownloads->bindTable(':table_orders_products_download', TABLE_ORDERS_PRODUCTS_DOWNLOAD);
$Qdownloads->bindInt(':customers_id', $lC_Customer->getID());
$Qdownloads->bindInt(':orders_id', $_GET['order']);
$Qdownloads->bindInt(':orders_products_download_id', $_GET['id']);
$Qdownloads->execute();

if ($Qdownloads->numberOfRows() < 1) {
  die();
}

// MySQL 3.22 does not have INTERVAL
list($dt_year, $dt_month, $dt_day) = explode('-', $Qdownloads->value('date_purchased_day'));
$download_timestamp = @mktime(23, 59, 59, $dt_month, $dt_day + $Qdownloads->value('download_maxdays'), $dt_year);

// Die if time expired (maxdays = 0 means no time limit)
if (($Qdownloads->value('download_maxdays') != 0) && ($download_timestamp <= time())) die;
// Die if remaining count is <=0
if ($Qdownloads->value('download_count') <= 0) die;
// Die if file is not there
if (!file_exists(DIR_FS_DOWNLOAD . $Qdownloads->value('orders_products_filename'))) die;

// Now decrement counter
$Qupdate = $lC_Database->query('update :table_orders_products_download set download_count = download_count-1 where orders_products_download_id = :orders_products_download_id');
$Qupdate->bindTable(':table_orders_products_download', TABLE_ORDERS_PRODUCTS_DOWNLOAD);
$Qupdate->bindInt(':orders_products_download_id', $_GET['id']);
$Qupdate->execute();

// Now send the file with header() magic
header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: Application/octet-stream");
header("Content-disposition: attachment; filename=" . $Qdownloads->value('orders_products_filename'));

if (DOWNLOAD_BY_REDIRECT == '1') {
  // This will work only on Unix/Linux hosts
  lc_unlink_temp_dir(DIR_FS_DOWNLOAD_PUBLIC);
  $tempdir = lc_random_name();
  umask(0000);
  mkdir(DIR_FS_DOWNLOAD_PUBLIC . $tempdir, 0777);
  symlink(DIR_FS_DOWNLOAD . $Qdownloads->value('orders_products_filename'), DIR_FS_DOWNLOAD_PUBLIC . $tempdir . '/' . $Qdownloads->value('orders_products_filename'));
  lc_redirect(DIR_WS_DOWNLOAD_PUBLIC . $tempdir . '/' . $Qdownloads->value('orders_products_filename'));
} else {
  // This will work on all systems, but will need considerable resources
  // We could also loop with fread($fp, 4096) to save memory
  readfile(DIR_FS_DOWNLOAD . $Qdownloads->value('orders_products_filename'));
}
/*
* Returns a random name, 16 to 20 characters long
*
* @access public
* @return string
*/  
function lc_random_name() {
  $letters = 'abcdefghijklmnopqrstuvwxyz';
  $dirname = '.';
  $length = floor(lc_rand(16,20));

  for ($i = 1; $i <= $length; $i++) {
   $q = floor(lc_rand(1,26));
   $dirname .= $letters[$q];
  }

  return $dirname;
}
/*
* Unlinks all subdirectories and files in $dir (non-recursive)
*
* @param string $dir  The parent directory
* @access public
* @return void
*/ 
function lc_unlink_temp_dir($dir) {
  $h1 = opendir($dir);
  while ($subdir = readdir($h1)) {
    // Ignore non directories
    if (!is_dir($dir . $subdir)) continue;
    // Ignore . and .. and CVS
    if ($subdir == '.' || $subdir == '..' || $subdir == 'CVS') continue;
    // Loop and unlink files in subdirectory
    $h2 = opendir($dir . $subdir);
    while ($file = readdir($h2)) {
      if ($file == '.' || $file == '..') continue;
      @unlink($dir . $subdir . '/' . $file);
    }
    closedir($h2);
    @rmdir($dir . $subdir);
  }
  closedir($h1);
}  
?>