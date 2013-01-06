<?php
/*
  $Id: products.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language;
if (!empty($_GET['action']) && ($_GET['action'] == 'save')) { // edit a product
  ?>
  <script>
    $(document).ready(function() {
      // instantiate floating menu
      $('#floating-menu-div-listing').fixFloat();
      // instantiate the datepicker
      $(".datepicker").glDatePicker({ zIndex: 100 });      
      // js code here

      var error = '<?php echo $_SESSION['error']; ?>';
      if (error) {
        var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
        $.modal.alert(errmsg);
      }
    });
  </script>
  <?php
} else { // default product listing
  ?>
  <script>
    $(document).ready(function() {

      updateProductFilter();

      var error = '<?php echo $_SESSION['error']; ?>';
      if (error) {
        var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
        $.modal.alert(errmsg);
      }
    });

    function doSelectFunction(e) {
      if (e.value == 'delete') {
        batchDelete();
      } else if (e.value == 'copy') {
        batchCopy();
      }
    }

    function updateProductFilter() {
      var cid = $("#cid").val();
      var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
      var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA&cid=CID'); ?>';
      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getCategoriesArray&cid=CID'); ?>';
      $.getJSON(jsonLink.replace('CID', cid),
        function (data) {
          if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href',url);
          }
          if (data.rpcStatus != 1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
            return false;
          }
          $("#cid").empty();
          $.each(data.categoriesArray, function(val, text) {
            var selected = (cid == val) ? 'selected="selected"' : '';
            $("#cid").append(
              $("<option " + selected + "></option>").val(val).html(text)
            );
          });
          oTable = $('#dataTable').dataTable({
            "bProcessing": true,
            "sAjaxSource": dataTableDataURL.replace('CID', cid).replace('MEDIA', $.template.mediaQuery.name),
            "sPaginationType": paginationType,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "bDestroy": true,
            "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                          { "sWidth": "55%", "bSortable": true, "sClass": "dataColProducts" },
                          { "sWidth": "15%", "bSortable": true, "sClass": "dataColPrice hide-on-mobile-portrait" },
                          { "sWidth": "10%", "bSortable": true, "sClass": "dataColQty hide-on-tablet" },
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
          } else {
            // instantiate floating menu
            $('#floating-menu-div-listing').fixFloat();
          }          
        }
      );
    }
  </script>
  <?php
}
?>