<?php
/*
  $Id: cache.js.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language;
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
      "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                    { "sWidth": "45%", "bSortable": true, "sClass": "dataColBlock" },
                    { "sWidth": "10%", "bSortable": true, "sClass": "dataColTotal hide-on-mobile-portrait" },
                    { "sWidth": "25%", "bSortable": true, "sClass": "dataColLast hide-on-mobile-portrait" },
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
  });
  
  function deleteEntry(id) {
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteEntry&cid=CID'); ?>';   
    $.getJSON(jsonLink.replace('CID', id),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href',url);
        }   
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          return false;
        } 
        oTable.fnReloadAjax();                 
      }              
    );
  }

  function batchDelete(values) {
    $(".select option:first").attr('selected','selected');
    var values = $('#batch').serialize();
    $("#check-all").attr('checked', false);
    var pattern = /batch/gi;
    if (values.match(pattern) == null) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_nothing_to_delete');?>');
      $(".select option:first").attr('selected','selected');
      return false;
    }  
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=batchDeleteEntries&bid=' . $_GET[$lC_Template->getModule()] . '&BATCH'); ?>';   
    $.getJSON(jsonLink.replace('BATCH', values),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href',url);
        }   
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          $(".select option:first").attr('selected','selected');
          return false;
        } 
        oTable.fnReloadAjax(); 
                        
      }              
    );
  }  
</script>