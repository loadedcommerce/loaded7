<?php
/*
  $Id: updates.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template;
?>
<script>
$(document).ready(function() {
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getHistory&media=MEDIA'); ?>';   
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "bPaginate": true,
    "bLengthChange": false,
    "bFilter": false,
    "bSort": true,
    "bInfo": false,
    "aaSorting": [[3,'desc']],
    "aoColumns": [{ "sWidth": "20%", "bSortable": true, "sClass": "dataColAction" },
                  { "sWidth": "40%", "bSortable": true, "sClass": "dataColResult hide-on-mobile-portrait" },
                  { "sWidth": "15%", "bSortable": true, "sClass": "dataColUser" },
                  { "sWidth": "25%", "bSortable": true, "sClass": "dataColTime hide-on-mobile-portrait" }]
  });
  $('#dataTable').responsiveTable();
});  
</script>