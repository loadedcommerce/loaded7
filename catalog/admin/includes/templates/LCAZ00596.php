<?php
/**
  @package    catalog::admin::templates
  @author     AlgoZone, Inc.
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built by AlgoZone, Inc  
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: LCAZ00596.php v1.0 2013-11-26 algozone $
*/
class lC_Template_LCAZ00596 {
  var $_id,
      $_title = 'Toy Store Responsive Template',
      $_code = 'LCAZ00596',
      $_author_name = 'AlgoZone.com',
      $_author_www = 'http://www.algozone.com',
      $_markup_version = 'HTML 5.0',
      $_css_based = '1', /* 0=No; 1=Yes */
      $_medium = 'Responsive UI',
      $_screenshot = 'LCAZ00596.png',
      $_version = '1.0.1',
      $_compatibility = '7.002.2.0',      
      $_groups = array('boxes' => array('left', 'right'),
                       'content' => array('before', 'after')),
      $_keys;

  public function getID() {
    global $lC_Database;

    if (isset($this->_id) === false) {
      $Qtemplate = $lC_Database->query('select id from :table_templates where code = :code');
      $Qtemplate->bindTable(':table_templates', TABLE_TEMPLATES);
      $Qtemplate->bindvalue(':code', $this->_code);
      $Qtemplate->execute();

      $this->_id = $Qtemplate->valueInt('id');
    }

    return $this->_id;
  }

  public function getTitle() {
    return $this->_title;
  }

  public function getCode() {
    return $this->_code;
  }

  public function getAuthorName() {
    return $this->_author_name;
  }

  public function getAuthorAddress() {
    return $this->_author_www;
  }

  public function getMarkup() {
    return $this->_markup_version;
  }

  public function isCSSBased() {
    return ($this->_css_based == '1');
  }

  public function getMedium() {
    return $this->_medium;
  }

  public function getGroups($group) {
    return $this->_groups[$group];
  }

  public function getScreenshot() {
    return $this->_screenshot;
  }
  
  public function getVersion() {
    return $this->_version;
  }
    
  public function getCompatibility() {
    return $this->_compatibility;
  }   

  public function install() {
    global $lC_Database;

    $Qinstall = $lC_Database->query('insert into :table_templates (title, code, author_name, author_www, markup_version, css_based, medium) values (:title, :code, :author_name, :author_www, :markup_version, :css_based, :medium)');
    $Qinstall->bindTable(':table_templates', TABLE_TEMPLATES);
    $Qinstall->bindValue(':title', $this->_title);
    $Qinstall->bindValue(':code', $this->_code);
    $Qinstall->bindValue(':author_name', $this->_author_name);
    $Qinstall->bindValue(':author_www', $this->_author_www);
    $Qinstall->bindValue(':markup_version', $this->_markup_version);
    $Qinstall->bindValue(':css_based', $this->_css_based);
    $Qinstall->bindValue(':medium', $this->_medium);
    $Qinstall->execute();

    $id = $lC_Database->nextID();


      $data = array('categories' => array('*', 'left', '100'),
                    'best_sellers' => array(                                    
                                  array('index/product_listing', 'left', '200'),
                                  array('index/category_listing', 'left', '200'),
                                   array('search/results', 'left', '200')
                                  ),              
                  'mainpage_banner' => array('index/index', 'before', 300),       
                  'new_products' => array('index/index', 'after', 100),
                  'upcoming_products' => array('index/index', 'after', 450),
                  'also_purchased_products' => array('products/info', 'after', 100)
                  );
     
     
    $Qboxes = $lC_Database->query('select id, code from :table_templates_boxes');
    $Qboxes->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qboxes->execute();


    while ($Qboxes->next()) {
      $data_value = $data[$Qboxes->value('code')][0];
      if(($Qboxes->value('code') == "categories" || $Qboxes->value('code') == "manufacturers") && isset($data[$Qboxes->value('code')])){
        foreach($data[$Qboxes->value('code')] as $rows){
	      $Qrelation = $lC_Database->query('insert into :table_templates_boxes_to_pages 
                                  (templates_boxes_id, templates_id, content_page, boxes_group, sort_order, page_specific) values 
                                  (:templates_boxes_id, :templates_id, :content_page, :boxes_group, :sort_order, :page_specific)');
        $Qrelation->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
        $Qrelation->bindInt(':templates_boxes_id', $Qboxes->valueInt('id'));
        $Qrelation->bindInt(':templates_id', $id);
        $Qrelation->bindValue(':content_page', $rows[0]);
        $Qrelation->bindValue(':boxes_group', $rows[1]);
        $Qrelation->bindInt(':sort_order', $rows[2]);
        $Qrelation->bindInt(':page_specific', 0);
        
        
          
            $Qrelation->execute();
        }
      }
      else if (isset($data[$Qboxes->value('code')])) {
          
        $Qrelation = $lC_Database->query('insert into :table_templates_boxes_to_pages 
                                  (templates_boxes_id, templates_id, content_page, boxes_group, sort_order, page_specific) values 
                                  (:templates_boxes_id, :templates_id, :content_page, :boxes_group, :sort_order, :page_specific)');
        $Qrelation->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
        $Qrelation->bindInt(':templates_boxes_id', $Qboxes->valueInt('id'));
        $Qrelation->bindInt(':templates_id', $id);
        $Qrelation->bindValue(':content_page', $data[$Qboxes->value('code')][0]);
        $Qrelation->bindValue(':boxes_group', $data[$Qboxes->value('code')][1]);
        $Qrelation->bindInt(':sort_order', $data[$Qboxes->value('code')][2]);
        $Qrelation->bindInt(':page_specific', 0);
         
          
          
            $Qrelation->execute();
        }
      
    }
    
    //load banners for the template
    $data = array();
    $data[]  = array( 
      ':banners_title' =>  'HTML Banner 1',
      ':banners_url' =>  '',
      ':banners_image' => '',
      ':banners_group' => $this->_code . '_slider',
      ':banners_html_text' => '<p><img alt="" class="wp1_1 slide1_bot" src="templates/LCAZ00596/images/banner_slider_1.jpg" /></p>'
    );
    $data[]  = array( 
      ':banners_title' =>  'HTML Banner 2',
      ':banners_url' =>  '',
      ':banners_image' => '',
      ':banners_group' => $this->_code . '_slider',
      ':banners_html_text' => '<p><img alt="" class="wp1_1 slide1_bot" src="templates/LCAZ00596/images/banner_slider_2.jpg" /></p>'
    );
    $data[]  = array( 
      ':banners_title' =>  'HTML Banner 3',
      ':banners_url' =>  '',
      ':banners_image' => '',
      ':banners_group' => $this->_code . '_slider',
      ':banners_html_text' => '<p><img alt="" class="wp1_1 slide1_bot" src="templates/LCAZ00596/images/banner_slider_3.jpg" /></p>'
    );
    
    foreach ( $data as $record ) {
        $Qrelation = $lC_Database->query('insert into :banners (banners_title, banners_url, banners_target, banners_image, banners_group, banners_html_text, status) values (:banners_title, :banners_url, :banners_target, :banners_image, :banners_group, :banners_html_text, :status)');
        $Qrelation->bindTable(':banners', TABLE_BANNERS);
        $Qrelation->bindInt(':banners_target', 0);
        $Qrelation->bindInt(':status', 1);
        foreach($record as $key => $value) {
          $Qrelation->bindValue($key, $value);
        }         
        $Qrelation->execute();      
    }
    
    //load template language texts from template local XML file
    //????? -> Build simple code here similar to language core class to load language into system
    
  }

  public function remove() {
    global $lC_Database;

    $Qdel = $lC_Database->query('delete from :table_templates_boxes_to_pages where templates_id = :templates_id');
    $Qdel->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
    $Qdel->bindValue(':templates_id', $this->getID());
    $Qdel->execute();

    $Qdel = $lC_Database->query('delete from :table_templates where id = :id');
    $Qdel->bindTable(':table_templates', TABLE_TEMPLATES);
    $Qdel->bindValue(':id', $this->getID());
    $Qdel->execute();

    if ($this->hasKeys()) {
      $Qdel = $lC_Database->query('delete from :table_configuration where configuration_key in (":configuration_key")');
      $Qdel->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qdel->bindRaw(':configuration_key', implode('", "', $this->getKeys()));
      $Qdel->execute();
    }
  }

  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array();
    }

    return $this->_keys;
  }

  public function hasKeys() {
    static $has_keys;

    if (isset($has_keys) === false) {
      $has_keys = (sizeof($this->getKeys()) > 0) ? true : false;
    }

    return $has_keys;
  }

  public function isInstalled() {
    global $lC_Database;

    static $is_installed;

    if (isset($is_installed) === false) {
      $Qcheck = $lC_Database->query('select id from :table_templates where code = :code');
      $Qcheck->bindTable(':table_templates', TABLE_TEMPLATES);
      $Qcheck->bindValue(':code', $this->_code);
      $Qcheck->execute();

      $is_installed = ($Qcheck->numberOfRows()) ? true : false;
    }

    return $is_installed;
  }

  public function isActive() {
    return true;
  }
}
?>