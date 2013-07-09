<?php
/*
  $Id: main.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
.dataColItem { text-align: left; } 
.dataColAction { text-align: right; }  
</style>
<div class="pageContainer">
  <div class="pageTitle"><h1><?php echo $lC_Template->getPageTitle(); ?></h1></div>
  <div class="status"><?php echo lc_status(); ?></div>
  <form name="batch" id="batch" action="#" method="post">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="display" id="exampleDataTable">
    <thead>
      <tr>
        <th align="left"><?php echo $lC_Language->get('table_heading_item'); ?></th>
        <th>
          <div id="actionContainerTop">
            <ul>
              <li><a href="javascript://" style="cursor:pointer" onclick="newItem(); return false;"><img src="<?php echo lc_icon_raw('new_file.png', '24x24'); ?>" border="0" title="<?php echo $lC_Language->get('dialog_heading_new_item'); ?>"></a></li> 
              <li><a href="javascript://" onclick="batchDelete(); return false;"><img src="<?php echo lc_icon_raw('trash.png'); ?>" title="<?php echo $lC_Language->get('icon_delete_batch'); ?>" border="0" /></a></li>
              <li><a href="javascript://" onclick="flagCheckboxes();"><img src="<?php echo lc_icon_raw('check_all.png'); ?>" title="<?php echo $lC_Language->get('icon_check_all'); ?>" border="0" /></li>
            </ul>
          </div>      
        </th>
      </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="2">
          <?php echo lc_legend(array('edit', 'trash')); ?>
          <div id="actionContainerBottom">
            <ul>
              <li><a href="javascript://" onclick="batchDelete(); return false;"><img src="<?php echo lc_icon_raw('trash.png'); ?>" title="<?php echo $lC_Language->get('icon_delete_batch'); ?>" border="0" /></a></li>
              <li><a href="javascript://" onclick="flagCheckboxes();"><img src="<?php echo lc_icon_raw('check_all.png'); ?>" title="<?php echo $lC_Language->get('icon_check_all'); ?>" border="0" /></li>
            </ul>
          </div>          
        </th>
      </tr>
    </tfoot> 
  </table>
  </form>
  <script type="text/javascript" charset="utf-8">
  $(document).ready(function() {  
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll'); ?>';   
    oTable = $('#exampleDataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL,
      "bJQueryUI": false,  
      "sDom": 'T<"clear"><"H"fl>rt<"F"ip<"clear">',
      "sPaginationType": "full_numbers", 
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
      "aoColumns": [{ "sWidth": "800px", "bSortable": true, "sClass": "dataColItem" },
                    { "sWidth": "140px", "bSortable": false, "sClass": "dataColAction" }]
    });
    $("#exampleDataTable_wrapper .ui-widget-header").removeClass('ui-widget-header');
  });
  </script>
</div>
<?php $lC_Template->loadDialog($lC_Template->getModule()); ?>