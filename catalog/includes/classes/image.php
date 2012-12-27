<?php
/*
  $Id: image.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Image {
  var $_groups;

  function lC_Image() {
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

  function getID($code) {
    foreach ($this->_groups as $group) {
      if ($group['code'] == $code) {
        return $group['id'];
      }
    }

    return 0;
  }

  function getCode($id) {
    return $this->_groups[$id]['code'];
  }

  function getWidth($code) {
    return $this->_groups[$this->getID($code)]['size_width'];
  }

  function getHeight($code) {
    return $this->_groups[$this->getID($code)]['size_height'];
  }

  function exists($code) {
    return isset($this->_groups[$this->getID($code)]);
  }

  function show($image, $title, $parameters = '', $group = '') {
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
      $image = 'pixel_trans.gif';
    } else {
      $image = 'products/' . $this->_groups[$group_id]['code'] . '/' . $image;
    }

    return lc_image(DIR_WS_IMAGES . $image, $title, $width, $height, $parameters);
  }

  function getAddress($image, $group = 'default') {
    $group_id = $this->getID($group);

    return DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'] . '/' . $image;
  }
}
?>