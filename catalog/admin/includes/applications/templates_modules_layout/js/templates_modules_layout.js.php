<?php
/*
  $Id: templates_modules.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language;
?>
<script>
$(document).ready(function() {
  updateTemplateSelector();
});

function updateTemplateSelector() {
  var filter = $("#filter").val();
  if (filter == null) filter = '<?php echo DEFAULT_TEMPLATE; ?>';
  var set = '<?php echo $_GET['set']; ?>';
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA&set=SET&filter=FILTER'); ?>';
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getTemplatesArray&filter=FILTER'); ?>';
  $.getJSON(jsonLink.replace('FILTER', filter),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      oTable = $('#dataTable').dataTable({
        "bProcessing": true,
        "sAjaxSource": dataTableDataURL.replace('SET', set).replace('FILTER', filter).replace('MEDIA', $.template.mediaQuery.name),
        "sPaginationType": paginationType,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "bDestroy": true,
        "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "hide-on-mobile-portrait dataColCheck" },
                      { "sWidth": "25%", "bSortable": true, "sClass": "dataColModules" },
                      { "sWidth": "25%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColPages" },
                      { "sWidth": "10%", "bSortable": true, "sClass": "hide-on-tablet-landscape dataColSpecific" },
                      { "sWidth": "10%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColGroup" },
                      { "sWidth": "10%", "bSortable": true, "sClass": "hide-on-tablet dataColSort" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
      });
      $('#dataTable').responsiveTable();
      
      if ($.template.mediaQuery.isSmallerThan('desktop')) {
        $('#templateSelectorContainer').attr('style', 'position:absolute; top:91px; left:180px; z-index:2;');
      }   
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('#templateSelectorContainer').removeAttr('style');
      }          
           
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('#main-title > h1').attr('style', 'font-size:1.8em;');
        $('#main-title').attr('style', 'padding: 0 0 0 20px;');
        $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
        $('#dataTable_length').hide();
        $('#actionText').hide();
        $('.selectContainer').hide();
        $('.on-mobile').show();
      }      
    }
  );
}
</script>