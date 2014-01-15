<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template, $lC_Language;
?>
<script>
$(document).ready(function() {
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA'); ?>'; 
  var quickAdd = '<?php echo (isset($_GET['action']) && $_GET['action'] == 'quick_add') ? true : false; ?>';
    
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType, 
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                  { "sWidth": "60%", "bSortable": true, "sClass": "dataColProducts" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColPrice hide-on-mobile-portrait" },
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
  
  if (quickAdd) {
    newSpecial();
  }
});

function updateGrossEdit(event) {
  __updateGross('edit_specials_price', event);
}

function updateNetEdit(event) {
  __updateNet('edit_specials_price', event);
}

function updateGross(event) {
  __updateGross('specials_price', event);
}

function updateNet(event) {
  __updateNet('specials_price', event);
}

function getTaxClass(pid) {
  if (pid != null) {
    var id = pid;
  } else {
    var id = $("#products").val();
  }
  var link = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getTaxClass&pid=PID'); ?>'
  var jsonLink = link.replace('PID', id);
  $.getJSON(jsonLink,
    function (tdata) {
      if (tdata.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (tdata.rpcStatus != 1) {
        alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }
      $("#taxClassRate").html(tdata.taxClassRate);
    }
  );
}

function pad(s) {
  s = s||'.';
  return (s.length>4) ? s : pad(s+'0');
}

function doRound(x, places) {
  return (new String(Math.round(x * Math.pow(10, places)) / Math.pow(10, places))).replace(/(\.\d*)?$/, pad);
}

function __updateGross(field, evt) {
  if (evt.keyCode == 9) {
    return false;
  }

  if ((document.getElementById(field).value).indexOf('%') > -1) {
    document.getElementById(field + "_gross").value = '';
    document.getElementById(field + "_gross").disabled = true;
    return false;
  } else if (document.getElementById(field + "_gross").disabled == true) {
    document.getElementById(field + "_gross").disabled = false;
  }

  var taxRate = $("#taxClassRate").text();
  var grossValue = document.getElementById(field).value;
  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }
  document.getElementById(field + "_gross").value = doRound(grossValue, 4);
}

function __updateNet(field, evt) {
  if (evt.keyCode == 9) {
    return false;
  }

  if ((document.getElementById(field + "_gross").value).indexOf('%') > -1) {
    document.getElementById(field).value = document.getElementById(field + "_gross").value;
    document.getElementById(field + "_gross").value = '';
    document.getElementById(field).focus();
    document.getElementById(field + "_gross").disabled = true;
    return false;
  }
  var taxRate = $("#taxClassRate").text;
  var netValue = document.getElementById(field + "_gross").value;
  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }
  document.getElementById(field).value = doRound(netValue, 4);
}
</script>