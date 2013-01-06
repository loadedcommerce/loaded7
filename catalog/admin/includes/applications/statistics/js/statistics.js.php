<?php
/*
  $Id: statistics.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language, $modulesArr, $accessArr, $cols;
if (!empty($_GET['module'])) { // module listing
?>
<script>
  $(document).ready(function() {
    // instantiate floating menu     
    $('#floating-menu-div-listing').fixFloat();

    var cols = '<?php echo $cols; ?>';
    if (cols == 2) {
      var aoCols = [{ "sWidth": "80%", "bSortable": true, "sClass": "dataCol1" },
                    { "sWidth": "20%", "bSortable": true, "sClass": "dataCol2" }];
    } else if (cols == 3) {
      var aoCols = [{ "sWidth": "60%", "bSortable": true, "sClass": "dataCol1" },
                    { "sWidth": "20%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "20%", "bSortable": true, "sClass": "dataCol3" }];
    } else if (cols == 4) {
      var aoCols = [{ "sWidth": "40%", "bSortable": true, "sClass": "dataCol1" },
                    { "sWidth": "20%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "20%", "bSortable": true, "sClass": "dataCol3" },
                    { "sWidth": "20%", "bSortable": true, "sClass": "dataCol4" }];
    } else if (cols == 5) {
      var aoCols = [{ "sWidth": "40%", "bSortable": true, "sClass": "dataCol1" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol3" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol4" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol5" }];
    }
    var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&module=' . $_GET['module'] . '&action=getListing&media=MEDIA'); ?>';   
    oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
      "sPaginationType": paginationType,    
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
      "aoColumns": aoCols
    });
    $('#dataTable').responsiveTable();
         
    if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
      $('#main-title > h1').attr('style', 'font-size:1.8em;');
      $('#main-title').attr('style', 'padding: 0 0 0 20px;');
      $('#dataTable_info').attr('style', 'bottom: 42px; color:#4c4c4c;');
      $('#dataTable_length').hide();
      $('#floating-button-container').hide();
      $('#actionText').hide();
      $('.on-mobile').show();
      $('.selectContainer').hide();
    }       
    var error = '<?php echo $_SESSION['error']; ?>';
    if (error) {
      var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
      $.modal.alert(errmsg);
    }
  });
</script>
<?php
} else { // default listing
?>
<script>
  $(document).ready(function() {
    var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getAll&media=MEDIA'); ?>';   
    oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
      "sPaginationType": paginationType,
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
      "aoColumns": [{ "sWidth": "80%", "bSortable": true, "sClass": "dataColModule" },
                    { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
    });
    $('#dataTable').responsiveTable();
         
    if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
      $('#main-title > h1').attr('style', 'font-size:1.8em;');
      $('#main-title').attr('style', 'padding: 0 0 0 20px;');
      $('#dataTable_info').attr('style', 'bottom: 42px; color:#4c4c4c;');
      $('#dataTable_length').hide();
      $('#floating-button-container').hide();
      $('#actionText').hide();
      $('.on-mobile').show();
      $('.selectContainer').hide();
    }    
    var error = '<?php echo $_SESSION['error']; ?>';
    if (error) {
      var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
      $.modal.alert(errmsg);
    }
  });
</script>
<?php } ?>