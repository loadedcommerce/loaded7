<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: image.php v1.0 2013-08-08 datazen $
*/
class lC_Image {
  var $_groups;

  public function lC_Image() {
    global $lC_Database, $lC_Language;

    $this->_groups = array();

    $Qgroups = $lC_Database->query('select * from :table_products_images_groups where language_id = :language_id');
    $Qgroups->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
    $Qgroups->bindInt(':language_id', $lC_Language->getID());
    $Qgroups->setCache('images_groups-' . $lC_Language->getID());
    $Qgroups->execute();

    while ($Qgroups->next()) {
      $this->_groups[$Qgroups->valueInt('id')] = $Qgroups->toArray();
    }

    $Qgroups->freeResult();
  }

  public function getID($code) {
    foreach ($this->_groups as $group) {
      if ($group['code'] == $code) {
        return $group['id'];
      }
    }

    return 0;
  }

  public function getCode($id) {
    return $this->_groups[$id]['code'];
  }

  public function getWidth($code) {
    return $this->_groups[$this->getID($code)]['size_width'];
  }

  public function getHeight($code) {
    return $this->_groups[$this->getID($code)]['size_height'];
  }

  public function exists($code) {
    return isset($this->_groups[$this->getID($code)]);
  }

  public function show($image, $title, $parameters = '', $group = '') {
    if (empty($group) || !$this->exists($group)) {
      $group = $this->getCode(DEFAULT_IMAGE_GROUP_ID);
    }

    $group_id = $this->getID($group);

    $width = $height = '';

    if ( ($this->_groups[$group_id]['force_size'] == '1') || empty($image) ) {
      $width = $this->_groups[$group_id]['size_width'];
      $height = $this->_groups[$group_id]['size_height'];
    }

    if (empty($image)) {
      $image = 'no_image.png';
    } else {
      $image = 'products/' . $this->_groups[$group_id]['code'] . '/' . $image;
    }

    return lc_image(DIR_WS_IMAGES . $image, $title, $width, $height, $parameters);
  }

  public function getAddress($image, $group = 'default') {
    $group_id = $this->getID($group);

    return DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'] . '/' . $image;
  }
}
?>