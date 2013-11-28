<?php
/*
  $Id: banner_manager.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Banner_manager_Admin class manages newsletters
*/
class lC_Banner_manager_Admin {
 /*
  * Returns the banners datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;
    
    $media = $_GET['media'];

    $Qbanners = $lC_Database->query('select banners_id, banners_title, banners_group, status from :table_banners order by banners_title, banners_group');
    $Qbanners->bindTable(':table_banners', TABLE_BANNERS);
    $Qbanners->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
    $Qbanners->execute();

    $result = array('aaData' => array());

    while ( $Qbanners->next() ) {
      $Qstats = $lC_Database->query('select sum(banners_shown) as banners_shown, sum(banners_clicked) as banners_clicked from :table_banners_history where banners_id = :banners_id');
      $Qstats->bindTable(':table_banners_history', TABLE_BANNERS_HISTORY);
      $Qstats->bindInt(':banners_id', $Qbanners->valueInt('banners_id'));
      $Qstats->execute();

      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qbanners->valueInt('banners_id') . '" id="' . $Qbanners->valueInt('banners_id') . '"></td>';
      $banners = '<td><a href="javascript:void(0);" onclick="showPreview(\'' . $Qbanners->valueInt('banners_id') . '\')"><span class="icon-eye with-tooltip" title="' . $lC_Language->get('icon_preview') . '"></span>&nbsp;' . $Qbanners->value('banners_title') . '</a></span>';  
      $group = '<td>' . $Qbanners->valueProtected('banners_group') . '</td>';
      $stats = '<td>' . $Qstats->valueInt('banners_shown') . ' / ' . $Qstats->valueInt('banners_clicked') . '</td>';
     
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editBanner(\'' . $Qbanners->valueInt('banners_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 2) ? '#' : 'javascript://" onclick="showStats(\'' . $Qbanners->valueInt('banners_id') . '\', \'' . urlencode($Qbanners->value('banners_title')) . '\')') . '" class="button icon-list with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 2) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_statistics') . '"></a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteBanner(\'' . $Qbanners->valueInt('banners_id') . '\', \'' . urlencode($Qbanners->value('banners_title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
                
      $result['aaData'][] = array("$check", "$banners", "$group", "$stats", "$action");
      $result['entries'][] = $Qbanners->toArray();
    }

    return $result;
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $id The banner id
  * @access public
  * @return array
  */
  public static function formData($id = '') {
    global $lC_Database;

    $result = array();
    $Qgroups = $lC_Database->query('select distinct banners_group from :table_banners order by banners_group');
    $Qgroups->bindTable(':table_banners', TABLE_BANNERS);
    $Qgroups->execute();
    $groups_array = array();
    while ( $Qgroups->next() ) {
      $groups_array[$Qgroups->value('banners_group')] = $Qgroups->value('banners_group');
    }
    $result['groupsArray'] = $groups_array;

    if ($id != null) {
      $result['bannerData'] = lC_Banner_manager_Admin::getData($id); 
    }

    return $result;
  }
 /*
  * Return banner information
  *
  * @param integer $id The banner id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database;

    $Qbanner = $lC_Database->query('select * from :table_banners where banners_id = :banners_id');
    $Qbanner->bindTable(':table_banners', TABLE_BANNERS);
    $Qbanner->bindInt(':banners_id', $id);
    $Qbanner->execute();

    $data = $Qbanner->toArray();

    $Qbanner->freeResult();

    return $data;
  }
 /*
  * Return newsletter preview information
  *
  * @param integer $id The banner id
  * @access public
  * @return array
  */
  public static function preview($id) {
    $result = array();
    $lC_ObjectInfo = new lC_ObjectInfo(lC_Banner_manager_Admin::getData($id));
    if ( !lc_empty($lC_ObjectInfo->get('banners_html_text')) ) {
      $result['banner'] = $lC_ObjectInfo->get('banners_html_text');
    } else {
      $result['banner'] = lc_image('../images/' . $lC_ObjectInfo->get('banners_image'), $lC_ObjectInfo->get('banners_title'));
    }
    $result['title'] = $lC_ObjectInfo->get('banners_title'); 

    return $result;
  }
 /*
  * Return newsletter stats information
  *
  * @param integer $id The newsletter id
  * @param string $type The newsletter type; yearly, monthly, daily
  * @param string $month The newsletter stats month
  * @param string $year The newsletter stats year
  * @access public
  * @return array
  */
  public static function stats($id, $type = '', $month = '', $year = '') {
    global $lC_Database, $lC_Language, $lC_Template, $lC_Vqmod;

    $lC_Language->loadIniFile('banner_manager.php');  

    $Qyears = $lC_Database->query('select distinct year(banners_history_date) as banner_year from :table_banners_history where banners_id = :banners_id');
    $Qyears->bindTable(':table_banners_history', TABLE_BANNERS_HISTORY);
    $Qyears->bindInt(':banners_id', $id);
    $Qyears->execute();
    $years_array = array();
    while ( $Qyears->next() ) {
      $years_array[] = array('id' => $Qyears->valueInt('banner_year'),
                             'text' => $Qyears->valueInt('banner_year'));
    }
    if ($years_array[0] == null) {
      $years_array[] = array('id' => @date("Y"),
                             'text' => @date("Y"));
    }
    $Qyears->freeResult();
    $months_array = array();
    for ( $i = 1; $i < 13; $i++ ) {
      $months_array[] = array('id' => $i,
                              'text' => @strftime('%B', @mktime(0,0,0,$i)));
    }

    $type_array = array(array('id' => 'daily',
                              'text' => $lC_Language->get('section_daily')),
                        array('id' => 'monthly',
                              'text' => $lC_Language->get('section_monthly')),
                        array('id' => 'yearly',
                              'text' => $lC_Language->get('section_yearly')));

    $lC_ObjectInfo = new lC_ObjectInfo(lC_Banner_manager_Admin::getData($id));
     
    $result['formElements'] = $lC_Language->get('operation_heading_type') . ' ' . lc_draw_pull_down_menu('type', $type_array, 'daily', 'onchange="updateStats(\'' . $id . '\');"') . ' ';
    switch ( $type ) {
      case 'yearly':
        break;

      case 'monthly':
        $result['formElements'] .= $lC_Language->get('operation_heading_year') . ' ' . lc_draw_pull_down_menu('year', $years_array, @date('Y'), 'onchange="updateStats(\'' . $id . '\');"');
        break;

      case 'daily':
      default:
        $result['formElements'] .= $lC_Language->get('operation_heading_month') . ' ' . lc_draw_pull_down_menu('month', $months_array, @date('n'), 'onchange="updateStats(\'' . $id . '\');"') . ' ' .
                                   $lC_Language->get('operation_heading_year') . ' ' . lc_draw_pull_down_menu('year', $years_array, @date('Y'), 'onchange="updateStats(\'' . $id . '\');"');
        break;
    }

    $result['graphInfo'] = '';
    $image_extension = lc_dynamic_image_extension();
    if ($year == null) $year = @date("Y");
    if ($month == null) $month = @date("n");
    if ( is_dir('images/graphs') && is_writeable('images/graphs') && !empty($image_extension) ) {
      switch ( $type ) {
        case 'yearly':
          include($lC_Vqmod->modCheck('includes/graphs/banner_yearly.php'));
          $stats = lc_banner_yearly((int)$id);
          $result['graphInfo'] = '<p align="center">' . lc_image('images/graphs/banner_yearly-' . $id . '.' . $image_extension) . '</p>';
          break;

        case 'monthly':
          include($lC_Vqmod->modCheck('includes/graphs/banner_monthly.php'));
          $stats = lc_banner_monthly((int)$id, (int)$year);
          $result['graphInfo'] = '<p align="center">' . lc_image('images/graphs/banner_monthly-' . $id . '.' . $image_extension) . '</p>';
          break;

        case 'daily':
        default:
          include($lC_Vqmod->modCheck('includes/graphs/banner_daily.php'));
          $stats = lc_banner_daily((int)$id, (int)$month, (int)$year);
          $result['graphInfo'] = '<p align="center">' . lc_image('images/graphs/banner_daily-' . $id . '_' . $month . '.' . $image_extension) . '</p>';
          break;
      }
    } else {
      if ( !empty($image_extension) ) {
        if ( is_dir('images/graphs') ) {
          if ( !is_writeable('images/graphs') ) {
            $result['error'] = true;                                                                                              
            $result['errmsg'] = sprintf($lC_Language->get('ms_error_graphs_directory_not_writable'), realpath('images/graphs'));
          }
        } else {
          $result['error'] = true;
          $result['errmsg'] = sprintf($lC_Language->get('ms_error_graphs_directory_non_existant'), realpath('images/graphs'));
        }
      }

    }

    $result['bannerStats'] = '';
    if ( isset($stats) ) {
      for ( $i = 0, $n = sizeof($stats); $i < $n; $i++ ) {
        $result['bannerStats'] .= '<tr>' .
                                  '  <td>' . $stats[$i][0] . '</td>' .
                                  '  <td>' . number_format($stats[$i][1]) . '</td>' .
                                  '  <td>' . number_format($stats[$i][2]) . '</td>' .
                                  '</tr>';
      }
    }

    return $result;
  }
 /*
  * Save the banner information
  *
  * @param integer $id The banner id to update, null on insert
  * @param array $data The banner information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database;
    
    $error = false;

    if ( empty($data['html_text']) && empty($data['image_local']) && !empty($data['image']) ) {

      if(!file_exists(realpath('../images/' . $data['image_target']))){
        mkdir('../images/'.$data['image_target'].'/', 0777);
        $path = realpath('../images/'.$data['image_target']);
        $insert_path = $data['image_target'].'/';
      }else{
        $path = realpath('../images/banners/');
        $insert_path = 'banners/';
      }

      // Remove existing image with the same name.
      if(  file_exists($path.'/'.$data['image']['name'])){
        unlink($path.'/'.$data['image']['name']);
      }

      $image = new upload($data['image'], $path);

      if ( !$image->exists() || !$image->parse() || !$image->save() ) {
        $error = true;
      }
    }
    
    if ( $error === false ) {
      $image_location = (!empty($data['image_local']) ? $data['image_local'] : (isset($image) ? $insert_path . $image->filename : null));

      if ( is_numeric($id) ) {
        $Qbanner = $lC_Database->query('update :table_banners set banners_title = :banners_title, banners_url = :banners_url, banners_target = :banners_target, banners_image = :banners_image, banners_group = :banners_group, banners_html_text = :banners_html_text, expires_date = :expires_date, expires_impressions = :expires_impressions, date_scheduled = :date_scheduled, status = :status where banners_id = :banners_id');
        $Qbanner->bindInt(':banners_id', $id);
      } else {
        $Qbanner = $lC_Database->query('insert into :table_banners (banners_title, banners_url, banners_target, banners_image, banners_group, banners_html_text, expires_date, expires_impressions, date_scheduled, status, date_added) values (:banners_title, :banners_url, :banners_target, :banners_image, :banners_group, :banners_html_text, :expires_date, :expires_impressions, :date_scheduled, :status, now())');
      }
      
      $Qbanner->bindTable(':table_banners', TABLE_BANNERS);
      $Qbanner->bindValue(':banners_title', $data['title']);
      $Qbanner->bindValue(':banners_url', $data['url']);
      $Qbanner->bindInt(':banners_target', (($data['target'] === true) ? 1 : 0));
      $Qbanner->bindValue(':banners_image', $image_location);
      $Qbanner->bindValue(':banners_group', (!empty($data['group_new']) ? $data['group_new'] : $data['group']));
      $Qbanner->bindValue(':banners_html_text', $data['html_text']);
      
      if ( empty($data['date_expires']) ) {
        $Qbanner->bindRaw(':expires_date', 'null');
        $Qbanner->bindInt(':expires_impressions', $data['expires_impressions']);
      } else {
        $Qbanner->bindValue(':expires_date', lC_DateTime::toDateTime($data['date_expires']));
        $Qbanner->bindInt(':expires_impressions', 0);
      }

      if ( empty($data['date_scheduled']) ) {
        $Qbanner->bindRaw(':date_scheduled', 'null');
        $Qbanner->bindInt(':status', (($data['status'] === true) ? 1 : 0));
      } else {
        $Qbanner->bindValue(':date_scheduled', lC_DateTime::toDateTime($data['date_scheduled']));
        $Qbanner->bindInt(':status', (lC_DateTime::toDateTime($data['date_scheduled']) > @date('Y-m-d') ? 0 : (($data['status'] === true) ? 1 : 0)));
      }
      
      $Qbanner->setLogging($_SESSION['module'], $id);
      $Qbanner->execute();

      if ( !$lC_Database->isError() ) {
        return true;
      }
    }

    return false;
  }
 /*
  * Delete the banner record
  *
  * @param integer $id The banner id to delete
  * @param boolean $delete_image True = delete the banner image
  * @access public
  * @return boolean
  */
  public static function delete($id, $delete_image = false) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    if ( $delete_image === true ) {
      $Qimage = $lC_Database->query('select banners_image from :table_banners where banners_id = :banners_id');
      $Qimage->bindTable(':table_banners', TABLE_BANNERS);
      $Qimage->bindInt(':banners_id', $id);
      $Qimage->execute();
    }

    $Qdelete = $lC_Database->query('delete from :table_banners where banners_id = :banners_id');
    $Qdelete->bindTable(':table_banners', TABLE_BANNERS);
    $Qdelete->bindInt(':banners_id', $id);
    $Qdelete->setLogging($_SESSION['module'], $id);
    $Qdelete->execute();

    if ( $lC_Database->isError() ) {
      $error = true;
    }

    if ( $error === false) {
      $Qdelete = $lC_Database->query('delete from :table_banners_history where banners_id = :banners_id');
      $Qdelete->bindTable(':table_banners_history', TABLE_BANNERS_HISTORY);
      $Qdelete->bindInt(':banners_id', $id);
      $Qdelete->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      if ( $delete_image === true ) {
        if ( !lc_empty($Qimage->value('banners_image')) ) {
          if ( is_file('../images/' . $Qimage->value('banners_image')) && is_writeable('../images/' . $Qimage->value('banners_image')) ) {
            @unlink('../images/' . $Qimage->value('banners_image'));
          }
        }
      }

      $image_extension = lc_dynamic_image_extension();

      if ( !empty($image_extension) ) {
        if ( is_file('images/graphs/banner_yearly-' . $id . '.' . $image_extension) && is_writeable('images/graphs/banner_yearly-' . $id . '.' . $image_extension) ) {
          @unlink('images/graphs/banner_yearly-' . $id . '.' . $image_extension);
        }

        if ( is_file('images/graphs/banner_monthly-' . $id . '.' . $image_extension) && is_writeable('images/graphs/banner_monthly-' . $id . '.' . $image_extension) ) {
          @unlink('images/graphs/banner_monthly-' . $id . '.' . $image_extension);
        }

        if ( is_file('images/graphs/banner_daily-' . $id . '.' . $image_extension) && is_writeable('images/graphs/banner_daily-' . $id . '.' . $image_extension) ) {
          unlink('images/graphs/banner_daily-' . $id . '.' . $image_extension);
        }
      }

      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Batch delete banner records
  *
  * @param array $batch The banner id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Banner_manager_Admin::delete($id);
    }
    return true;
  }
}
?>