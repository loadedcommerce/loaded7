<?php
/**
  $Id: output_compression.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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