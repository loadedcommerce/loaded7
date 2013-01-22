<?php
/*
  $Id: curl.php v1.0 2013-01-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce.com

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
*/
//require_once('includes/classes/transport.php');  

class curl {
  /**
  * Curl Transport 
  *  
  * @param array    $parameters The parameters to process
  * @access public      
  * @return mixed
  */  
  public static function execute($parameters) {
    $curl = curl_init($parameters['server']['scheme'] . '://' . $parameters['server']['host'] . $parameters['server']['path'] . (isset($parameters['server']['query']) ? '?' . $parameters['server']['query'] : ''));

    $curl_options = array(CURLOPT_PORT => $parameters['server']['port'],
                          CURLOPT_HEADER => true,
                          CURLOPT_SSL_VERIFYPEER => false,
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_FORBID_REUSE => true,
                          CURLOPT_FRESH_CONNECT => true,
                          CURLOPT_FOLLOWLOCATION => false);

    if ( !empty($parameters['header']) ) {
      $curl_options[CURLOPT_HTTPHEADER] = $parameters['header'];
    }

    if ( !empty($parameters['certificate']) ) {
      $curl_options[CURLOPT_SSLCERT] = $parameters['certificate'];
    }

    if ( $parameters['method'] == 'post' ) {
      $curl_options[CURLOPT_POST] = true;
      $curl_options[CURLOPT_POSTFIELDS] = $parameters['parameters'];
    }

    curl_setopt_array($curl, $curl_options);
    $result = curl_exec($curl);

    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    list($headers, $body) = explode("\r\n\r\n", $result, 2);

    if ( ($http_code == 301) || ($http_code == 302) ) {
      if ( !isset($parameters['redir_counter']) || ($parameters['redir_counter'] < 6) ) {
        if ( !isset($parameters['redir_counter']) ) {
          $parameters['redir_counter'] = 0;
        }

        $matches = array();
        preg_match('/(Location:|URI:)(.*?)\n/i', $headers, $matches);

        $redir_url = trim(array_pop($matches));

        $parameters['redir_counter']++;

        $redir_params = array('url' => $redir_url,
                              'method' => $parameters['method'],
                              'redir_counter', $parameters['redir_counter']);

        $body = transport::getResponse($redir_params, 'curl');
      }
    }

    return $body;
  }
  /**
  * Is Curl available 
  *  
  * @access public      
  * @return boolean
  */ 
  public static function canUse() {
    return function_exists('curl_init');
  }
}
?>