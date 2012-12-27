<?php
/*
  $Id: upload.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class upload {
    var $file, $filename, $destination, $permissions, $extensions, $tmp_filename;

    function upload($file = '', $destination = '', $permissions = '777', $extensions = '') {
      $this->set_file($file);
      $this->set_destination($destination);
      $this->set_permissions($permissions);
      $this->set_extensions($extensions);
    }

    function exists() {
      $file = array();

      if ( is_array($this->file) ) {
        $file = $this->file;
      } elseif ( isset($_FILES[$this->file]) ) {
        $file = array('name' => $_FILES[$this->file]['name'],
                      'type' => $_FILES[$this->file]['type'],
                      'size' => $_FILES[$this->file]['size'],
                      'tmp_name' => $_FILES[$this->file]['tmp_name']);
      }

      if ( isset($file['tmp_name']) && !empty($file['tmp_name']) && ($file['tmp_name'] != 'none') && is_uploaded_file($file['tmp_name']) ) {
        return true;
      }

      return false;
    }

    function parse() {
      global $lC_Language, $lC_MessageStack;

      $file = array();

      if ( is_array($this->file) ) {
        $file = $this->file;
      } elseif ( isset($_FILES[$this->file]) ) {
        $file = array('name' => $_FILES[$this->file]['name'],
                      'type' => $_FILES[$this->file]['type'],
                      'size' => $_FILES[$this->file]['size'],
                      'tmp_name' => $_FILES[$this->file]['tmp_name']);
      }

      if ( isset($file['tmp_name']) && !empty($file['tmp_name']) && ($file['tmp_name'] != 'none') && is_uploaded_file($file['tmp_name']) ) {
        if (sizeof($this->extensions) > 0) {
          if (!in_array(strtolower(substr($file['name'], strrpos($file['name'], '.')+1)), $this->extensions)) {
            $lC_MessageStack->add('header', $lC_Language->get('ms_error_upload_file_type_prohibited'), 'error');

            return false;
          }
        }

        $this->set_file($file);
        $this->set_filename($file['name']);
        $this->set_tmp_filename($file['tmp_name']);

        if (!empty($this->destination)) {
          return $this->check_destination();
        } else {
          return true;
        }
      }
    }

    function save() {
      global $lC_Language, $lC_MessageStack;

      if (substr($this->destination, -1) != '/') $this->destination .= '/';

      if (move_uploaded_file($this->file['tmp_name'], $this->destination . $this->filename)) {
        chmod($this->destination . $this->filename, $this->permissions);

        return true;
      } else {
        $lC_MessageStack->add('header', $lC_Language->get('ms_error_upload_file_not_saved'), 'error');

        return false;
      }
    }

    function set_file($file) {
      $this->file = $file;
    }

    function set_destination($destination) {
      $this->destination = $destination;
    }

    function set_permissions($permissions) {
      $this->permissions = octdec($permissions);
    }

    function set_filename($filename) {
      $this->filename = $filename;
    }

    function set_tmp_filename($filename) {
      $this->tmp_filename = $filename;
    }

    function set_extensions($extensions) {
      if (!empty($extensions)) {
        if (is_array($extensions)) {
          $this->extensions = $extensions;
        } else {
          $this->extensions = array($extensions);
        }
      } else {
        $this->extensions = array();
      }
    }

    function check_destination() {
      global $lC_Language, $lC_MessageStack;

      if (!is_writeable($this->destination)) {
        if (is_dir($this->destination)) {
          $lC_MessageStack->add('header', sprintf($lC_Language->get('ms_error_upload_destination_not_writable'), $this->destination), 'error');
        } else {
          $lC_MessageStack->add('header', sprintf($lC_Language->get('ms_error_upload_destination_non_existant'), $this->destination), 'error');
        }

        return false;
      } else {
        return true;
      }
    }
  }
?>