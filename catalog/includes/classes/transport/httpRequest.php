<?php
/*
  $Id: httpRequest.php v1.0 2013-01-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce.com

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
*/

class httpRequest {
  
  protected static $_methods = array('get' => HTTP_METH_GET,
                                     'post' => HTTP_METH_POST);
  /**
  * HTTP Request Transport 
  *  
  * @param array    $parameters The parameters to process
  * @access public      
  * @return mixed
  */ 
  public static function execute($parameters) {
    $h = new HttpRequest($parameters['server']['scheme'] . '://' . $parameters['server']['host'] . $parameters['server']['path'] . (isset($parameters['server']['query']) ? '?' . $parameters['server']['query'] : ''), static::$_methods[$parameters['method']], array('redirect' => 5));

    if ( $parameters['method'] == 'post' ) {
      $post_params = array();

      parse_str($parameters['parameters'], $post_params);

      $h->setPostFields($post_params);
    }

    $h->send();

    return $h->getResponseBody();
  }
  /**
  * Is HTTP Request available 
  *  
  * @access public      
  * @return boolean
  */
  public static function canUse() {
    return class_exists('HttpRequest');
  }
}
?>