<?php
/*
  $Id: download.php v1.0 2014-01-10 resultsonlyweb $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Application_Products_import_export_Actions_download extends lC_Application_Products_import_export {
    public function __construct() {
      global $lC_Language, $lC_MessageStack;

      parent::__construct();
	  
	  if(isset($_GET['filename'])){
		$filename = $_GET['filename'];
	  
		// Now send the file with header() magic
		header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Content-Type: Application/octet-stream");
		header("Content-disposition: attachment; filename=" . $filename);
			
		if (DOWNLOAD_BY_REDIRECT == '1') {
		  // This will work only on Unix/Linux hosts
		  lc_unlink_temp_dir(DIR_FS_DOWNLOAD_PUBLIC);
		  $tempdir = lc_random_name();
		  umask(0000);
		  mkdir(DIR_FS_DOWNLOAD_PUBLIC . $tempdir, 0777);
		  symlink(DIR_FS_DOWNLOAD . $filename, DIR_FS_DOWNLOAD_PUBLIC . $tempdir . '/' . $filename);
		  lc_redirect(DIR_WS_DOWNLOAD_PUBLIC . $tempdir . '/' . $filename);
		} else {
		  // This will work on all systems, but will need considerable resources
		  // We could also loop with fread($fp, 4096) to save memory
		  readfile(DIR_FS_DOWNLOAD . $filename);
		}
		die;
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
    }
  }
?>