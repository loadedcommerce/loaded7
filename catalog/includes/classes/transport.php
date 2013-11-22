<?php
/*
  $Id: transport.php v1.0 2013-01-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce.com

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
*/
require_once(DIR_FS_CATALOG . 'includes/classes/transport/curl.php');  
require_once(DIR_FS_CATALOG . 'includes/classes/transport/webRequest.php');  
require_once(DIR_FS_CATALOG . 'includes/classes/transport/stream.php');  

class transport {  

  protected static $_drivers = array('webRequest', 'curl', 'stream');
  
  /**
  * Transport Controller 
  *  
  * @param array    $parameters The parameters to process
  * @param string   $driver     The transport type
  * @access public      
  * @return mixed
  */  
  public static function getResponse($parameters, $driver = 'curl') {
    if ( !isset($driver) ) {
      foreach ( static::$_drivers as $d ) {
        if ( call_user_func(array($d, 'canUse')) ) {
          $driver = $d;

          break;
        }
      }
    }

    if ( !isset($parameters['header']) || !is_array($parameters['header'])) {
      $parameters['header'] = array();
    }

    if ( !isset($parameters['parameters']) ) {
      $parameters['parameters'] = '';
    }

    if ( !isset($parameters['method']) ) {
      $parameters['method'] = 'post';
    }

    $parameters['server'] = parse_url($parameters['url']);

    if ( !isset($parameters['server']['port']) ) {
      $parameters['server']['port'] = ($parameters['server']['scheme'] == 'https') ? 443 : 80;
    }

    if ( !isset($parameters['server']['path']) ) {
      $parameters['server']['path'] = '/';
    }

    if ( isset($parameters['server']['user']) && isset($parameters['server']['pass']) ) {
      $parameters['header'][] = 'Authorization: Basic ' . base64_encode($parameters['server']['user'] . ':' . $parameters['server']['pass']);
    }

    return trim(call_user_func(array($driver, 'execute'), $parameters));
  }
}
?>