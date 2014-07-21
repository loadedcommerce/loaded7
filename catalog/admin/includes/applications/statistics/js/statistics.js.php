<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: statistics.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template, $lC_Language, $modulesArr, $accessArr, $cols;
if (!empty($_GET['module'])) { // module listing
?>
<script src="../ext/jquery/DataTables/media/js/jquery.dataTables.tableTools.min.js"></script>
<script>
  $(document).ready(function() {
    updateList();
    //$("span.hide-on-mobile").parent().hide();
  });
  function updateList() {
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
    else if (cols == 6) {
      var aoCols = [{ "sWidth": "25%", "bSortable": true, "sClass":                     "dataCol1" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol3" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol4" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol5" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol6" }];
    }
    else if (cols == 7) {
      var aoCols = [{ "sWidth": "30%", "bSortable": true, "sClass":                     "dataCol1" },
                    { "sWidth": "12%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "10%", "bSortable": true, "sClass": "dataCol3" },
                    { "sWidth": "10%", "bSortable": true, "sClass": "dataCol4" },
                    { "sWidth": "12%", "bSortable": true, "sClass": "dataCol5" },
                    { "sWidth": "13%", "bSortable": true, "sClass": "dataCol6" },
                    { "sWidth": "13%", "bSortable": true, "sClass": "dataCol7" }];
    }
    else if (cols == 8) {
      var aoCols = [{ "sWidth": "25%", "bSortable": true, "sClass":                     "dataCol1" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol3" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol4" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol5" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol6" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol7" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol8" }];
    }
    else if (cols == 9) {
      var aoCols = [{ "sWidth": "25%", "bSortable": true, "sClass":                     "dataCol1" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol3" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol4" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol5" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol6" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol7" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol8" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol9" }];
    }
    else if (cols == 10) {
      var aoCols = [{ "sWidth": "7%", "bSortable": true, "sClass":                     "dataCol1" },
                    { "sWidth": "7%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "10%", "bSortable": true, "sClass": "dataCol3" },
                    { "sWidth": "10%", "bSortable": true, "sClass": "dataCol4" },
                    { "sWidth": "12%", "bSortable": true, "sClass": "dataCol5" },
                    { "sWidth": "9%", "bSortable": true, "sClass": "dataCol6" },
                    { "sWidth": "8%", "bSortable": true, "sClass": "dataCol7" },
                    { "sWidth": "9%", "bSortable": true, "sClass": "dataCol8" },
                    { "sWidth": "8%", "bSortable": true, "sClass": "dataCol9" },
                    { "sWidth": "7%", "bSortable": true, "sClass": "dataCol10" }];
    }
    else if (cols == 11) {
      var aoCols = [{ "sWidth": "25%", "bSortable": true, "sClass":                     "dataCol1" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol3" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol4" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol5" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol6" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol7" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol8" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol9" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol10" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol11" }];
    }
    else if (cols == 12) {
      var aoCols = [{ "sWidth": "25%", "bSortable": true, "sClass":                     "dataCol1" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol2" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol3" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol4" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol5" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol6" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol7" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol8" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol9" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol10" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol11" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataCol12" }];
    }
    var statusFilter = $("#order_status").val();
    if (statusFilter == null) statusFilter = '0';  
    var manufacturerFilter = $("#manufacturer").val();
    if (manufacturerFilter == null) manufacturerFilter = '0';  
    var supplierFilter = $("#supplier").val();
    if (supplierFilter == null) supplierFilter = '0';  
    var timespanFilter = $("#time_span").val();
    if (timespanFilter == null) timespanFilter = '0';  
    var startDateFilter = $("#start_date").val();
    if (startDateFilter == null) startDateFilter = '0'; 
    var expiresDateFilter = $("#expires_date").val();
    if (expiresDateFilter == null) expiresDateFilter = '0';    
    var breakoutFilter = $("[name=breakout]:checked").val();
    if (breakoutFilter == null) breakoutFilter = 'category';
    var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&module=' . $_GET['module'] . '&action=getListing&media=MEDIA&statusID=STATUSID&manufacturerID=MANUFACTURERID&supplierID=SUPPLIERID&timeSpan=TIMESPAN&startDate=STARTDATE&expiresDate=EXPIRESDATE&breakoutType=BREAKOUTTYPE'); ?>';
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&module=' . $_GET['module'] . '&action=getListing&media=MEDIA&statusID=STATUSID&manufacturerID=MANUFACTURERID&supplierID=SUPPLIERID&timeSpan=TIMESPAN&startDate=STARTDATE&expiresDate=EXPIRESDATE&breakoutType=BREAKOUTTYPE'); ?>';
    $.getJSON(jsonLink.replace('STATUSID', statusFilter).replace('MANUFACTURERID', manufacturerFilter).replace('SUPPLIERID', supplierFilter).replace('TIMESPAN', timespanFilter).replace('STARTDATE', startDateFilter).replace('EXPIRESDATE',
      expiresDateFilter).replace('BREAKOUTTYPE', breakoutFilter),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href', url);
        }
        if (data.rpcStatus != 1) {
          alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          return false;
        }
    oTable = $('#dataTable').dataTable({
      "bProcessing": true,
          "sAjaxSource": dataTableDataURL.replace('STATUSID', statusFilter).replace('MANUFACTURERID', manufacturerFilter).replace('SUPPLIERID', supplierFilter).replace('TIMESPAN', timespanFilter).replace('STARTDATE', startDateFilter).replace('EXPIRESDATE', expiresDateFilter).replace('BREAKOUTTYPE', breakoutFilter).replace('MEDIA', $.template.mediaQuery.name),
      "sPaginationType": paginationType,    
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
          "aaSorting": [[1,'desc']],
          "bDestroy": true,
          "aoColumns": aoCols,
              "sDom": 'T<"top"f>rt<"bottom"ilp>',
              "oTableTools": { 
                "sSwfPath": "../ext/jquery/DataTables/media/swf/copy_csv_xls_pdf.swf",
                "aButtons": [ 
                            /*"copy",
                            "xls",*/
                            "csv",
                            "pdf",
                            "print",
                          ]
              },
    });
    $('#dataTable').responsiveTable();
         
    if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
      $('#main-title > h1').attr('style', 'font-size:1.8em;');
      $('#main-title').attr('style', 'padding: 0 0 0 20px;');
      $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
      $('#dataTable_length').hide();
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
       $('.datepicker').glDatePicker({ zIndex: 100 });  
  }
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
      $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
      $('#dataTable_length').hide();
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