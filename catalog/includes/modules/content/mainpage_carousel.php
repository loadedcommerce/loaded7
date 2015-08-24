<?php
/**
  @package    catalog::modules::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: mainpage_carousel.php v1.0 2013-08-08 datazen $
*/
class lC_Content_mainpage_carousel extends lC_Modules {
 /* 
  * Public variables 
  */  
  public $_title,
         $_code = 'mainpage_carousel',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /* 
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;           

    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');      
    
    $this->_title = $lC_Language->get('Mainpage Carousel');
  }
 /*
  * Returns the also puchased HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Database, $lC_Language, $lC_Image, $lC_carousel, $lC_Services;
    $caption_enabled = (defined('MODULE_CONTENT_MAINPAGE_CAROUSEL_CAPTION') && @constant('MODULE_CONTENT_MAINPAGE_CAROUSEL_CAPTION') == '1') ? true : false;
    $indicator_enabled = (defined('MODULE_CONTENT_MAINPAGE_CAROUSEL_INDICATOR') && @constant('MODULE_CONTENT_MAINPAGE_CAROUSEL_INDICATOR') == '1') ? true : false;
    
    if ($lC_Services->isStarted('banner') ) {
        
        $Qbanner = $lC_Database->query('select * from :table_banners where status = 1 and banners_group = :banners_group');
        $Qbanner->bindTable(':table_banners', TABLE_BANNERS);
        $Qbanner->bindValue(':banners_group', 'banner_slider');
        $Qbanner->execute();
        
        $this->_content .= '<div id="CarouselModule" class="carousel slide" data-ride="carousel">';
        $this->_content .= '<div class="carousel-inner" role="listbox">';
        $n=0;
        while ( $Qbanner->next() ) {
            $this->_content .= '<div class="item ' . ($n==0 ? 'active':'') . '">';
            $this->_content .= lc_link_object(lc_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $Qbanner->valueInt('banners_id')), lc_image(DIR_WS_IMAGES . $Qbanner->value('banners_image'), $Qbanner->value('banners_title')), ($Qbanner->valueInt('banners_target')===1)  ?   ' target="_blank" '  :  ' target="_self" ');
            if($caption_enabled === true ){
                $this->_content .= '<div class="carousel-caption">' . $Qbanner->value('banners_title') . '</div>';
           }
            $this->_content .= '</div>';
            $n++;
        }
        $this->_content .= '</div>';
        if ($Qbanner->numberOfRows() > 0) {
        $this->_content .= '<ol class="carousel-indicators">';
        for( $i=0; $i<$n; $i++ ){
            $this->_content .= '<li data-target="#CarouselModule" data-slide-to="' .$i .'"' . ($n==0 ? ' class="active"':'') . '></li>';
        }    
        $this->_content .= '</ol>';

        $this->_content .= '  <a class="left carousel-control" href="#CarouselModule" role="button" data-slide="prev">';
        $this->_content .= '    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>';
        $this->_content .= '    <span class="sr-only">Previous</span>';
        $this->_content .= '  </a>';
        $this->_content .= '  <a class="right carousel-control" href="#CarouselModule" role="button" data-slide="next">';
        $this->_content .= '    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
        $this->_content .= '    <span class="sr-only">Next</span>';
        $this->_content .= '  </a>';
        }
        $this->_content .= '</div>';
    }
  }
  
  public function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable Carousel Indicators', 'MODULE_CONTENT_MAINPAGE_CAROUSEL_INDICATOR', '1', 'Enable Carousel Indicators to navigate directly to a banner.', '6', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable Carousel Image Caption', 'MODULE_CONTENT_MAINPAGE_CAROUSEL_CAPTION', '1', 'ENable Carousel Image Captions retrived from title of banner. ', '6', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
  }
 /*
  * Return the module keys
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_CONTENT_MAINPAGE_CAROUSEL_INDICATOR', 'MODULE_CONTENT_MAINPAGE_CAROUSEL_CAPTION');
    }

    return $this->_keys;
  }
}
?>