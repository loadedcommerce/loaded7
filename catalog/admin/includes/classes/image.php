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

  require('../includes/classes/image.php');

  class lC_Image_Admin extends lC_Image {

    // Private variables
    var $_title, $_header, $_data = array();
    var $_has_parameters = false;

    // Class constructor
    function lC_Image_Admin() {
      parent::lC_Image();
    }

    // Public methods
    function &getGroups() {
      return $this->_groups;
    }

    function resize($image, $group_id) {
      if (lc_empty(CFG_APP_IMAGEMAGICK_CONVERT) || ! @file_exists(CFG_APP_IMAGEMAGICK_CONVERT)) {
        return $this->resizeWithGD($image, $group_id);
      }

      if (!file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'])) {
        mkdir(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code']);
        @chmod(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'], 0777);
      }

      exec(escapeshellarg(CFG_APP_IMAGEMAGICK_CONVERT) . ' -resize ' . (int)$this->_groups[$group_id]['size_width'] . 'x' . (int)$this->_groups[$group_id]['size_height'] . (($this->_groups[$group_id]['force_size']) == '1' ? '!' : '') . ' ' . escapeshellarg(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[1]['code'] . '/' . $image) . ' ' . escapeshellarg(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'] . '/' . $image));
      @chmod(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'] . '/' . $image, 0777);
    }

    public static function hasGDSupport() {
      if ( imagetypes() & ( IMG_JPG || IMG_GIF || IMG_PNG ) ) {
        return true;
      }

      return false;
    }

    function resizeWithGD($image, $group_id) {
      $img_type = false;

      switch (substr($image, (strrpos($image, '.')+1))) {
        case 'jpg':
        case 'jpeg':
          if (imagetypes() & IMG_JPG) {
            $img_type = 'jpg';
          }

          break;

        case 'gif':
          if (imagetypes() & IMG_GIF) {
            $img_type = 'gif';
          }

          break;

        case 'png':
          if (imagetypes() & IMG_PNG) {
            $img_type = 'png';
          }

          break;
      }

      if ($img_type !== false) {
        if (! @file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'])) {
          mkdir(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'], 0777);
        }

        list($orig_width, $orig_height) = @getimagesize(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[1]['code'] . '/' . $image);

        $height = $this->_groups[$group_id]['size_height'];

        if ($this->_groups[$group_id]['force_size'] == '1') {
          $width = $this->_groups[$group_id]['size_width'];
        } else {
          $width = @round($orig_width * $height / $orig_height);
        }

        $im_p = @imagecreatetruecolor($width, $height);

        if ( ($img_type == 'gif') || ($img_type == 'png') ) {
          @imagealphablending($im_p, false);
          @imagesavealpha($im_p, true);

          $transparent = @imagecolorallocatealpha($im_p, 255, 255, 255, 127);
          @imagefilledrectangle($im_p, 0, 0, $height, $width, $transparent);
        }

        $x = 0;

        if ($this->_groups[$group_id]['force_size'] == '1') {
          if ( ($img_type != 'gif') && ($img_type != 'png') ) {
            $bgcolour = @imagecolorallocate($im_p, 255, 255, 255); // white
            @imagefill($im_p, 0, 0, $bgcolour);
          }

          $width = @round($orig_width * $height / $orig_height);

          if ($width < $this->_groups[$group_id]['size_width']) {
            $x = @floor(($this->_groups[$group_id]['size_width'] - $width) / 2);
          }
        }

        switch ($img_type) {
          case 'jpg':
            $im = @imagecreatefromjpeg(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[1]['code'] . '/' . $image);
            break;

          case 'gif':
            $im = @imagecreatefromgif(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[1]['code'] . '/' . $image);
            break;

          case 'png':
            $im = @imagecreatefrompng(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[1]['code'] . '/' . $image);
            break;
        }

        @imagecopyresampled($im_p, $im, $x, 0, 0, 0, $width, $height, $orig_width, $orig_height);

        switch ($img_type) {
          case 'jpg':
            @imagejpeg($im_p, DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'] . '/' . $image);
            break;

          case 'gif':
            @imagegif($im_p, DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'] . '/' . $image);
            break;

          case 'png':
            @imagepng($im_p, DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'] . '/' . $image);
            break;
        }

        @imagedestroy($im_p);
        @imagedestroy($im);

        @chmod(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'] . '/' . $image, 0777);
      } else {
        return false;
      }
    }

    function getModuleCode() {
      return $this->_code;
    }

    function &getTitle() {
      return $this->_title;
    }

    function &getHeader() {
      return $this->_header;
    }

    function &getData() {
      return $this->_data;
    }

    function activate() {
      $this->_setHeader();
      $this->_setData();
    }

    function hasParameters() {
      return $this->_has_parameters;
    }

    function existsInGroup($id, $group_id) {
      global $lC_Database;

      $Qimage = $lC_Database->query('select image from :table_products_images where id = :id');
      $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimage->bindInt(':id', $id);
      $Qimage->execute();

      return @file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $this->_groups[$group_id]['code'] . '/' . $Qimage->value('image'));
    }

    function delete($id) {
      global $lC_Database;

      $Qimage = $lC_Database->query('select image from :table_products_images where id = :id');
      $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimage->bindInt(':id', $id);
      $Qimage->execute();

      foreach ($this->_groups as $group) {
        @unlink(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $group['code'] . '/' . $Qimage->value('image'));
      }

      $Qdel = $lC_Database->query('delete from :table_products_images where id = :id');
      $Qdel->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qdel->bindInt(':id', $id);
      $Qdel->execute();

      return ($Qdel->affectedRows() === 1);
    }

    function setAsDefault($id) {
      global $lC_Database;

      $Qimage = $lC_Database->query('select products_id from :table_products_images where id = :id');
      $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimage->bindInt(':id', $id);
      $Qimage->execute();

      if ($Qimage->numberOfRows() === 1) {
        $Qupdate = $lC_Database->query('update :table_products_images set default_flag = :default_flag where products_id = :products_id and default_flag = :default_flag');
        $Qupdate->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qupdate->bindInt(':default_flag', 0);
        $Qupdate->bindInt(':products_id', $Qimage->valueInt('products_id'));
        $Qupdate->bindInt(':default_flag', 1);
        $Qupdate->execute();

        $Qupdate = $lC_Database->query('update :table_products_images set default_flag = :default_flag where id = :id');
        $Qupdate->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qupdate->bindInt(':default_flag', 1);
        $Qupdate->bindInt(':id', $id);
        $Qupdate->execute();

        return ($Qupdate->affectedRows() === 1);
      }
    }

    function reorderImages($images_array) {
      global $lC_Database;

      $counter = 0;

      foreach ($images_array as $id) {
        $counter++;

        $Qupdate = $lC_Database->query('update :table_products_images set sort_order = :sort_order where id = :id');
        $Qupdate->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qupdate->bindInt(':sort_order', $counter);
        $Qupdate->bindInt(':id', $id);
        $Qupdate->execute();
      }

      return ($counter > 0);
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

      return lc_image('../' . DIR_WS_IMAGES . $image, $title, $width, $height, $parameters);
    }
  }
?>