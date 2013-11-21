<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: httpRequest.php v1.0 2013-08-08 datazen $
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