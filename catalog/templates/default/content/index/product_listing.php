<?php
/*
  $Id: product_listing.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/ 
?>
<style>
button.price_add {
  margin-top: 20px;
}
</style>
<div class="full_page">
  <!--PRODUCTS CONTENT STARTS-->
  <div class="content">
    <!-- h1><?php echo $lC_Template->getPageTitle(); ?></h1 --> 
    <!--PRODUCTS LISTING STARTS-->
    <div class="products_list products_slider">
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
      if (file_exists(DIR_FS_TEMPLATE . 'modules/product_listing.php')) {
        require(DIR_FS_TEMPLATE . 'modules/product_listing.php');
      } else {
        require('includes/modules/product_listing.php');
      }
    ?>
    </div>
    <!--Product List Ends-->
  </div>
</div>
<script>
  $(function(){
    $('#listView').click(function(){
        $('#viewList').show();
        $('#viewGrid').hide();
    });
    $('#gridView').click(function(){
        $('#viewGrid').show();
        $('#viewList').hide();
    });
  });
</script>