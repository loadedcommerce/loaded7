<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: stream.php v1.0 2013-08-08 datazen $
*/
if (!class_exists('stream')) {
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

      if (isset($paramters['timeout'])) ini_set('default_socket_timeout', (int)$paramters['timeout']); // set the timeout
      $result = file_get_contents($parameters['url'], false, $context);
      ini_set('default_socket_timeout', 0); // reset timeout back to server limit
      
      return $result;
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
}
?>