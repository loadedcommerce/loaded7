<?php
/*
  $Id: controller.php v1.0 2013-04-20 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class Test_Addon_One {
  /*
  * Protected variables
  */
  protected $_type = 'payment',
            $_title = 'Loaded Payments',
            $_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. Sed lectus.',
            $_rating = '5',
            $_author = 'Loaded Commerce, LLC',
            $_thumbnail = 'p1.png',
            $_version = '1.01',
            $_enabled = true,
            $_valid = true;
  /*
  * Class constructor
  */
  public function __construct() {
  }
  /*
  * Class methods
  */
  public function isEnabled() {
    return $this->_enabled;
  }  
  
  public function isValid() {
    return $this->_valid;
  }  
  
  public function getAddonType() {
    return $this->_type;
  }
  
  public function getAddonTitle() {
    return $this->_title;
  }  
  
  public function getAddonDescription() {
    return $this->_description;
  }  
  
  public function getAddonRating() {
    return $this->_rating;
  }  

  public function getAddonAuthor() {
    return $this->_author;
  }  
  
  public function getAddonThumbnail() {
    return $this->_thumbnail;
  }  
  
  public function getAddonVersion() {
    return $this->_version;
  }  
}
?>