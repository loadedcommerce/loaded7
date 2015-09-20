<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: category.php v1.0 2013-08-08 datazen $
*/

/**
 * The lC_Category class manages category information
 */

  class lC_Category {

/**
 * An array containing the category information
 *
 * @var array
 * @access private
 */

    private $_data = array();

/**
 * Constructor
 *
 * @param int $id The ID of the category to retrieve information from
 * @access public
 */

    public function __construct($id) {
      global $lC_CategoryTree;

      if ( $lC_CategoryTree->exists($id) ) {
        $this->_data = $lC_CategoryTree->getData($id);
      }
    }

/**
 * Return the ID of the assigned category
 *
 * @access public
 * @return integer
 */

    public function getID() {
      return $this->_data['id'];
    }

/**
 * Return the title of the assigned category
 *
 * @access public
 * @return string
 */

    public function getTitle() {
      return $this->_data['name'];
    }

/**
 * Return the meta title of the assigned category
 *
 * @access public
 * @return string
 */

    public function getMetaTitle() {
      return $this->_data['meta_title'];
    }

/**
 * Return the meta description of the assigned category
 *
 * @access public
 * @return string
 */

    public function getMetaDescription() {
      return $this->_data['meta_description'];
    }

/**
 * Return the meta keywords of the assigned category
 *
 * @access public
 * @return string
 */

    public function getMetaKeywords() {
      return $this->_data['meta_keywords'];
    }

/**
 * Check if the category has an image
 *
 * @access public
 * @return string
 */

    public function hasImage() {
      return ( !empty($this->_data['image']) );
    }

/**
 * Return the image of the assigned category
 *
 * @access public
 * @return string
 */

    public function getImage() {
      return $this->_data['image'];
    }

/**
 * Check if the assigned category has a parent category
 *
 * @access public
 * @return boolean
 */

    public function hasParent() {
      return ( $this->_data['parent_id'] > 0 );
    }

/**
 * Return the parent ID of the assigned category
 *
 * @access public
 * @return integer
 */

    public function getParent() {
      return $this->_data['parent_id'];
    }

/**
 * Return the breadcrumb path of the assigned category
 *
 * @access public
 * @return string
 */

    public function getPath() {
      global $lC_CategoryTree;

      return $lC_CategoryTree->buildBreadcrumb($this->_data['id']);
    }

/**
 * Return specific information from the assigned category
 *
 * @access public
 * @return mixed
 */

    public function getData($keyword) {
      return $this->_data[$keyword];
    }
  }
?>
