<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_classes.js.php v1.0 2013-08-08 datazen $
*/
?>
<script>
$(document).ready(function() {  
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll'); ?>';   
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL,
    "sPaginationType": paginationType,    
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
    "aoColumns": [{ "sWidth": "16px", "bSortable": false, "sClass": "dataColCheck" },
                  { "sWidth": "85%", "bSortable": true, "sClass": "dataColItem" },
                  { "sWidth": "15%", "bSortable": false, "sClass": "dataColAction" }]
  });
});
</script>