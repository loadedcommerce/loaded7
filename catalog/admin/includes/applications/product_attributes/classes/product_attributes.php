<?php
/*
  $Id: product_attrbutes.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Product_attributes_Admin class manages product attributes
*/
abstract class lC_Product_attributes_Admin {
 /*
  * Protected variables
  */
  protected $_title;
 /*
  * Declares abstract function to be extended through inheritance
  *
  * @access public
  * @return null
  */
  abstract public function setFunction($value);
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/product_attributes/' . $this->getCode() . '.php');

    $this->_title = $lC_Language->get('product_attributes_' . $this->getCode() . '_title');
  }
 /*
  * Returns the addons modules datatable data for listings
  *
  * @access public
  * @return array
  */
  public function getAll() {
    global $lC_Language;

    $media = $_GET['media'];
    
    $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/product_attributes');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $files = $lC_DirectoryListing->getFiles();

    $cnt = 0;
    $result = array('aaData' => array());
    $installed_modules = array();
    foreach ( $files as $file ) {
      include('includes/modules/product_attributes/' . $file['name']);
      $class = substr($file['name'], 0, strrpos($file['name'], '.'));
      if ( class_exists('lC_ProductAttributes_' . $class) ) {
        $module = 'lC_ProductAttributes_' . $class;
        $module = new $module();
        $name = '<td>' . $module->getTitle() . '</td>';
        $action = '<td class="align-right vertical-center"><span class="button-group compact">';
        if ( $module->isInstalled() ) {
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? '#' : 'javascript://" onclick="uninstallModule(\'' . $module->getCode() . '\', \'' . urlencode($module->getTitle()) . '\')') . '" class="button icon-minus-round icon-red' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_uninstall')) . '</a>';
        } else {
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? '#' : 'javascript://" onclick="installModule(\'' . $module->getCode() . '\', \'' . urlencode($module->getTitle()) . '\')') . '" class="button icon-plus-round icon-green' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('button_install')) . '</a>';
        }
        $action .= '</span></td>';
        $result['aaData'][] = array("$name", "$action");
        $cnt++;
      }
    }
    $result['total'] = $cnt;

    return $result;
  }
 /*
  * Return the product attribute ID
  *
  * @param string $this->getCode() The product attribute code
  * @access public
  * @return integer
  */
  public function getID() {
    global $lC_Database;

    $Qmodule = $lC_Database->query('select id from :table_templates_boxes where code = :code and modules_group = :modules_group');
    $Qmodule->bindTable(':table_templates_boxes');
    $Qmodule->bindValue(':code', $this->getCode());
    $Qmodule->bindValue(':modules_group', 'product_attributes');
    $Qmodule->execute();

    return ( $Qmodule->numberOfRows() === 1 ) ? $Qmodule->valueInt('id') : 0;
  }
 /*
  * Return the product attribute code
  *
  * @access public
  * @return string
  */
  public function getCode() {
    return substr(get_class($this), 21);
  }
 /*
  * Return the product attribute title
  *
  * @param string $this->_title The product attribute title
  * @access public
  * @return string
  */
  public function getTitle() {
    return $this->_title;
  }
 /*
  * Check to see if attribute module is installed
  *
  * @param string $this->getID() The product attribute module ID
  * @access public
  * @return integer
  */
  public function isInstalled() {
    return ($this->getID() > 0);
  }
 /*
  * Install the product attribute module
  *
  * @param string $module The product attribute module name
  * @access public
  * @return array
  */
  public function install($module) {
    $result = array();
    if ( file_exists('includes/modules/product_attributes/' . $module . '.php') ) {
      include('includes/modules/product_attributes/' . $module . '.php');
      if ( class_exists('lC_ProductAttributes_' . $module) ) {
        $module = 'lC_ProductAttributes_' . $module;
        $module = new $module();
        if ( $module->_install($module->getTitle(), $module->getCode()) ) {
        } else {
          $result['rpcStatus'] = -1;
        }
      }
    }
    return $result;
  }
 /*
  * Uninstall the product attribute module
  *
  * @param string $module The product attribute module name
  * @access public
  * @return array
  */
  public function uninstall($module) {
    $result = array();
    if ( file_exists('includes/modules/product_attributes/' . $module . '.php') ) {
      include('includes/modules/product_attributes/' . $module . '.php');
      if ( class_exists('lC_ProductAttributes_' . $module) ) {
        $module = 'lC_ProductAttributes_' . $module;
        $module = new $module();
        if ( $module->_uninstall($module->getID(), $module->getCode()) ) {
        } else {
          $result['rpcStatus'] = -1;          }
      }
    }
    return $result;
  }
 /*
  * Install the product attribute module
  *
  * @param string $_title The product attribute module title
  * @param string $_code The product attribute module code
  * @access private
  * @return boolean
  */
  private function _install($_title, $_code) {
    global $lC_Database;

    $Qinstall = $lC_Database->query('insert into :table_templates_boxes (title, code, author_name, author_www, modules_group) values (:title, :code, :author_name, :author_www, :modules_group)');
    $Qinstall->bindTable(':table_templates_boxes');
    $Qinstall->bindValue(':title', $_title);
    $Qinstall->bindValue(':code', $_code);
    $Qinstall->bindValue(':author_name', '');
    $Qinstall->bindValue(':author_www', '');
    $Qinstall->bindValue(':modules_group', 'product_attributes');
    $Qinstall->execute();

    return ( $lC_Database->isError() === false );
  }
 /*
  * Install the product attribute module
  *
  * @param string $_id The product attribute module ID
  * @param string $_code The product attribute module code
  * @access private
  * @return boolean
  */
  private function _uninstall($_id, $_code) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    $Qdelete = $lC_Database->query('delete from :table_product_attributes where id = :id');
    $Qdelete->bindTable(':table_product_attributes');
    $Qdelete->bindInt(':id', $_id);
    $Qdelete->execute();

    if ( $lC_Database->isError() ) {
      $error = true;
    }

    if ( $error === false ) {
      $Quninstall = $lC_Database->query('delete from :table_templates_boxes where code = :code and modules_group = :modules_group');
      $Quninstall->bindTable(':table_templates_boxes');
      $Quninstall->bindValue(':code', $_code);
      $Quninstall->bindValue(':modules_group', 'product_attributes');
      $Quninstall->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();
    } else {
      $lC_Database->rollbackTransaction();
    }

    return ( $error === false );
  }
}
?>
