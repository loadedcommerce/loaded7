<?php
/*
  $Id: product_listing.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/ 
?>
<div class="contentBox">
  <div class="top" style="position:relative;">
    <span style="float:right;"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'content_header_right.png'); ?></span> 
    <span style="float:left;"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'content_header_left.png'); ?>
      <span class="titleGroup">
        <span class="icon"><?php echo lc_icon('icon_products.png', $lC_Template->getPageTitle()); ?></span>
        <span class="title"><?php echo $lC_Template->getPageTitle(); ?></span>
      </span>
    </span>
  </div>

  <div id="filterToolsContainer">
    <div id="listingModesContainer">
      <div id="listingModesBlock">
        <span>
          <a id="viewAsGrid" href="javascript://" onclick="toggleView('grid'); return false;" title="View as Grid"></a>
          <a id="viewAsList" href="javascript://" onclick="toggleView('list'); return false;" title="View as List"></a>
        </span>
      </div>
    </div><div style="clear:both;"></div> 
  </div>
  <script>
    $(document).ready(function() {
      toggleView('grid');
    });

    function toggleView(mode) {
      if (mode == 'grid') {
        $('#viewAsGrid').addClass('selected');
        $('#viewAsList').removeClass('selected');
        $('#viewGrid').removeClass('hidden');
        $('#viewList').addClass('hidden');
      } else {
        $('#viewAsGrid').removeClass('selected');
        $('#viewAsList').addClass('selected');
        $('#viewGrid').addClass('hidden');
        $('#viewList').removeClass('hidden');
      }
    }


  </script>

  <div class="content"> 

<style>
.hidden { display:none; }
#productListingContainer { border:1px dashed red; }

#viewGridTable { width:100%; }
#viewGridTable TD { width:33%; text-align:center; } 

#viewListTable { width:100%; } 
#viewListTable TD { width:100% } 



</style>


    <span>
      <?php 
      // optional Product List Filter
      if (PRODUCT_LIST_FILTER > 0) {
        if (isset($_GET['manufacturers']) && !empty($_GET['manufacturers'])) {
          $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_TEMPLATES_BOXES . " tb, " . TABLE_PRODUCT_ATTRIBUTES . " pa where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$lC_Language->getID() . "' and tb.code = 'manufacturers' and tb.id = pa.id and pa.products_id = p.products_id and pa.value = '" . (int)$_GET['manufacturers'] . "' order by cd.categories_name";
        } else {
          $filterlist_sql = "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
        }
        $Qfilterlist = $lC_Database->query($filterlist_sql);
        $Qfilterlist->execute();
        if ($Qfilterlist->numberOfRows() > 1) {
          echo '<p><form name="filter" action="' . lc_href_link(FILENAME_DEFAULT) . '" method="get">' . $lC_Language->get('filter_show') . '&nbsp;';
          if (isset($_GET['manufacturers']) && !empty($_GET['manufacturers'])) {
            echo lc_draw_hidden_field('manufacturers', $_GET['manufacturers']);
            $options = array(array('id' => '', 'text' => $lC_Language->get('filter_all_categories')));
          } else {
            echo lc_draw_hidden_field('cPath', $cPath);
            $options = array(array('id' => '', 'text' => $lC_Language->get('filter_all_manufacturers')));
          }
          if (isset($_GET['sort'])) {
            echo lc_draw_hidden_field('sort', $_GET['sort']);
          }
          while ($Qfilterlist->next()) {
            $options[] = array('id' => $Qfilterlist->valueInt('id'), 'text' => $Qfilterlist->value('name'));
          }
          echo lc_draw_pull_down_menu('filter', $options, (isset($_GET['filter']) ? $_GET['filter'] : null), 'onchange="this.form.submit()"');
          echo lc_draw_hidden_session_id_field() . '</form></p>' . "\n";
        }
      }
      if (isset($_GET['manufacturers']) && !empty($_GET['manufacturers'])) {
        $lC_Products->setManufacturer($_GET['manufacturers']);
      }
      $Qlisting = $lC_Products->execute();
      if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/product_listing.php')) {
        require(DIR_FS_TEMPLATE . 'includes/modules/product_listing.php');
      } else {
        require('includes/modules/product_listing.php');
      }
      ?>
    </span>
  </div>
  <div class="bottom">
    <span style="float:right;"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'content_bottom_right.png'); ?></span> 
    <span style="float:left;"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'content_bottom_left.png'); ?></span>
  </div>
</div>
<div style="clear:both;"></div>
<div class="buttonSet" style="margin-top:0;"><a href="javascript: history.go(-1)" class="button"><span class="buttonText"><?php echo $lC_Language->get('button_back'); ?></span></a></div>      