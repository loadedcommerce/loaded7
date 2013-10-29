<?php
/*
  $Id: main.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<?php
$OrderstatusArray = array();
$OrderstatusArray[] = array('id' => 0, 'text' => 'All');
foreach(lC_Orders_Admin::getOrderstatusArray() as $id => $text) {
  $OrderstatusArray[] = array('id' => $id, 'text' => $text);
}
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
  .dataColCheck { text-align: center; }  
  .dataColOID { text-align: left; } 
  .dataColName { text-align: left; } 
  .dataColCountry { text-align: left; } 
  .dataColItems { text-align: left; } 
  .dataColOTotal { text-align: left; } 
  .dataColDate { text-align: center; } 
  .dataColTime { text-align: center; } 
  .dataColStatus { text-align: left; } 
  .dataColAction { text-align: right; } 
  .dataTables_info { position:absolute; bottom: 42px; color:#4c4c4c; }
  .selectContainer { position:absolute; bottom:29px; left:30px }  
  </style>
  <div class="with-padding-no-top">
   <div id="templateSelectorContainer" style="position:absolute; top:98px; left:190px; z-index:2;"><div id="templateSelector"><form name="orders_status_batch" id="orders_status_batch" action="" onchange="updateOrderList();"><?php echo lc_draw_pull_down_menu('filter', $OrderstatusArray, null, 'class="input with-small-padding"');?></form></div></div>
    <form name="batch" id="batch" action="#" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
      <thead>
        <tr>
          <th scope="col" class="hide-on-mobile align-left"><input onclick="toggleCheck();" id="check-all" type="checkbox" value="1" name="check-all"></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_oid'); ?></th>
          <th scope="col" class="align-left hide-on-mobile-portrait"><?php echo $lC_Language->get('table_heading_customers'); ?></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_country'); ?></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_items'); ?></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_order_total'); ?></th>
          <th scope="col" class="align-center hide-on-tablet no-wrap"><?php echo $lC_Language->get('table_heading_date_purchased'); ?></th>
          <th scope="col" class="align-center hide-on-tablet"><?php echo $lC_Language->get('table_heading_time'); ?></th>
          <th scope="col" class="align-left hide-on-mobile"><?php echo $lC_Language->get('table_heading_status'); ?></th>
          <th scope="col" class="align-right"><span class="button-group compact"><a href="javascript:void(0);" style="cursor:pointer" onclick="oTable.fnReloadAjax();" class="button with-tooltip icon-redo blue" title="<?php echo $lC_Language->get('button_refresh'); ?>"></a></span><span id="actionText">&nbsp;&nbsp;<?php echo $lC_Language->get('table_heading_action'); ?></span></th>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="10">&nbsp;</th>
        </tr>
      </tfoot>
    </table>
    <div class="selectContainer">
      <select <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onchange="batchDelete();"'); ?> name="selectAction" id="selectAction" class="select blue-gradient glossy<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>">
        <option value="0" selected="selected">With Selected</option>
        <option value="delete">Delete</option>
      </select>
    </div>    
    </form>
    <div class="clear-both"></div>
  </div>
</section>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<!-- End main content -->