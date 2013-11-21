<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: stream.php v1.0 2013-08-08 datazen $
*/
class stream {
  
  public static function execute($parameters) {
    $options = array('http' => array('method' => ($parameters['method'] == 'get' ? 'GET' : 'POST'),
                                     'follow_location' => true,
                                     'max_redirects' => 5,
                                     'content' => $parameters['parameters']));

    if ( !isset($parameters['header']) ) {
      $parameters['header'] = array();
    }

    $parameters['header'][] = 'Content-type: application/x-www-form-urlencoded';

    $options['http']['header'] = implode("\r\n", $parameters['header']);

    if ( !empty($parameters['certificate']) ) {
      $options['ssl'] = array('local_cert' => $parameters['certificate']);
    }

    $context = stream_context_create($options);

    return file_get_contents($parameters['url'], false, $context);
  }
  /**
  * Is Stream available 
  *  
  * @access public      
  * @return boolean
  */  
  public static function canUse() {
    return extension_loaded('openssl');
  }
}
?>