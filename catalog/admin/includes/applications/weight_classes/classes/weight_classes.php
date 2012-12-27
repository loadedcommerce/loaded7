<?php
/*
  $Id: weight_classes.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Weight_classes_Admin class manages weight class definitions
*/
class lC_Weight_classes_Admin {
 /*
  * Returns the weight class datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $Qclasses = $lC_Database->query('select weight_class_id, weight_class_key, weight_class_title from :table_weight_classes where language_id = :language_id order by weight_class_title');
    $Qclasses->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
    $Qclasses->bindInt(':language_id', $lC_Language->getID());
    $Qclasses->execute();

    $result = array('aaData' => array());
    while ( $Qclasses->next() ) {
      $class_name = $Qclasses->value('weight_class_title');
      if ( $Qclasses->valueInt('weight_class_id') == SHIPPING_WEIGHT_UNIT ) {
        $class_name .= '<small class="tag purple-gradient glossy margin-left">' . $lC_Language->get('default_entry') . '</small>';
      }

      $name = '<td>' . $class_name . '</td>';
      $unit = '<td>' . $Qclasses->value('weight_class_key') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['definitions'] < 3) ? '#' : 'javascript://" onclick="editClass(\'' . $Qclasses->valueInt('weight_class_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['definitions'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['definitions'] < 4 || $Qclasses->valueInt('weight_class_id') == SHIPPING_WEIGHT_UNIT) ? '#' : 'javascript://" onclick="deleteClass(\'' . $Qclasses->valueInt('weight_class_id') . '\', \'' . urlencode($Qclasses->valueProtected('weight_class_title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['definitions'] < 4 || $Qclasses->valueInt('weight_class_id') == SHIPPING_WEIGHT_UNIT) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$name", "$unit", "$action");
    }

    $Qclasses->freeResult();

    return $result;
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $id The weight class id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return array
  */
  public static function getFormData($id = null, $edit = false) {
    global $lC_Database, $lC_Language;

    $result = array();

    $result['titleCode'] = '';
    foreach ( $lC_Language->getAll() as $l ) {
      $result['titleCode'] .= '<span class="input" style="width:75%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('name[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span> ' . lc_draw_input_field('key[' . $l['id'] . ']', null, 'class="input" size="4"') . '<br />';
    }

    $Qrules = $lC_Database->query('select weight_class_id, weight_class_title from :table_weight_classes where language_id = :language_id order by weight_class_title');
    $Qrules->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
    $Qrules->bindInt(':language_id', $lC_Language->getID());
    $Qrules->execute();
    $result['newRules'] = '';
    while ( $Qrules->next() ) {
      $result['newRules'] .= '<span class="input full-width"><label for="rules[' . $Qrules->valueInt('weight_class_id') . ']" style="width:20%" class="button grey-gradient glossy">' . $Qrules->value('weight_class_title') . '</label>' . lc_draw_input_field('rules[' . $Qrules->valueInt('weight_class_id') . ']', null, 'class="input-unstyled required number"') . '</span>';
    }

    if (isset($id) && $id != null) {
      if ($edit === true) {
        $Qwc = $lC_Database->query('select language_id, weight_class_key, weight_class_title from :table_weight_classes where weight_class_id = :weight_class_id');
        $Qwc->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
        $Qwc->bindInt(':weight_class_id', $id);
        $Qwc->execute();
        $classes_array = array();
        while ( $Qwc->next() ) {
          $classes_array[$Qwc->valueInt('language_id')] = array('key' => $Qwc->value('weight_class_key'),
                                                                'title' => $Qwc->value('weight_class_title'));
        }
        foreach ( $lC_Language->getAll() as $l ) {
          $result['names'] .= '<span class="input" style="width:75%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('name[' . $l['id'] . ']', $classes_array[$l['id']]['title'], 'class="input-unstyled"') . '</span> ' . lc_draw_input_field('key[' . $l['id'] . ']', $classes_array[$l['id']]['key'], 'class="input" size="4"') . '<br />';
        }

        $Qwc->freeResult();

        $Qrules = $lC_Database->query('select r.weight_class_to_id, r.weight_class_rule, c.weight_class_title from :table_weight_classes_rules r, :table_weight_classes c where r.weight_class_from_id = :weight_class_from_id and r.weight_class_to_id != :weight_class_to_id and r.weight_class_to_id = c.weight_class_id and c.language_id = :language_id order by c.weight_class_title');
        $Qrules->bindTable(':table_weight_classes_rules', TABLE_WEIGHT_CLASS_RULES);
        $Qrules->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
        $Qrules->bindInt(':weight_class_from_id', $id);
        $Qrules->bindInt(':weight_class_to_id', $id);
        $Qrules->bindInt(':language_id', $lC_Language->getID());
        $Qrules->execute();

        $result['editRules'] = '';
        while ( $Qrules->next() ) {
          $result['editRules'] .= '<span class="input full-width"><label for="rules[' . $Qrules->valueInt('weight_class_to_id') . ']" style="width:20%" class="button grey-gradient glossy">' . $Qrules->value('weight_class_title') . '</label>' . lc_draw_input_field('rules[' . $Qrules->valueInt('weight_class_to_id') . ']', $Qrules->value('weight_class_rule'), 'class="input-unstyled required number"') . '</span>';
        }
      } else {
        $result['wcData'] = lC_Weight_classes_Admin::getData($id);

        $Qcheck = $lC_Database->query('select count(*) as total from :table_products where products_weight_class = :products_weight_class');
        $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
        $Qcheck->bindInt(':products_weight_class', $id);
        $Qcheck->execute();

        if ( ( $id == SHIPPING_WEIGHT_UNIT ) || ( $Qcheck->valueInt('total') > 0 ) ) {
          if ( $id == SHIPPING_WEIGHT_UNIT ) {
            $result['rpcStatus'] = -3;
          }
          if ( $Qcheck->valueInt('total') > 0 ) {
            $result['rpcStatus'] = -2;
            $result['totalInUse'] = $Qcheck->valueInt('total');
          }
        }
      }
    }

    return $result;
  }
 /*
  * Returns the weight class information
  *
  * @param integer $id The weight class id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language;

    $Qclass = $lC_Database->query('select * from :table_weight_classes where weight_class_id = :weight_class_id and language_id = :language_id');
    $Qclass->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
    $Qclass->bindInt(':weight_class_id', $id);
    $Qclass->bindInt(':language_id', $lC_Language->getID());
    $Qclass->execute();

    $data = $Qclass->toArray();

    $Qclass->freeResult();

    return $data;
  }
 /*
  * Saves the weight class information
  *
  * @param integer $id The weight class id used on update, null on insert
  * @param array $data An array containing the weight class information
  * @param boolean $default True = set the weight class to be the default
  * @access public
  * @return boolean
  */
  public static function save($data, $default = false) {
    global $lC_Database, $lC_Language;

    $error = false;

    $id = (isset($data['wcid']) && is_numeric($data['wcid'])) ? (int)$data['wcid'] : NULL;

    $lC_Database->startTransaction();

    if ( is_numeric($id) ) {
      $weight_class_id = $id;
    } else {
      $Qwc = $lC_Database->query('select max(weight_class_id) as weight_class_id from :table_weight_classes');
      $Qwc->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
      $Qwc->execute();

      $weight_class_id = $Qwc->valueInt('weight_class_id') + 1;
    }

    foreach ( $lC_Language->getAll() as $l ) {
      if ( is_numeric($id) ) {
        $Qwc = $lC_Database->query('update :table_weight_classes set weight_class_key = :weight_class_key, weight_class_title = :weight_class_title where weight_class_id = :weight_class_id and language_id = :language_id');
      } else {
        $Qwc = $lC_Database->query('insert into :table_weight_classes (weight_class_id, language_id, weight_class_key, weight_class_title) values (:weight_class_id, :language_id, :weight_class_key, :weight_class_title)');
      }

      $Qwc->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
      $Qwc->bindInt(':weight_class_id', $weight_class_id);
      $Qwc->bindInt(':language_id', $l['id']);
      $Qwc->bindValue(':weight_class_key', $data['key'][$l['id']]);
      $Qwc->bindValue(':weight_class_title', $data['name'][$l['id']]);
      $Qwc->setLogging($_SESSION['module'], $weight_class_id);
      $Qwc->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      if ( is_numeric($id) ) {
        $Qrules = $lC_Database->query('select weight_class_to_id from :table_weight_classes_rules where weight_class_from_id = :weight_class_from_id and weight_class_to_id != :weight_class_to_id');
        $Qrules->bindTable(':table_weight_classes_rules', TABLE_WEIGHT_CLASS_RULES);
        $Qrules->bindInt(':weight_class_from_id', $weight_class_id);
        $Qrules->bindInt(':weight_class_to_id', $weight_class_id);
        $Qrules->execute();

        while ( $Qrules->next() ) {
          $Qrule = $lC_Database->query('update :table_weight_classes_rules set weight_class_rule = :weight_class_rule where weight_class_from_id = :weight_class_from_id and weight_class_to_id = :weight_class_to_id');
          $Qrule->bindTable(':table_weight_classes_rules', TABLE_WEIGHT_CLASS_RULES);
          $Qrule->bindValue(':weight_class_rule', $data['rules'][$Qrules->valueInt('weight_class_to_id')]);
          $Qrule->bindInt(':weight_class_from_id', $weight_class_id);
          $Qrule->bindInt(':weight_class_to_id', $Qrules->valueInt('weight_class_to_id'));
          $Qrule->setLogging($_SESSION['module'], $weight_class_id);
          $Qrule->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            break;
          }
        }
      } else {
        $Qclasses = $lC_Database->query('select weight_class_id from :table_weight_classes where weight_class_id != :weight_class_id and language_id = :language_id');
        $Qclasses->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
        $Qclasses->bindInt(':weight_class_id', $weight_class_id);
        $Qclasses->bindInt(':language_id', $lC_Language->getID());
        $Qclasses->execute();

        while ( $Qclasses->next() ) {
          $Qdefault = $lC_Database->query('insert into :table_weight_classes_rules (weight_class_from_id, weight_class_to_id, weight_class_rule) values (:weight_class_from_id, :weight_class_to_id, :weight_class_rule)');
          $Qdefault->bindTable(':table_weight_classes_rules', TABLE_WEIGHT_CLASS_RULES);
          $Qdefault->bindInt(':weight_class_from_id', $Qclasses->valueInt('weight_class_id'));
          $Qdefault->bindInt(':weight_class_to_id', $weight_class_id);
          $Qdefault->bindInt(':weight_class_rule', 1);
          $Qdefault->setLogging($_SESSION['module'], $weight_class_id);
          $Qdefault->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            break;
          }

          if ( $error === false ) {
            $Qnew = $lC_Database->query('insert into :table_weight_classes_rules (weight_class_from_id, weight_class_to_id, weight_class_rule) values (:weight_class_from_id, :weight_class_to_id, :weight_class_rule)');
            $Qnew->bindTable(':table_weight_classes_rules', TABLE_WEIGHT_CLASS_RULES);
            $Qnew->bindInt(':weight_class_from_id', $weight_class_id);
            $Qnew->bindInt(':weight_class_to_id', $Qclasses->valueInt('weight_class_id'));
            $Qnew->bindValue(':weight_class_rule', $data['rules'][$Qclasses->valueInt('weight_class_id')]);
            $Qnew->setLogging($_SESSION['module'], $weight_class_id);
            $Qnew->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }
      }
    }

    if ( $error === false ) {
      if ( $default === true ) {
        $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindInt(':configuration_value', $weight_class_id);
        $Qupdate->bindValue(':configuration_key', 'SHIPPING_WEIGHT_UNIT');
        $Qupdate->setLogging($_SESSION['module'], $weight_class_id);
        $Qupdate->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      if ( $default === true ) {
        lC_Cache::clear('configuration');
      }

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Deletes the weight class record
  *
  * @param integer $id The weight class id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    $Qrules = $lC_Database->query('delete from :table_weight_classes_rules where weight_class_from_id = :weight_class_from_id or weight_class_to_id = :weight_class_to_id');
    $Qrules->bindTable(':table_weight_classes_rules', TABLE_WEIGHT_CLASS_RULES);
    $Qrules->bindInt(':weight_class_from_id', $id);
    $Qrules->bindInt(':weight_class_to_id', $id);
    $Qrules->setLogging($_SESSION['module'], $id);
    $Qrules->execute();

    if ( $lC_Database->isError() ) {
      $error = true;
    }

    if ( $error === false ) {
      $Qclasses = $lC_Database->query('delete from :table_weight_classes where weight_class_id = :weight_class_id');
      $Qclasses->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
      $Qclasses->bindInt(':weight_class_id', $id);
      $Qclasses->setLogging($_SESSION['module'], $id);
      $Qclasses->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('weight-classes');
      lC_Cache::clear('weight-rules');

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Batch deletes weight class records
  *
  * @param array $batch An array of weight class id's to delete
  * @access public
  * @return array
  */
  public static function batchDelete($batch) {
    global $lC_Language;

    $lC_Language->loadIniFile('weight_classes.php');

    $result = array();
    $result['namesString'] = '';
    foreach ( $batch as $id ) {
      $wcData = lC_Weight_classes_Admin::getFormData($id);
      if ( isset($wcData['rpcStatus']) ) {
        if ($wcData['rpcStatus'] == -2) {
          $result['namesString'] .= $wcData['wcData']['weight_class_title'] . ' (' . sprintf($lC_Language->get('total_entries'), $wcData['totalInUse']) . '), ';
        } else if ($wcData['rpcStatus'] == -3) {
          $result['namesString'] .= $wcData['wcData']['weight_class_title'] . ' (' . $lC_Language->get('default') . '), ';
        }
      } else {
        lC_Weight_classes_Admin::delete($id);
      }
    }
    if ( !empty($result['namesString']) ) {
      $result['namesString'] = substr($result['namesString'], 0, -2);
    }

    return $result;
  }
}
?>