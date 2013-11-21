<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: store.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template, $lC_Language;
?>
<script>
$(document).ready(function() {
  
  var sel = '<?php echo $_GET['type']; ?>';
  if (sel != '') {
    if (sel == 'featured') showAddonType('1', 'Featured');   
    if (sel == 'payment') showAddonType('2', 'Payment');   
    if (sel == 'shipping') showAddonType('3', 'Shipping');   
  } else { 
    showAddonType('1', 'Featured'); 
  }
  $(this).scrollTop(0); 
});

$('input[name=sortby]').click(function() {
  var type = $('.store-type-selected').closest('li').attr('onclick');
  var atype = type.substring(type.indexOf("('")+1, type.indexOf("')")).replace(/'/gi,'').replace(/ /gi,'').split(",");
  showAddonType(atype[0], atype[1]);
});

function showAddonType(id, text) {
  
  var filter = $('input[name=sortby]:radio:checked').val();
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&aid=AID&action=getAll&type=TYPE&media=MEDIA&filter=FILTER'); ?>';
  oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": dataTableDataURL.replace('AID', parseInt(id)).replace('TYPE', text).replace('MEDIA', $.template.mediaQuery.name).replace('FILTER', filter),
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bSort": false,
      "bInfo": false,
      "bDestroy": true,
      "aoColumns": [{ "sWidth": "80px", "sClass": "dataColThumb hide-on-mobile-portrait" },
                    { "sWidth": "30%", "sClass": "dataColTitle" },
                    { "sWidth": "50%", "sClass": "dataColDesc hide-on-mobile hide-on-tablet" },
                    { "sWidth": "110px", "sClass": "dataColAction" }]
  });  
  oTable.responsiveTable();  
  $('#dataTable thead').remove();
  $('#cfgTitleText').html(text + ' Add Ons'); 
  $('#dataTable_wrapper').removeClass('dataTables_wrapper');
  $(".unstyled-list a").removeClass("store-type-selected");   

  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
  } else {
    $("#menuLink" + id).addClass('store-type-selected');
  }

  setTimeout('updateWindowSize()', 0);
}  
  
function updateTitles() {
  var installed = 'Installed';
  var available = 'Available';
  $("#dataTable td").each(function() {
    var t = $(this).text();
    if (t == installed) $(this).closest('tr').html('<td class="grey-gradient glossy no-padding" colspan="4" align="center"><span class="big-text">Installed</span></td>');  
    if (t == available) $(this).closest('tr').html('<td class="grey-gradient glossy no-padding" colspan="4" align="center"><span class="big-text">Available</span></td>');  
  });
} 

function updateWindowSize() {
  var winW = $(window).width();
  
  // tweak template depending on view
  if ($.template.mediaQuery.name === 'mobile-portrait') { 
    $('#storeHeaderRightContainer').css('width', '100%');    
    $('#storeSearchContainer').css('width', '100%').removeClass('no-margin-bottom').addClass('margin-top margin-bottom');    
    $('#storeFilterContainer').css('width', '100%');    
    $('#storeSearchContainerInput').removeClass('no-padding');
    $('#uninstallButton').empty();
  } else if ($.template.mediaQuery.name === 'mobile-landscape') { 
    if (winW > 464) { // small tablet portrait 600x800
      $('#storeHeaderRightContainer').css('width', '100%');    
      $('#storeSearchContainer').css('width', '46%').css('float', 'right').addClass('margin-top');    
      $('#storeFilterContainer').css('width', '48%').css('float', 'left').addClass('margin-top');  
    } else { // mobile landscape
      $('#storeHeaderRightContainer').css('width', '100%');    
      $('#storeSearchContainer').css('width', '100%').removeClass('no-margin-bottom').addClass('margin-top margin-bottom');    
      $('#storeFilterContainer').css('width', '100%').css('margin-left', '80px');    
      $('#storeSearchContainerInput').removeClass('no-padding');    
    }
  } else if ($.template.mediaQuery.name === 'tablet-portrait') {  
      $('#storeHeaderRightContainer').css('width', '100%');    
      $('#storeSearchContainer').css('width', '46%').css('float', 'right').addClass('margin-top');    
      $('#storeFilterContainer').css('width', '48%').css('float', 'left').addClass('margin-top'); 
  } else if ($.template.mediaQuery.name === 'tablet-landscape') { 
      $('#storeHeaderRightContainer').css('width', '100%');    
      $('#storeSearchContainer').css('width', '46%').css('float', 'right').addClass('margin-top');    
      $('#storeFilterContainer').css('width', '48%').css('float', 'left').addClass('margin-top'); 
  } else { // desktop
  }   
}
</script>