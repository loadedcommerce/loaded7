<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: cache.php v1.0 2013-08-08 datazen $
*/

/**
 * The lC_Cache class handles the caching of dynamically generated data
 */

  class lC_Cache {

/**
 * The cached data
 *
 * @var mixed
 * @access private
 */

    private $_data;

/**
 * The key ID for the cached data
 *
 * @var string
 * @access private
 */

    private $_key;

/**
 * Write the data to a cache file
 *
 * @param string mixed $data The data to cache
 * @param string $key The key ID to save the cached data with
 * @access public
 */

    public function write($data, $key = null) {
      if ( empty($key) ) {
        $key = $this->_key;
      }

      return ( @file_put_contents(DIR_FS_WORK . 'cache/' . $key . '.cache', serialize($data), LOCK_EX) !== false );
    }

/**
 * Read data from a cache file if it has not yet expired
 *
 * @param string $key The key ID to read the data from the cached file
 * @param int $expire The amount of minutes the cached data is active for
 * @access public
 * @return boolean
 */

    public function read($key, $expire = null) {
      $this->_key = $key;

      $filename = DIR_FS_WORK . 'cache/' . $key . '.cache';

      if ( file_exists($filename) ) {
        $difference = floor((time() - filemtime($filename)) / 60);

        if ( empty($expire) || ( is_numeric($expire) && ($difference < $expire)) ) {
          $this->_data = unserialize(file_get_contents($filename));

          return true;
        }
      }

      return false;
    }

/**
 * Return the cached data
 *
 * @access public
 * @return mixed
 */

    public function getCache() {
      return $this->_data;
    }

/**
 * Start the buffer to cache its contents
 *
 * @access public
 */

    public function startBuffer() {
      ob_start();
    }

/**
 * Stop the buffer and cache its contents
 *
 * @access public
 */

    public function stopBuffer() {
      $this->_data = ob_get_contents();

      ob_end_clean();

      $this->write($this->_data);
    }

/**
 * Delete cached files by their key ID
 *
 * @param string $key The key ID of the cached files to delete
 * @access public
 */

    public static function clear($key) {
      
      $key_length = strlen($key);
      
      $d = dir(DIR_FS_WORK . 'cache/');
      
      while ( ($entry = $d->read()) !== false ) {
        if ( (strlen($entry) >= $key_length) && (substr($entry, 0, $key_length) == $key) ) {
          @unlink(DIR_FS_WORK . 'cache/' . $entry);
        }
      }

      $d->close();
    }
  }
?>