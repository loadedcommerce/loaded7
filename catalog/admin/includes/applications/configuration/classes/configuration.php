<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: configuration.php v1.0 2013-08-08 datazen $
*/
class lC_Configuration_Admin {
 /*
  * Returns the configuration datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll($group_id = 1, $view = NULL) {
    global $lC_Database, $lC_Language;

    $Qcfg = $lC_Database->query('select * from :table_configuration where configuration_group_id = :configuration_group_id order by sort_order');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindInt(':configuration_group_id', $group_id);
    $Qcfg->execute();

    $result = array('entries' => array());
    $result = array('aaData' => array());
    while ( $Qcfg->next() ) {
      if ($Qcfg->valueProtected('configuration_title') == 'Zip Code' && STORE_COUNTRY != 223) {
        $title = 'Postal Code';
      } else {
        $title = $Qcfg->valueProtected('configuration_title');
      }
      $value = $Qcfg->valueProtected('configuration_value');
      $configuration_group_id = $Qcfg->valueProtected('configuration_group_id');

      if ($value == '-1') {
        $value = $lC_Language->get('parameter_false');
      } elseif ($value == '0') {
        $value = $lC_Language->get('parameter_optional');
      } elseif ($value == '1') {
        // Value 1 or true/yes fix.
        if ($configuration_group_id == 7) {
          $value = 1;
        } else {
          $value = $lC_Language->get('parameter_true');
        }
      } else if ($title == 'Country' || $title == 'Country of Origin') {
        $country = lc_get_country_data($value);
        $value = $country['countries_name'];
      } else if ($title == 'Default Shipping Unit') {
        $wc = lc_get_weight_class_data($value);
        $value = $wc['weight_class_title'];
      } else if (stristr($title, 'percentage')) {
        $value = $value . '%';
      } else if ($title == 'Zone') {
        $zn = lc_get_zone_data($value);
        $value = $zn['zone_name'];
      }

      $action = '<td class="align-right vertical-center">' .
                '  <span class="button-group compact" title="' . $_SESSION['admin']['access']['configuration']. '">' .
                '    <a href="javascript:void(0);" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['configuration'] < 4) ? " disabled" : NULL) . '"' . ((int)($_SESSION['admin']['access']['configuration'] < 4) ? NULL : ' onclick="editEntry(\'' . $Qcfg->valueInt('configuration_id') . '\')"') . '><span>' .  ((stristr($view, 'mobile-')) ? NULL : $lC_Language->get('icon_edit')) . '</span></a>' .
                '  </span>' .
                '</td>';

      $result['aaData'][] = array("$title", "$value", "$action");
      $result['entries'][] = $Qcfg->toArray();
      if ( !lc_empty($Qcfg->value('use_function')) ) {
        $result['entries'][sizeof($result['entries'])-1]['configuration_value'] = lc_call_user_func($Qcfg->value('use_function'), $Qcfg->value('configuration_value'));
      }
    }
    $Qcfg->freeResult();

    return $result;
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $id The configuration id
  * @access public
  * @return array
  */
  public static function getFormData($id = null) {
    $result = array();
    $result['cData'] = array();
    if (isset($id) && $id != null) {
      $result['cData'] = lC_Configuration_Admin::getData($id);
      
      if ( !lc_empty($result['cData']['set_function']) ) {
        if ($result['cData']['configuration_key'] == 'STORE_ZONE') {
          if (lc_store_country_has_zones() == 1) {
            $result['valueField'] = lc_call_user_func($result['cData']['set_function'], $result['cData']['configuration_value'], $result['cData']['configuration_key']);
          } else {
            $result['valueField'] = lc_draw_input_field('configuration[' . $result['cData']['configuration_key'] . ']', $result['cData']['configuration_value'], 'style="width:96%"');
          }
        } else { 
          $result['valueField'] = lc_call_user_func($result['cData']['set_function'], $result['cData']['configuration_value'], $result['cData']['configuration_key']);
        }
      } else {
        $result['valueField'] = lc_draw_input_field('configuration[' . $result['cData']['configuration_key'] . ']', $result['cData']['configuration_value'], 'style="width:96%"');
      }

    }
    
    return $result;
  }
 /*
  * Gets the configuration information
  *
  * @param integer $id The configuration id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database;

    $Qcfg = $lC_Database->query('select * from :table_configuration where configuration_id = :configuration_id');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindInt(':configuration_id', $id);
    $Qcfg->execute();

    $result = $Qcfg->toArray();

    $Qcfg->freeResult();

    return $result;
  }
 /*
  * Saves the configuration information
  *
  * @param array $parameter The configuration key=>value
  * @access public
  * @return boolean
  */
  public static function save($parameter) {
    global $lC_Database;

    $Qcfg = $lC_Database->query('select configuration_id from :table_configuration where configuration_key = :configuration_key');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindValue(':configuration_key', key($parameter));
    $Qcfg->execute();

    if ( $Qcfg->numberOfRows() === 1 ) {
      $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value, last_modified = now() where configuration_key = :configuration_key');
      $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qupdate->bindValue(':configuration_value', $parameter[key($parameter)]);
      $Qupdate->bindValue(':configuration_key', key($parameter));
      $Qupdate->setLogging($_SESSION['module'], $Qcfg->valueInt('configuration_id'));
      $Qupdate->execute();

      if ( $Qupdate->affectedRows() ) {
        lC_Cache::clear('configuration');

        return true;
      }
    }

    return false;
  }
 /*
  * Get all the configuration groups
  *
  * @access public
  * @return array
  */
  public static function getAllGroups() {
    global $lC_Database;

    $Qgroups = $lC_Database->query('select * from :table_configuration_group where visible = 1 order by sort_order, configuration_group_title');
    $Qgroups->bindTable(':table_configuration_group', TABLE_CONFIGURATION_GROUP);
    $Qgroups->execute();

    $result = array('entries' => array());

    while ( $Qgroups->next() ) {
      $result['entries'][] = $Qgroups->toArray();
    }

    $result['total'] = $Qgroups->numberOfRows();

    $Qgroups->freeResult();

    return $result;
  }
 /*
  * Gets the configuration group title
  *
  * @param integer $id The configuration group id
  * @access public
  * @return string
  */
  public static function getGroupTitle($id) {
    global $lC_Database;

    $Qcg = $lC_Database->query('select configuration_group_title from :table_configuration_group where configuration_group_id = :configuration_group_id');
    $Qcg->bindTable(':table_configuration_group', TABLE_CONFIGURATION_GROUP);
    $Qcg->bindInt(':configuration_group_id', $id);
    $Qcg->execute();

    $result = $Qcg->value('configuration_group_title');

    $Qcg->freeResult();

    return $result;
  }
 /*
  * Gets the last config group id
  *
  * @param integer $id The configuration group id
  * @access public
  * @return string
  */
  public static function getMaxGroupId() {
    global $lC_Database;

    $Qcg = $lC_Database->query('select max(configuration_group_id) as last_id from :table_configuration_group');
    $Qcg->bindTable(':table_configuration_group', TABLE_CONFIGURATION_GROUP);
    $Qcg->execute();

    $result = $Qcg->valueInt('last_id');

    $Qcg->freeResult();

    return $result;
  }
 /*
  * Returns the configuration sub menu listing
  *
  * @access public
  * @return string
  */
  public static function drawMenu() {
    foreach ( lc_toObjectInfo(lC_Configuration_Admin::getAllGroups())->get('entries') as $group ) {
      $menu .= '<li class="message-menu" id="cfgGroup' . (int)$group['configuration_group_id'] . '">' .
               '  <span class="message-status" style="padding-top:14px;">' .
               '     <a href="javascript:void(0);" onclick="showGroup(\'' . (int)$group['configuration_group_id'] . '\', \'' . lc_output_string_protected($group['configuration_group_title']) . '\');" class="new-message" title=""></a>' .
               '   </span>' .
               '   <a id="cfgLink' . (int)$group['configuration_group_id'] . '" href="javascript:void(0);" onclick="showGroup(\'' . (int)$group['configuration_group_id'] . '\', \'' . str_replace("/", "-", lc_output_string_protected($group['configuration_group_title'])) . '\');">' .
               '     <br><strong>' . lc_output_string_protected($group['configuration_group_title']) . '</strong>' .
               '   </a>' .
               ' </li>';
    }

    return $menu;
  }



}
?>
