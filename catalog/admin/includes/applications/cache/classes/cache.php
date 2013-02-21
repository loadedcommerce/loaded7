<?php
/*
  $Id: cache.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Cache_Admin class is for AJAX remote program control
*/
class lC_Cache_Admin { 
 /*
  * Returns the cache datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() { 
    global $lC_Language, $_module;

    $media = $_GET['media'];
    
    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_WORK . 'cache/');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('cache');
    $cached_files = array();
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      $last_modified = filemtime(DIR_FS_WORK . 'cache/' . $file['name']);
      if ( strpos($file['name'], '-') !== false ) {
        $code = substr($file['name'], 0, strpos($file['name'], '-'));
      } else {
        $code = substr($file['name'], 0, strpos($file['name'], '.'));
      }
      if ( isset($cached_files[$code]) ) {
        $cached_files[$code]['total']++;
        if ( $last_modified > $cached_files[$code]['last_modified'] ) {
          $cached_files[$code]['last_modified'] = $last_modified;
        }
      } else {
        $cached_files[$code] = array('total' => 1,
                                     'last_modified' => $last_modified);
      }
    }
    $result = array('aaData' => array());
    foreach( $cached_files as $cache => $stats ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $cache . '" id="' . $cache . '"></td>';
      $block = '<td>' . $cache . '</td>';
      $total = '<td>' . $stats['total'] . '</td>';
      $last = '<td>' . lC_DateTime::getShort(lC_DateTime::fromUnixTimestamp($stats['last_modified']), true) . '</td>';
      $action = '<td><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $cache . '\', \'' . urlencode($cache) . '\')') . '" class="button icon-trash' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_delete')) . '</a>
                 </span></td>';         
      $result['aaData'][] = array("$check", "$block", "$total", "$last", "$action");
    }

    return $result;
  }
 /*
  * Delete cache file
  *
  * @param string $id The cache file name to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    lC_Cache::clear($id);

    return true;
  }
 /*
  * Batch delete cache files
  *
  * @param array $batch The cache file name's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Cache_Admin::delete($id);
    }
    return true;
  } 
}
?>