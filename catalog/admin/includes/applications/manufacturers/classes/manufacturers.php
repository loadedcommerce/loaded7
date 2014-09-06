<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufacturers.php v1.0 2013-08-08 datazen $
*/
class lC_Manufacturers_Admin {
 /*
  * Returns the customer groups datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language;
           
    $media = $_GET['media'];
    
    $Qmanufacturers = $lC_Database->query('select manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified from :table_manufacturers order by manufacturers_name');
    $Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qmanufacturers->execute();

    $result = array('aaData' => array());
    while ( $Qmanufacturers->next() ) {
      $Qclicks = $lC_Database->query('select sum(url_clicked) as total from :table_manufacturers_info where manufacturers_id = :manufacturers_id');
      $Qclicks->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
      $Qclicks->bindInt(':manufacturers_id', $Qmanufacturers->valueInt('manufacturers_id'));
      $Qclicks->execute();

      $name = '<td>' . $Qmanufacturers->value('manufacturers_name') . '</td>';
      $clicks = '<td>' . $Qclicks->valueInt('total') . '</td>';
      $action = '<td class="align-right vertical-center">
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? '#' : 'javascript://" onclick="editEntry(\'' . $Qmanufacturers->valueInt('manufacturers_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   </span>
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qmanufacturers->valueInt('manufacturers_id') . '\', \'' . urlencode($Qmanufacturers->valueProtected('manufacturers_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                   </span>
                 </td>';
      $result['aaData'][] = array("$name", "$clicks", "$action");
      $result['entries'][] = $Qmanufacturers->toArray();
    }

    $Qmanufacturers->freeResult();

    return $result;
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $id The customer groups id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return array
  */
  public static function getFormData($id = null) {
    global $lC_Database, $lC_Language;

    $result = array();
    foreach ( $lC_Language->getAll() as $l ) {
      $result['mfgUrl'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('manufacturers_url[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span><br />';
    }

    if ($id != null && is_numeric($id)) {
      $manufacturers_array = array();
      $Qmanufacturer = $lC_Database->query('select manufacturers_url, languages_id from :table_manufacturers_info where manufacturers_id = :manufacturers_id');
      $Qmanufacturer->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
      $Qmanufacturer->bindInt(':manufacturers_id', $id);
      $Qmanufacturer->execute();

      while ( $Qmanufacturer->next() ) {
        $manufacturers_array[$Qmanufacturer->valueInt('languages_id')] = $Qmanufacturer->value('manufacturers_url');
      }
      foreach ( $lC_Language->getAll() as $l ) {
        $result['editMfgUrl'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('manufacturers_url[' . $l['id'] . ']', $manufacturers_array[$l['id']], 'class="input-unstyled"') . '</span><br />';
      }
      $result['mData'] = lC_Manufacturers_Admin::getData($id, $lC_Language->getID());
      $result['mImage'] = lc_image('../' . DIR_WS_IMAGES . 'manufacturers/' . $result['mData']['manufacturers_image'], $result['mData']['manufacturers_name']) . '<br />' . DIR_WS_CATALOG . DIR_WS_IMAGES . 'manufacturers/' . $result['mData']['manufacturers_image'] . '<br />';

    }

    return $result;
  }
 /*
  * Return the manufacturer information
  *
  * @param integer $id The manufacturer id
  * @param boolean $language_id The language id, null = default language
  * @access public
  * @return array
  */
  public static function getData($id, $language_id = null) {
    global $lC_Database, $lC_Language;

    if ( empty($language_id) ) {
      $language_id = $lC_Language->getID();
    }

    $Qmanufacturers = $lC_Database->query('select m.*, mi.* from :table_manufacturers m, :table_manufacturers_info mi where m.manufacturers_id = :manufacturers_id and m.manufacturers_id = mi.manufacturers_id and mi.languages_id = :languages_id');
    $Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qmanufacturers->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
    $Qmanufacturers->bindInt(':manufacturers_id', $id);
    $Qmanufacturers->bindInt(':languages_id', $language_id);
    $Qmanufacturers->execute();

    $data = $Qmanufacturers->toArray();

    $Qclicks = $lC_Database->query('select sum(url_clicked) as total from :table_manufacturers_info where manufacturers_id = :manufacturers_id');
    $Qclicks->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
    $Qclicks->bindInt(':manufacturers_id', $id);
    $Qclicks->execute();

    $data['url_clicks'] = $Qclicks->valueInt('total');

    $Qproducts = $lC_Database->query('select count(*) as products_count from :table_products where manufacturers_id = :manufacturers_id');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindInt(':manufacturers_id', $id);
    $Qproducts->execute();

    $data['products_count'] = $Qproducts->valueInt('products_count');

    $Qclicks->freeResult();
    $Qproducts->freeResult();
    $Qmanufacturers->freeResult();

    return $data;
  }
 /*
  * Save the manufacturer information
  *
  * @param integer $id The manufacturer id to update, null on insert
  * @param array $data The manufacturer information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language;

    $error = false;

    $lC_Database->startTransaction();

    if ( is_numeric($id) ) {
      $Qmanufacturer = $lC_Database->query('update :table_manufacturers set manufacturers_name = :manufacturers_name, last_modified = now() where manufacturers_id = :manufacturers_id');
      $Qmanufacturer->bindInt(':manufacturers_id', $id);
    } else {
      $Qmanufacturer = $lC_Database->query('insert into :table_manufacturers (manufacturers_name, date_added) values (:manufacturers_name, now())');
    }

    $Qmanufacturer->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qmanufacturer->bindValue(':manufacturers_name', $data['name']);
    $Qmanufacturer->setLogging($_SESSION['module'], $id);
    $Qmanufacturer->execute();

    if ( !$lC_Database->isError() ) {
      if ( is_numeric($id) ) {
        $manufacturers_id = $id;
      } else {
        $manufacturers_id = $lC_Database->nextID();
      }

      $image = new upload('manufacturers_image', realpath('../' . DIR_WS_IMAGES . 'manufacturers'));

      if ( $image->exists() ) {
        if ( $image->parse() && $image->save() ) {
          $Qimage = $lC_Database->query('update :table_manufacturers set manufacturers_image = :manufacturers_image where manufacturers_id = :manufacturers_id');
          $Qimage->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
          $Qimage->bindValue(':manufacturers_image', $image->filename);
          $Qimage->bindInt(':manufacturers_id', $manufacturers_id);
          $Qimage->setLogging($_SESSION['module'], $manufacturers_id);
          $Qimage->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }
      }
    } else {
      $error = true;
    }

    if ( $error === false ) {
      foreach ( $lC_Language->getAll() as $l ) {
        if ( is_numeric($id) ) {
          $Qurl = $lC_Database->query('update :table_manufacturers_info set manufacturers_url = :manufacturers_url where manufacturers_id = :manufacturers_id and languages_id = :languages_id');
        } else {
          $Qurl = $lC_Database->query('insert into :table_manufacturers_info (manufacturers_id, languages_id, manufacturers_url) values (:manufacturers_id, :languages_id, :manufacturers_url)');
        }

        $Qurl->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
        $Qurl->bindInt(':manufacturers_id', $manufacturers_id);
        $Qurl->bindInt(':languages_id', $l['id']);
        $Qurl->bindValue(':manufacturers_url', $data['url'][$l['id']]);
        $Qurl->setLogging($_SESSION['module'], $manufacturers_id);
        $Qurl->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
          break;
        }
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('manufacturers');

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Delete the manufacturer
  *
  * @param integer $id The manufacturer id to delete
  * @param boolean $delete_image Also delete the image from the server
  * @param boolean $delete_products Also delete the products from this manufacturer
  * @access public
  * @return array
  */
  public static function delete($id, $delete_image = false, $delete_products = false) {
    global $lC_Database;

    if ( $delete_image === true ) {
      $Qimage = $lC_Database->query('select manufacturers_image from :table_manufacturers where manufacturers_id = :manufacturers_id');
      $Qimage->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
      $Qimage->bindInt(':manufacturers_id', $id);
      $Qimage->execute();

      if ( $Qimage->numberOfRows() && !lc_empty($Qimage->value('manufacturers_image')) ) {
        if ( file_exists(realpath('../' . DIR_WS_IMAGES . 'manufacturers/' . $Qimage->value('manufacturers_image'))) ) {
          @unlink(realpath('../' . DIR_WS_IMAGES . 'manufacturers/' . $Qimage->value('manufacturers_image')));
        }
      }
    }

    $Qm = $lC_Database->query('delete from :table_manufacturers where manufacturers_id = :manufacturers_id');
    $Qm->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qm->bindInt(':manufacturers_id', $id);
    $Qm->setLogging($_SESSION['module'], $id);
    $Qm->execute();

    $Qmi = $lC_Database->query('delete from :table_manufacturers_info where manufacturers_id = :manufacturers_id');
    $Qmi->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
    $Qmi->bindInt(':manufacturers_id', $id);
    $Qmi->setLogging($_SESSION['module'], $id);
    $Qmi->execute();

    if ( $delete_products === true ) {
      $Qproducts = $lC_Database->query('select products_id from :table_products where manufacturers_id = :manufacturers_id');
      $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproducts->bindInt(':manufacturers_id', $id);
      $Qproducts->execute();

      while ( $Qproducts->next() ) {
        lC_Products_Admin::delete($Qproducts->valueInt('products_id'));
      }
    } else {
      $Qupdate = $lC_Database->query('update :table_products set manufacturers_id = null where manufacturers_id = :manufacturers_id');
      $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
      $Qupdate->bindInt(':manufacturers_id', $id);
      $Qupdate->setLogging($_SESSION['module'], $id);
      $Qupdate->execute();
    }

    lC_Cache::clear('manufacturers');

    return true;
  }
 /*
  * Batch delete manufacturer records
  *
  * @param array $batch The manufacturer id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Manufacturers_Admin::delete($id);
    }
    return true;
  }
  public static function getManufacturersArray() {
    global $lC_Language, $lC_Database;
    // build the manufacturers array
    $manufacturers_array = array();
    $Qmanufacturers = $lC_Database->query('select manufacturers_id, manufacturers_name from :table_manufacturers order by manufacturers_name');
    $Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qmanufacturers->execute();
    while ($Qmanufacturers->next()) {
      $manufacturersArray[] = array('id' => $Qmanufacturers->valueInt('manufacturers_id'),
                                    'text' => $Qmanufacturers->value('manufacturers_name'));
    }
    return $manufacturersArray;
  }   
}
?>