<?php
/*
  $Id: mainpage_banner.php v1.0 2011-11-04 maestro $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Content_mainpage_banner extends lC_Modules {
 /* 
  * Public variables 
  */  
  public $_title,
         $_code = 'mainpage_banner',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /* 
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;           

    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');      
    
    $this->_title = $lC_Language->get('mainpage_banner_title');
  }
 /*
  * Returns the also puchased HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Database, $lC_Language, $lC_Image, $lC_Banner, $lC_Services;
    
    if ($lC_Services->isStarted('banner') && $lC_Banner->exists('mainpage')) {
      $this->_content = '<p align="center">' . $lC_Banner->display() . '</p>';
    }
  }
}
?>