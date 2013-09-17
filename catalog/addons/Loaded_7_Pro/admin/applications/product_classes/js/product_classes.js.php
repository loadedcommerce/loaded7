<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_classes.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template;
?>
<script>
$(document).ready(function() {  
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';              
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA&addon=Loaded_7_Pro'); ?>';   
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType,    
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
    "aaSorting": [[0,'asc']],
    "aoColumns": [{ "sWidth": "25%", "bSortable": false, "sClass": "dataColName" },
                  { "sWidth": "35%", "bSortable": true, "sClass": "dataColComment hide-on-mobile" },
                  { "sWidth": "25%", "bSortable": true, "sClass": "dataColUsage hide-on-mobile" },
                  { "sWidth": "15%", "bSortable": false, "sClass": "dataColAction" }]
  });
  $('#dataTable').responsiveTable();
  
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
    $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
    $('#dataTable_length').hide();
    $('#floating-button-container').hide();
    $('#actionText').hide();
    $('.on-mobile').show();
  }  
});
</script>