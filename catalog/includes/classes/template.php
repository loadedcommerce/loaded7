<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: template.php v1.0 2013-08-08 datazen $
*/
include_once(DIR_FS_CATALOG . 'includes/classes/BarcodeQR.php');

class lC_Template {
  /**
  * Holds the template name value
  *
  * @var string
  * @access protected
  */
  protected $_template;
  /**
  * Holds the template selected name value
  *
  * @var string
  * @access protected
  */
  protected $_template_selected;
  /**
  * Holds the template ID value
  *
  * @var int
  * @access protected
  */
  protected $_template_id;
  /**
  * Holds the title of the page module
  *
  * @var string
  * @access protected
  */
  protected $_module; 
  /**
  * Holds the group name of the page
  *
  * @var string
  * @access protected
  */
  protected $_group;
  /**
  * Holds the title of the page
  *
  * @var string
  * @access protected
  */
  protected $_page_title;
  /**
  * Holds the image of the page
  *
  * @var string
  * @access protected
  */
  protected $_page_image;
  /**
  * Holds the filename of the content to be added to the page
  *
  * @var string
  * @access protected
  */
  protected $_page_contents;
  /**
  * Holds the meta tags of the page
  *
  * @var array
  * @access protected
  */
  protected $_page_tags = array('generator' => array('LoadedCommerce, Innovative eCommerce Solutions'));
  /**
  * Holds javascript filenames to be included in the page
  *
  * The javascript files must be plain javascript files without any PHP logic, and are linked to from the page
  *
  * @var array
  * @access protected
  */
  protected $_javascript_filenames = array();
  /**
  * Holds javascript PHP filenames to be included in the page
  *
  * The javascript PHP filenames can consist of PHP logic to produce valid javascript syntax, and is embedded in the page
  *
  * @var array
  * @access protected
  */
  protected $_javascript_php_filenames = array();
  /**
  * Holds blocks of javascript syntax to embedd into the page
  *
  * Each block must contain its relevant <script> and </script> tags
  *
  * @var array
  * @access protected
  */
  protected $_javascript_blocks = array();
  /**
  * Defines if the requested page has a wrapper
  *
  * @var boolean
  * @access protected
  */
  protected $_has_wrapper = true;    
  /**
  * Defines if the requested page has a header
  *
  * @var boolean
  * @access protected
  */
  protected $_has_header = true;
  /**
  * Defines if the requested page has a footer
  *
  * @var boolean
  * @access protected
  */
  protected $_has_footer = true;
  /**
  * Defines if the requested page has box modules
  *
  * @var boolean
  * @access protected
  */
  protected $_has_box_modules = true;
  /**
  * Defines if the requested page has content modules
  *
  * @var boolean
  * @access protected
  */
  protected $_has_content_modules = true;
  /**
  * Defines if the requested page should display any debug messages
  *
  * @var boolean
  * @access protected
  */
  protected $_show_debug_messages = true;
  /**
  * Setup the template class with the requested page module
  *
  * @param string $module The default page module to setup
  * @return object
  */
  public function &setup($module) {
    $group = basename($_SERVER['SCRIPT_FILENAME']);
    if (($pos = strrpos($group, '.')) !== false) {
      $group = substr($group, 0, $pos);
    }

    if (empty($_GET) === false) {
      $first_array = array_slice($_GET, 0, 1);
      
      $_module = lc_sanitize_string(basename(key($first_array)));
      
      if (file_exists('includes/content/' . $group . '/' . $_module . '.php')) {
        $module = $_module;
      }
    }

    include('includes/content/' . $group . '/' . $module . '.php');
    $_page_module_name = 'lC_' . ucfirst($group) . '_' . ucfirst($module);
    $object = new $_page_module_name();
    
    if ( isset($_GET['action']) && !empty($_GET['action']) ) {
      include('includes/classes/actions.php');

      lC_Actions::parse($_GET['action']);
    }

    return $object;
  }
  /**
  * Returns the template ID
  *
  * @access public
  * @return int
  */
  public function getID() {
    if (isset($this->_template) === false) {
      $this->set();
    }

    return $this->_template_id;
  }
  /**
  * Returns the template name
  *
  * @access public
  * @return string
  */
  public function getCode($id = null) {
    if (isset($this->_template) === false) {
      $this->set();
    }

    if (is_numeric($id)) {
      foreach ($this->getTemplates() as $template) {
        if ($template['id'] == $id) {
          return $template['code'];
        }
      }
    } else {
      return $this->_template;
    }
  }
  /**
  * Returns the page module name
  *
  * @access public
  * @return string
  */
  public function getModule() {
    return $this->_module;
  }
  /**
  * Returns the page group name
  *
  * @access public
  * @return string
  */
  public function getGroup() {
    return $this->_group;
  }
  /**
  * Returns the title of the page
  *
  * @access public
  * @return string
  */
  public function getPageTitle() {
    return lc_output_string_protected($this->_page_title);
  }
  /**
  * Returns the tags of the page separated by a comma
  *
  * @access public
  * @return string
  */
  public function getPageTags() {
    global $lC_Template, $_GET;

    $tag_string = '';
    $meta_title = $lC_Template->getBranding('meta_title') != '' ? $lC_Template->getBranding('meta_title') : STORE_NAME;
    $meta_title_prefix = $lC_Template->getBranding('meta_title_prefix') != '' ? $lC_Template->getBranding('meta_title_prefix') : '';
    $meta_title_suffix = $lC_Template->getBranding('meta_title_suffix') != '' ? $lC_Template->getBranding('meta_title_suffix') : '';
    $meta_delimeter = $lC_Template->getBranding('meta_delimeter') != '' ? $lC_Template->getBranding('meta_delimeter') : '';
    $meta_description = $lC_Template->getBranding('meta_description') != '' ? $lC_Template->getBranding('meta_description') : '';
    $meta_keywords = $lC_Template->getBranding('meta_keywords') != '' ? $lC_Template->getBranding('meta_keywords') : '';;


    if($this->_module == 'index' && isset($_GET['cpath']) && (empty($_GET['cpath']) === false) ){
      $tag_parts_title = $meta_title_prefix . $meta_delimeter . ($this->_page_title != '' ? $this->_page_title : $meta_title) . $meta_delimeter . $meta_title_suffix;
      $tag_parts_description = $meta_description;
      $tag_parts_keywords = ($this->_page_tags['keywords'] != '' ? implode(",", $this->_page_tags['keywords']) . ',' : '' ) . $meta_keywords;
    } else if($this->_group == 'products'){
      $tag_parts_title =  $meta_title_prefix . $meta_delimeter . ($this->_page_title != '' ? $this->_page_title : $meta_title) . $meta_delimeter . $meta_title_suffix ;
      $tag_parts_description .=  ($this->_page_tags['description'] != '' ? implode(",", $this->_page_tags['description']) . ' ' : '' ) .$meta_description ;
      $tag_parts_keywords =  ($this->_page_tags['keywords'] != '' ? implode(",", $this->_page_tags['keywords']) . ',' : '' ) . $meta_keywords ;
    } else {
      $tag_parts_title =  $meta_title_prefix . $meta_delimeter . $meta_title . $meta_delimeter . $meta_title_suffix;
      $tag_parts_description =  $meta_description ;
      $tag_parts_keywords =  $meta_keywords;
    }

    $tag_string .= '<title>' . $tag_parts_title . '</title>' . "\n";
    $tag_string .= '<meta name="description" content="' . $tag_parts_description . '">' . "\n";
    $tag_string .= '<meta name="keywords" content="' . $tag_parts_keywords . '">' . "\n";
    $tag_string .= '<meta name="generator" content="' . $this->_page_tags['generator'][0] . '">' . "\n";

    return $tag_string;
  }
  /**
  * Return the box modules assigned to the page
  *
  * @param string $group The group name of box modules to include that the template has provided
  * @return array
  */
  public function getBoxModules($group) {
    if (isset($this->lC_Modules_Boxes) === false) {
      $this->lC_Modules_Boxes = new lC_Modules('boxes');
    }
    
    return $this->lC_Modules_Boxes->getGroup($group);
  }
  /**
  * Return the content modules assigned to the page
  *
  * @param string $group The group name of content modules to include that the template has provided
  * @return array
  */
  public function getContentModules($group) {
    if (isset($this->lC_Modules_Content) === false) {
      $this->lC_Modules_Content = new lC_Modules('content');
    }
    
    return $this->lC_Modules_Content->getGroup($group);
  }
  /**
  * Returns the image of the page
  *
  * @access public
  * @return string
  */
  public function getPageImage() {
    return $this->_page_image;
  }
  /**
  * Returns the content filename of the page
  *
  * @access public
  * @return string
  */
  public function getPageContentsFilename() {
    return $this->_page_contents;
  }
  /**
  * Returns the javascript to link from or embedd to on the page
  *
  * @access public
  * @return string
  */
  public function getJavascript() {
    if (!empty($this->_javascript_filenames)) {
      echo $this->_getJavascriptFilenames();
    }

    if (!empty($this->_javascript_php_filenames)) {
      $this->_getJavascriptPhpFilenames();
    }

    if (!empty($this->_javascript_blocks)) {
      echo $this->_getJavascriptBlocks();
    }
  }
  /**
  * Return all templates in an array
  *
  * @access public
  * @return array
  */
  public function &getTemplates() {
    global $lC_Database;

    $templates = array();

    $Qtemplates = $lC_Database->query('select id, code, title from :table_templates');
    $Qtemplates->bindTable(':table_templates', TABLE_TEMPLATES);
    $Qtemplates->setCache('templates');
    $Qtemplates->execute();

    while ($Qtemplates->next()) {
      $templates[] = $Qtemplates->toArray();
    }

    $Qtemplates->freeResult();

    return $templates;
  }
  /**
  * Checks to see if the page has a title set
  *
  * @access public
  * @return boolean
  */
  public function hasPageTitle() {
    return !empty($this->_page_title);
  }
  /**
  * Checks to see if the page has a meta tag set
  *
  * @access public
  * @return boolean
  */
  public function hasPageTags() {
    return !empty($this->_page_tags);
  }
  /**
  * Checks to see if the page has javascript to link to or embedd from
  *
  * @access public
  * @return boolean
  */
  public function hasJavascript() {
    return (!empty($this->_javascript_filenames) || !empty($this->_javascript_php_filenames) || !empty($this->_javascript_blocks));
  }
  /**
  * Load the page specific CSS
  *
  * @access public
  * @return boolean
  */
  public function loadCSS($_code, $_group) {
    
    $html = '';
    if ( file_exists('templates/' . $_code . '/css/' . $_group . '.css') ) {
      $html = '<link rel="stylesheet" href="templates/' . $_code . '/css/' . $_group . '.css">';      
    }

    return $html;
  }
  /**
  * Checks to see if the page includes a wrapper
  *
  * @access public
  * @return boolean
  */
  public function hasPageWrapper() {
    return $this->_has_wrapper;
  }    
  /**
  * Checks to see if the page has a footer defined
  *
  * @access public
  * @return boolean
  */
  public function hasPageFooter() {
    return $this->_has_footer;
  }
  /**
  * Checks to see if the page has a header defined
  *
  * @access public
  * @return boolean
  */
  public function hasPageHeader() {
    return $this->_has_header;
  }
  /**
  * Checks to see if the page has content modules defined
  *
  * @access public
  * @return boolean
  */
  public function hasPageContentModules() {
    return $this->_has_content_modules;
  }
  /**
  * Checks to see if the page has box modules defined
  *
  * @access public
  * @return boolean
  */
  public function hasPageBoxModules() {
    return $this->_has_box_modules;
  }
  /**
  * Checks to see if the page show display debug messages
  *
  * @access public
  * @return boolean
  */
  public function showDebugMessages() {
    return $this->_show_debug_messages;
  }
  /**
  * Sets the template to use
  *
  * @param string $code The code of the template to use
  * @access public
  */
  public function set($code = null) {
    // much explanation is needed to follow the following rules/switches for template setting on page loads
    if ( (isset($_SESSION['template']) === false) || // template session is not set
          !empty($code) || // a template code has been sent to this function
          (isset($_GET['template']) && !empty($_GET['template'])) || // a template selection came from the url $_GET
          isset($_SESSION['template']) && $_SESSION['template']['code'] != DEFAULT_TEMPLATE ) // the session template is not the same as in the database
    {
      // one of the above triggered the function into action, continue
      if ( !empty( $code ) ) { 
        // someone sent a template code to this function
        $set_template = $code;
      } else if (isset($_GET['template']) && !empty($_GET['template'])) { 
        // no code sent and a template code was in the url $_GET
        $set_template = $_GET['template'];
        $set_template_selected = $_GET['template'];
        $_SESSION['template']['selected'] = $set_template_selected;
        $this->_template_selected = $set_template_selected;
      } else if (isset($_SESSION['template']['selected']) && $_SESSION['template']['selected'] != '') { 
        // no code sent, no $_GET and the session for selected template is set
        $set_template = $_SESSION['template']['selected'];
        $set_template_selected = $_SESSION['template']['selected'];
      } else {
        // set the template from the database default template setting
        $set_template = DEFAULT_TEMPLATE;
      }
      
      // if someone clears the template selection we reset from the database default template setting
      if (isset($_SESSION['template']['selected']) && $_SESSION['template']['selected'] == 'reset') {
        $_SESSION['template']['selected'] = null;
      }
      
      $data = array();
      $data_default = array();

      foreach ($this->getTemplates() as $template) { 
        // for each template we check some tings
        if ($template['code'] == DEFAULT_TEMPLATE) { 
          // if the code of the template matches DEFAULT_TEMPLATE we set the $default_data array in case it's needed
          $data_default = array('id' => $template['id'], 'code' => $template['code'], 'selected' => $set_template_selected);
        } elseif ($template['code'] == $set_template) { 
          // if the code of the template does not match DEFAULT_TEMPLATE we set the $data array for use
          $data = array('id' => $template['id'], 'code' => $set_template, 'selected' => $set_template_selected);
        }
      }

      // if the template to set is not found in the database we fallback to default
      if (empty($data)) { 
        $data = $data_default;
      }

      // set the session with the template data result
      $_SESSION['template'] = $data;
    }

    $this->_template_id = $_SESSION['template']['id'];
    $this->_template = $_SESSION['template']['code'];
  }
  /**
  * Sets the title of the page
  *
  * @param string $title The title of the page to set to
  * @access public
  */
  public function setPageTitle($title) {
    $this->_page_title = $title;
  }
  /**
  * Sets the image of the page
  *
  * @param string $image The image of the page to set to
  * @access public
  */
  public function setPageImage($image) {
    $this->_page_image = $image;
  }
  /**
  * Sets the content of the page
  *
  * @param string $filename The content filename to include on the page
  * @access public
  */
  public function setPageContentsFilename($filename) {
    $this->_page_contents = $filename;
  }
  /**
  * Adds a tag to the meta keywords array
  *
  * @param string $key The keyword for the meta tag
  * @param string $value The value for the meta tag using the key
  * @access public
  */
  public function addPageTags($key, $value) {
    $this->_page_tags[$key][] = $value;
  }
  /**
  * Adds a javascript file to link to
  *
  * @param string $filename The javascript filename to link to
  * @access public
  */
  public function addJavascriptFilename($filename) {
    $this->_javascript_filenames[] = $filename;
  }
  /**
  * Adds a PHP based javascript file to embedd on the page
  *
  * @param string $filename The PHP based javascript filename to embedd
  * @access public
  */
  public function addJavascriptPhpFilename($filename) {
    $this->_javascript_php_filenames[] = $filename;
  }
  /**
  * Adds javascript logic to the page
  *
  * @param string $javascript The javascript block to add on the page
  * @access public
  */
  public function addJavascriptBlock($javascript) {
    $this->_javascript_blocks[] = $javascript;
  }
  /**
  * Returns the javascript filenames to link to on the page
  *
  * @access private
  * @return string
  */
  private function _getJavascriptFilenames() {
    $js_files = '';

    foreach ($this->_javascript_filenames as $filenames) {
      $js_files .= '<script src="' . $filenames . '"></script>' . "\n";
    }

    return $js_files;
  }
  /**
  * Returns the PHP javascript files to embedd on the page
  *
  * @access private
  */
  private function _getJavascriptPhpFilenames() {
    foreach ($this->_javascript_php_filenames as $filename) {
      if ( file_exists($filename) ) {
        include($filename);
      }
    }
  }
  /**
  * Returns javascript blocks to add to the page
  *
  * @access private
  * @return string
  */
  private function _getJavascriptBlocks() {
    return implode("\n", $this->_javascript_blocks);
  }
  /**
  * Returns OGP tags to add to the page head
  *
  * @access private
  * @return string
  */
  public function getPageOGPTags() {
    $tag_string = '';
    if (!is_array($this->_ogp_tags)) return $tag_string;     
    foreach ($this->_ogp_tags as $key => $values) {
        for ($i=0; $i<=sizeof($values); $i++){
            if(!empty($values[$i])){
                $tag_string .= '<meta property="og:' . $key . '" content="' . str_replace(array("\r\n", "\r", "\n"), "", $values[$i]) . '" />' . "\n";
            }
        }
    }
    return $tag_string . "\n";
  }
  /**
  * Returns OGP Image array for OGP tags generation
  *
  * @access private
  * @return string
  */
  public function addOGPTags($key, $value) {
    $this->_ogp_tags[$key][] = $value;
  }    
  /**
  * Returns QR Code output
  *
  * @access private
  * @return string
  */  
  public function getQRCode() {
    global $lC_Customer, $lC_Session, $lC_Language;
    
    $BarcodeQR = new BarcodeQR();
    $qrcode_url = (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . $_SERVER['REQUEST_URI'];

    if ($lC_Customer->isLoggedOn() === true && $lC_Customer->getEmailAddress != NULL ) {
      $qrcode_url_add = (stristr($qrcode_url, "?") ? '&' : '?') . $lC_Session->getName() . '=' . $lC_Session->getID() . '&email=' . $lC_Customer->getEmailAddress  . '&qr=1';
    } else {
      $qrcode_url_add = (stristr($qrcode_url, "?") ? '&' : '?') . $lC_Session->getName() . '=' . $lC_Session->getID();
    } 
    
    $output = '<a id="qrcode-tooltip">' .
              '  <span style="cursor:pointer;">' .
              '    <img src="images/icons/qr-icon.png" alt="' . $lC_Language->get('text_click_and_scan')  . '" style="vertical-align:middle; padding-right:6px;" /><span class="small-margin-left">' . $lC_Language->get('text_click_and_scan') . '</span>' .
              '  </span>' .
              '</a>' . 
              '<div id="qr-message">' . 
              '<a class="close-qr" title="Hide message" onclick="$(\'#qr-message\').hide(\'500\');"><span style="color:#fff;">X</span></a>';
    
    $BarcodeQR->url($qrcode_url . $qrcode_url_add);
    if ($lC_Customer->isLoggedOn() === true) {
      $BarcodeQR->draw(230, DIR_FS_WORK . 'qrcode/c' .  $lC_Customer->id . '.png');
      $output .= '<img alt="' . $lC_Language->get('text_click_and_scan') . '" src="includes/work/qrcode/c' . $lC_Customer->id . '.png" />';      
    } else {
      $BarcodeQR->draw(230, DIR_FS_WORK . 'qrcode/g' .  $lC_Session->getID() . '.png');
      $output .= '<img alt="' . $lC_Language->get('text_click_and_scan') . '" src="includes/work/qrcode/g' . $lC_Session->getID() . '.png" />';
    }   
    $output .= '</div><script>$("#qrcode-tooltip").click(function() { $("#qr-message").show("500"); });</script>';
    
    return $output;
  }
 /*
  * Return the language selection 
  *
  * @access public
  * @return array
  */  
  public function getLanguageSelection($include_image = true, $include_name = false, $params = '') {
    global $lC_Language;
    
    $text = '';
    $output = '';
    foreach ($lC_Language->getAll() as $value) {
      if ($include_name === true && $include_image === true) {
        $text = $value['name'] . ' ' . $lC_Language->showImage($value['code']);
      } else if ($include_name === true && $include_image === false) {
        $text = $value['name'];
      } else {
        $text = $lC_Language->showImage($value['code'], null, null, $params);
      }
      $output .= '<li>' . lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('language', 'currency')) . '&language=' . $value['code'], 'AUTO'), $text) . '</li>';
    }
    
    return $output;
  }
 /*
  * return the top cats for nav
  *
  * @access public
  * @return array
  */
  public function getTopCategories() {
    global $lC_Database, $lC_Language;
    
    $Qcategories = $lC_Database->query('select c.categories_id, cd.categories_name, cd.categories_menu_name, c.categories_link_target, c.categories_custom_url, c.categories_mode from :table_categories c, :table_categories_description cd where c.parent_id = 0 and c.categories_id = cd.categories_id and cd.language_id = :language_id and c.categories_status = 1 and c.categories_visibility_nav = 1 order by sort_order, cd.categories_name');
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcategories->bindInt(':language_id', $lC_Language->getID());
    $Qcategories->execute();
    while ( $Qcategories->next() ) {
      $topCategories[] = array('id' => $Qcategories->value('categories_id'),
                               'name' => ($Qcategories->value('categories_menu_name') != '') ? $Qcategories->value('categories_menu_name') : $Qcategories->value('categories_name'),
                               'link_target' => $Qcategories->value('categories_link_target'),
                               'custom_url' => $Qcategories->value('categories_custom_url'),
                               'mode' => $Qcategories->value('categories_mode'));
    }
    
    return $topCategories;   
  } 
 /*
  * Get the top category selection for nav
  *
  * @access public
  * @return array
  */
  public function getTopCategoriesSelection() {
    $output = '';
    if (is_array($this->getTopCategories())) {
      foreach ($this->getTopCategories() as $menuItem) {
        if ($menuItem['custom_url'] != '') {
          if ($menuItem['mode'] == 'override') {
            $output.= '<li><a href="' . $menuItem['custom_url'] . '"' . (($menuItem['target'] != '') ? ' target="_blank"' : '') . '>' . $menuItem['name'] . '</a></li>';
          } else {
           
            // Session bug fix
            $link = lc_href_link($menuItem['custom_url'], '', 'NONSSL');
            if(substr_count($link, '?') > 1){

              $link = str_replace('?lCsid', '&lCsid', $link);
            }

            $output.= '<li><a href="' . $link . '"' . (($menuItem['target'] != '') ? ' target="_blank"' : '') . '>' . $menuItem['name'] . '</a></li>';
          }
        } else {
          $output .= '<li><a href="' . lc_href_link(FILENAME_DEFAULT, 'cPath=' . $menuItem['id'], 'NONSSL') . '"' . (($menuItem['target'] != '') ? ' target="_blank"' : '') . '>' . $menuItem['name'] . '</a></li>';
        }
      }
    }
    
    return $output;    
  } 
 /*
  * Return the currency selection form
  *
  * @access public
  * @return array
  */  
  public function getCurrencySelectionForm() {
    global $lC_Currencies;
    
    $output = '<form id="currencies" name="currencies" action="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), null, 'AUTO', false) . '" method="get">' . 
              '  <select name="currency" id="currency" onchange="this.form.submit();">';

    $currency_data = array();
    foreach ($lC_Currencies->currencies as $key => $value) {
      $currency_data[] = array('id' => $key, 'text' => $value['title']);
    }
    foreach ($currency_data as $currencies) {
      $output .= '<option value="' . $currencies['id'] . '"' . ($_SESSION['currency'] == $currencies['id'] ? 'selected="selected"' : null) . '>' . $currencies['text'] . '</option>';
    }
    $output .= '</select>' . lc_draw_hidden_session_id_field() . '</form>';
    
    return $output;    
  }
 /*
  * Return the minicart dropdown selection
  *
  * @access public
  * @return array
  */
  public function getMiniCartSelection() {
    global $lC_ShoppingCart, $lC_Language, $lC_Image, $lC_Currencies;
      
    $output = '';
    if ($lC_ShoppingCart->hasContents()) {
      $output .= '<a class="minicart_link" onclick="toggleMiniCart();">' . 
                 '  <span class="item"><b>' . $lC_ShoppingCart->numberOfItems() . '</b> ' . ($lC_ShoppingCart->numberOfItems() > 1 ? strtoupper($lC_Language->get('text_cart_items')) : strtoupper($lC_Language->get('text_cart_item'))) . ' /</span> <span class="price"><b>' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</b></span>' .
                 '</a>' .
                 '<div class="cart_drop">' .
                 '  <span class="dark"></span>' .
                 '  <ul>';

      foreach ($lC_ShoppingCart->getProducts() as $products) {
        $output .= '<li>' . $lC_Image->show($products['image'], $products['name'], null, 'mini') . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), '(' . $products['quantity'] . ') x ' . $products['name']) . ' <span class="price">' . $lC_Currencies->format($products['price']) . '</span></li>';
      }           

      $output .= '  </ul>' .
                 '  <div class="cart_bottom">' .
                 '    <div class="subtotal_menu">' .
                 '      <small>' . $lC_Language->get('box_shopping_cart_subtotal') . '</small>' .
                 '      <big>' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</big>' .
                 '    </div>' .
                 '    <a href="' . lc_href_link(FILENAME_CHECKOUT, 'shopping_cart', 'SSL') . '">' . $lC_Language->get('button_view_cart') . '</a>' .
                 '  </div>' .
                 '</div>';
                 
      $output .= '<script> function toggleMiniCart() { var isOpen = $(".cart_drop").is(":visible"); if (isOpen) { $(".cart_drop").slideUp(300); } else { $(".cart_drop").slideDown(300); }}</script>';
      
    } else {
      $output .= $lC_Language->get('box_shopping_cart_empty');
    }    
    
    return $output;   
  }
  
  public function getInfoBoxHtml($group) {
    global $lC_Vqmod;
    
    $content = '';
    if ($this->hasPageBoxModules()) {
      ob_start();
      foreach ($this->getBoxModules($group) as $box) {
        $lC_Box = new $box();
        $lC_Box->initialize();
        if ($lC_Box->hasContent()) {
          if ($this->getCode() == DEFAULT_TEMPLATE) {
            include($lC_Vqmod->modCheck('templates/' . $this->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
          } else {
            if (file_exists('templates/' . $this->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php')) {
              include($lC_Vqmod->modCheck('templates/' . $this->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
            } else {
              include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
            }
          }
        }
        unset($lC_Box);
      }
      $content = ob_get_contents();
      ob_end_clean();  
    }  
    
    return $content;
  } 
  
  
 /*
  * return the Branding Data
  *
  * @access public
  * @return array
  */
  public function getBranding($data) {
    global $lC_Database, $lC_Language;

    $QbrandingLangData = $lC_Database->query('select * from :table_branding where language_id = :language_id');
    $QbrandingLangData->bindTable(':table_branding', TABLE_BRANDING);
    $QbrandingLangData->bindInt(':language_id', $lC_Language->getID());
    $QbrandingLangData->execute();
    
    $QbrandingData = $lC_Database->query('select * from :table_branding_data');
    $QbrandingData->bindTable(':table_branding_data', TABLE_BRANDING_DATA);
    $QbrandingData->execute();

    switch($data){
      case 'site_image':
      $data = $QbrandingData->value('site_image');
      break;
      
      case 'og_image':
      $data = $QbrandingData->value('og_image');
      break;
      
      case 'chat_code':
      $data = $QbrandingData->value('chat_code');
      break;
      
      case 'support_phone':
      $data = $QbrandingData->value('support_phone');
      break;
      
      case 'support_email':
      $data = $QbrandingData->value('support_email');
      break;
      
      case 'sales_phone':
      $data = $QbrandingData->value('sales_phone');
      break;
      
      case 'sales_email':
      $data = $QbrandingData->value('sales_email');
      break;
      
      case 'meta_delimeter':
      $data = $QbrandingData->value('meta_delimeter');
      break;
      
      case 'social_facebook_page':
      $data = $QbrandingData->value('social_facebook_page');
      break;
      
      case 'social_tweeter':
      $data = $QbrandingData->value('social_twitter');
      break;
      
      case 'social_pinterest':
      $data = $QbrandingData->value('social_pinterest');
      break;
      
      case 'social_google_plus':
      $data = $QbrandingData->value('social_google_plus');
      break;
      
      case 'social_youtube':
      $data = $QbrandingData->value('social_youtube');
      break;
      
      case 'social_linkedin':
      $data = $QbrandingData->value('social_linkedin');
      break;

      case 'meta_delimeter':
      $data = $QbrandingData->value('meta_delimeter');
      break;
      
      case 'slogan':
      $data = $QbrandingLangData->value('slogan');
      break;
      
      case 'meta_description':
      $data = $QbrandingLangData->value('meta_description');
      break;
      
      case 'meta_keywords':
      $data = $QbrandingLangData->value('meta_keywords');
      break;
      
      case 'meta_title':
      $data = $QbrandingLangData->value('meta_title');
      break;
      
      case 'meta_title_prefix':
      $data = $QbrandingLangData->value('meta_title_prefix');
      break;
      
      case 'meta_title_suffix':
      $data = $QbrandingLangData->value('meta_title_suffix');
      break;
      
      case 'footer_text':
      $data = $QbrandingLangData->value('footer_text');
      break;
    }
    return $data;
  }
}
?>