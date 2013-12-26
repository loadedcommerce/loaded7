<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: curl.php v1.0 2013-08-08 datazen $
*/
if (!class_exists('curl')) {
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
      
     // added support for curl proxy
      if (defined('CURL_PROXY_HOST') && defined('CURL_PROXY_PORT') && CURL_PROXY_HOST != NULL && CURL_PROXY_PORT != NULL) {
        $curl_options[CURLOPT_HTTPPROXYTUNNEL] = true;
        $curl_options[CURLOPT_PROXY] = CURL_PROXY_HOST . ":" . CURL_PROXY_PORT;
      }
      if (defined('CURL_PROXY_USER') && defined('CURL_PROXY_PASSWORD') && CURL_PROXY_USER != NULL && CURL_PROXY_PASSWORD != NULL) {
        $curl_options[CURLOPT_PROXYUSERPWD] = CURL_PROXY_USER . ':' . CURL_PROXY_PASSWORD;
      }    

      curl_setopt_array($curl, $curl_options);
      $result = curl_exec($curl);

      $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      curl_close($curl);

      list($headers1, $body1,$body2) = explode("\r\n\r\n", $result, 3);
      $body = (empty($body2)) ? $body1 : $body2;

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
}
?>