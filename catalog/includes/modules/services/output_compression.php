<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: output_compression.php v1.0 2013-08-08 datazen $
*/
class lC_Services_output_compression {
  function start() {
    if (extension_loaded('zlib')) {
      if ((int)ini_get('zlib.output_compression') < 1) {
        ini_set('zlib.output_compression_level', SERVICE_OUTPUT_COMPRESSION_GZIP_LEVEL);
        ob_start('ob_gzhandler');

        return false; // no call to stop() is needed
      }
    }

    return false;
  }

  function stop() {
    return true;
  }
}
?>