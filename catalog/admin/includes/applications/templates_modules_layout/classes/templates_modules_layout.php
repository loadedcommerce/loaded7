<?php
/*
  $Id: templates_modules_layout.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Templates_modules_layout_Admin class manages templates modules layout
*/
class lC_Templates_modules_layout_Admin {
 /*
  * Returns the templates modules layout datatable data for listings
  *
  * @param string $filter The template name
  * @access public
  * @return array
  */
  public static function getAll($filter) {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $filterArray = lC_Templates_modules_layout_Admin::getFilterArray($filter);

    $Qlayout = $lC_Database->query('select b2p.*, b.title as box_title from :table_templates_boxes_to_pages b2p, :table_templates_boxes b where b2p.templates_id = :templates_id and b2p.templates_boxes_id = b.id and b.modules_group = :modules_group order by b2p.page_specific desc, b2p.boxes_group, b2p.sort_order, b.title');
    $Qlayout->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
    $Qlayout->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qlayout->bindInt(':templates_id', $filterArray['filter_id']);
    $Qlayout->bindValue(':modules_group', $_GET['set']);
    $Qlayout->execute();

    $result = array('aaData' => array());
    $result['filterArray'] = $filterArray;
    while ( $Qlayout->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qlayout->valueInt('id') . '" id="' . $Qlayout->valueInt('id') . '"></td>';
      $modules = '<td>' . $Qlayout->value('box_title') . '</td>';
      $pages = '<td>' . $Qlayout->value('content_page') . '</td>';
      $specific = '<td>' . (($Qlayout->valueInt('page_specific') === 1) ? '<span class="icon-tick icon-green icon-size2">' : '<span class="icon-cross icon-red icon-size2">') . '</span></td>';
      $group = '<td>' . (($Qlayout->value('boxes_group') == 'left')   ? '<small class="tag red-bg">' . $Qlayout->value('boxes_group') : (($Qlayout->value('boxes_group') != 'left') ? '<small class="tag orange-bg">' . $Qlayout->value('boxes_group') : '<small class="tag">' . $Qlayout->value('boxes_group'))) . '</small></td>';
      $sort = '<td>' . $Qlayout->valueInt('sort_order') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['templates_modules_layout'] < 3) ? '#' : 'javascript://" onclick="editModule(\'' . $Qlayout->valueInt('id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['templates_modules_layout'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['templates_modules_layout'] < 4) ? '#' : 'javascript://" onclick="deleteModule(\'' . $Qlayout->valueInt('id') . '\', \'' . urlencode($Qlayout->valueProtected('box_title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['templates_modules_layout'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$modules", "$pages", "$specific", "$group", "$sort", "$action");
      $result['entries'][] = $Qlayout->toArray();
    }

    $Qlayout->freeResult();

    return $result;
  }
 /*
  * Return the template module layout information
  *
  * @param integer $id The template module layout id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language, $lC_Vqmod;

    $lC_Language->loadIniFile('templates_modules_layout.php');

    $result = array();
    $Qboxes = $lC_Database->query('select id, title from :table_templates_boxes where modules_group = :modules_group order by title');
    $Qboxes->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qboxes->bindValue(':modules_group', $_GET['set']);
    $Qboxes->execute();
    while ( $Qboxes->next() ) {
      $result['boxes_array'][$Qboxes->valueInt('id')] = $Qboxes->value('title');
    }

    $Qtemplate = $lC_Database->query('select id from :table_templates where code = :code');
    $Qtemplate->bindTable(':table_templates', TABLE_TEMPLATES);
    $Qtemplate->bindValue(':code', $_GET['filter']);
    $Qtemplate->execute();
    $filter_id = $Qtemplate->valueInt('id');

    $pages_array = array(array('id' => $filter_id . '/*',
                               'text' => '*'));
    $d_boxes = new lC_DirectoryListing('../templates/' . $_GET['filter'] . '/content');
    $d_boxes->setRecursive(true);
    $d_boxes->setAddDirectoryToFilename(true);
    $d_boxes->setCheckExtension('php');
    $d_boxes->setExcludeEntries('.svn');
    foreach ( $d_boxes->getFiles(false) as $box ) {
      if ( $box['is_directory'] === true ) {
        $entry = array('id' => $filter_id . '/' . $box['name'] . '/*',
                       'text' => $box['name'] . '/*');
      } else {
        $page_filename = substr($box['name'], 0, strrpos($box['name'], '.'));
        $entry = array('id' => $filter_id . '/' . $page_filename,
                       'text' => $page_filename);
      }
      if ( ( $_GET['filter'] != DEFAULT_TEMPLATE ) && ( $d_boxes->getSize() > 0 ) ) {
        $entry['group'] = '-- ' . $_GET['filter'] . ' --';
      }
      $pages_array[] = $entry;
    }
    if ( $_GET['filter'] != DEFAULT_TEMPLATE ) {
      $d_boxes = new lC_DirectoryListing('../templates/' . DEFAULT_TEMPLATE . '/content');
      $d_boxes->setRecursive(true);
      $d_boxes->setAddDirectoryToFilename(true);
      $d_boxes->setCheckExtension('php');
      $d_boxes->setExcludeEntries('.svn');
      foreach ( $d_boxes->getFiles(false) as $box ) {
        if ( $box['is_directory'] === true ) {
          $entry = array('id' => $filter_id . '/' . $box['name'] . '/*',
                         'text' => $box['name'] . '/*');
        } else {
          $page_filename = substr($box['name'], 0, strrpos($box['name'], '.'));
          $entry = array('id' => $filter_id . '/' . $page_filename,
                         'text' => $page_filename);
        }
        $check_entry = $entry;
        $check_entry['group'] = '-- ' . $_GET['filter'] . ' --';
        if ( !in_array($check_entry, $pages_array) ) {
          $entry['group'] = '-- ' . DEFAULT_TEMPLATE . ' --';
          $pages_array[] = $entry;
        }
      }
    }
    $result['pages_array'] = $pages_array;

    require($lC_Vqmod->modCheck('includes/templates/' . $_GET['filter'] . '.php'));

    $class = 'lC_Template_' . $_GET['filter'];
    $filter_template = new $class();
    $groups_array = array();
    foreach ( $filter_template->getGroups($_GET['set']) as $group ) {
      $groups_array[] = array('id' => $group,
                              'text' => $group);
    }
    $Qgroups = $lC_Database->query('select distinct b2p.boxes_group from :table_templates_boxes_to_pages b2p, :table_templates_boxes b where b2p.templates_id = :templates_id and b2p.templates_boxes_id = b.id and b.modules_group = :modules_group and b2p.boxes_group not in (:boxes_group) order by b2p.boxes_group');
    $Qgroups->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
    $Qgroups->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qgroups->bindInt(':templates_id', $filter_id);
    $Qgroups->bindValue(':modules_group', $_GET['set']);
    $Qgroups->bindRaw(':boxes_group', '"' . implode('", "', $filter_template->getGroups($_GET['set'])) . '"');
    $Qgroups->execute();
    while ( $Qgroups->next() ) {
      if ($Qgroups->value('boxes_group') == null) continue;
      $groups_array[] = array('id' => $Qgroups->value('boxes_group'),
                              'text' => $Qgroups->value('boxes_group'));
    }
    if ( !empty($groups_array) ) {
      array_unshift($groups_array, array('id' => null, 'text' => $lC_Language->get('please_select')));
      $result['groups_dropdown'] = lc_draw_pull_down_menu('group', $groups_array, null, 'class="input with-small-padding"') . '&nbsp;&nbsp;';
    }

    if ($id != null) {
      $Qlayout = $lC_Database->query('select b2p.*, b.id as box_id, b.title as box_title, b.code as box_code from :table_templates_boxes_to_pages b2p, :table_templates_boxes b where b2p.id = :id and b2p.templates_boxes_id = b.id');
      $Qlayout->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
      $Qlayout->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
      $Qlayout->bindInt(':id', $id);
      $Qlayout->execute();

      $result['modules_selected'] = $Qlayout->value('box_id');
      $result['pages_selected'] = $Qlayout->valueInt('templates_id') . '/' . $Qlayout->value('content_page');
      $result['page_specific'] = $Qlayout->valueInt('page_specific') === 1 ? true : false;
      $result['group_selected'] = $Qlayout->value('boxes_group');
      $result['sort_order'] = $Qlayout->valueInt('sort_order');

      $Qlayout->freeResult;
    }

    return $result;
  }
 /*
  * Save the template module layout information
  *
  * @param integer $id The template module layout id used on update, null on insert
  * @access public
  * @return boolean
  */
  public static function save($id = null) {
    global $lC_Database;

    $link = explode('/', $_GET['content_page'], 2);
    $group = (isset($_GET['group']) && $_GET['group'] != null) ? $_GET['group'] : $_GET['group_new'];

    if ( is_numeric($id) ) {
      $Qlayout = $lC_Database->query('update :table_templates_boxes_to_pages set content_page = :content_page, boxes_group = :boxes_group, sort_order = :sort_order, page_specific = :page_specific where id = :id');
      $Qlayout->bindInt(':id', $id);
    } else {
      $Qlayout = $lC_Database->query('insert into :table_templates_boxes_to_pages (templates_boxes_id, templates_id, content_page, boxes_group, sort_order, page_specific) values (:templates_boxes_id, :templates_id, :content_page, :boxes_group, :sort_order, :page_specific)');
      $Qlayout->bindInt(':templates_boxes_id', $_GET['box']);
      $Qlayout->bindInt(':templates_id', $link[0]);
    }

    $Qlayout->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
    $Qlayout->bindValue(':content_page', $link[1]);
    $Qlayout->bindValue(':boxes_group', $group);
    $Qlayout->bindInt(':sort_order', $_GET['sort_order']);
    $Qlayout->bindInt(':page_specific', ($_GET['page_specific'] == 'on') ? '1' : '0');
    $Qlayout->setLogging($_SESSION['module'], $id);
    $Qlayout->execute();

    if ( !$lC_Database->isError() ) {
      lC_Cache::clear('templates_' . $_GET['set'] . '_layout');

      return true;
    }

    return false;
  }
 /*
  * Delete the template module layout
  *
  * @param integer $id The template module layout id
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $Qdel = $lC_Database->query('delete from :table_templates_boxes_to_pages where id = :id');
    $Qdel->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
    $Qdel->bindInt(':id', $id);
    $Qdel->setLogging($_SESSION['module'], $id);
    $Qdel->execute();

    if ( !$lC_Database->isError() ) {
      lC_Cache::clear('templates_' . $_GET['set'] . '_layout');

      return true;
    }

    return false;
  }
 /*
  * Batch delete template module layouts
  *
  * @param array $batch The template module layout id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Templates_modules_layout_Admin::delete($id);
    }
    return true;
  }
 /*
  * Returns the template module layout data
  *
  * @param string $filter The template module layout name
  * @access public
  * @return array
  */
  public static function getTemplatesArray($filter) {

   $filterArray = lC_Templates_modules_layout_Admin::getFilterArray($filter);

   return $filterArray;
  }
 /*
  * Returns the template module layout data
  *
  * @param string $filter The template module layout name
  * @access private
  * @return array
  */
  private static function getFilterArray($name) {
    global $lC_Database, $lC_Vqmod;

    $filter_id = 0;
    $filter_array = array();
    $templates_array = array();
    if (file_exists('includes/templates/' . $name . '.php')) {
      require($lC_Vqmod->modCheck('includes/templates/' . $name . '.php'));
      $Qtemplates = $lC_Database->query('select id, title, code from :table_templates order by title');
      $Qtemplates->bindTable(':table_templates', TABLE_TEMPLATES);
      $Qtemplates->execute();
      while ( $Qtemplates->next() ) {
        if ( $Qtemplates->value('code') == $name ) {
          $filter_id = $Qtemplates->valueInt('id');
        }
        $templates_array[$Qtemplates->value('code')] = $Qtemplates->value('title');
      }
    }
    $filter_array['filter_id'] = $filter_id;
    $filter_array['filter_name'] = $name;
    $filter_array['template_array'] = $templates_array;

    return $filter_array;
  }
}
?>