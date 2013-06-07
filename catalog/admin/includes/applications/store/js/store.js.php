<?php
/*
  $Id: store.js.php v1.0 2013-01-01 datazen $

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
  showAddonType('0', 'Payment');  
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