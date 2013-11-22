<?php
/*
  $Id: stream.php v1.0 2013-01-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce.com

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
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
}
?>