<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: template.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template;
?>
<script>
$(document).ready(function() {
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA'); ?>';
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType,
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "aoColumns": [{ "sWidth": "120px", "bSortable": false, "sClass": "dataColImage" },
                  { "sWidth": "80%", "bSortable": false, "sClass": "dataColTemplates hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
  });
  $('#dataTable').responsiveTable();
       
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
    $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
    $('#dataTable_length').hide();
    $('#actionText').hide();
  } 
  var action = '<?php echo ((isset($_GET['action']) && $_GET['action'] != NULL) ? $_GET['action'] : ''); ?>';
  if (action == 'edit') {
    var id = '<?php echo ((isset($_GET['code']) && $_GET['code'] != NULL) ? end(explode('_', $_GET['code'])) : ''); ?>';
    editTemplate(id);
    return;
  }       
});
</script>