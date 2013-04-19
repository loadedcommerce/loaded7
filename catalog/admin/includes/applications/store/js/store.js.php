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
  
  showType('1', 'Payment');  
  
});

function showType(id, text) {
  //$('#contentContainer').html(text + ' Addons Listing Area');
 
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&aid=AID&action=getAll&media=MEDIA'); ?>';
  oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL.replace('AID', parseInt(id)).replace('MEDIA', $.template.mediaQuery.name),
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bSort": false,
      "bInfo": false,
      "bDestroy": true,
      "aoColumns": [{ "sWidth": "80px", "sClass": "dataColThumb" },
                    { "sWidth": "30%", "sClass": "dataColTitle hide-on-mobile-portrait" },
                    { "sWidth": "50%", "sClass": "dataColDesc hide-on-mobile hide-on-tablet" },
                    { "sWidth": "110px", "sClass": "dataColAction" }]
  });
  oTable.responsiveTable();  
  $('#dataTable thead').remove();
  $('#cfgTitleText').html(text + ' Add Ons'); 
  $(".unstyled-list a").removeClass("store-type-selected");
  $("#menuLink" + id).addClass('store-type-selected');

  setTimeout('updateTitles()', 800);
     
}  
  

function updateTitles() {
  var installed = 'Installed';
  var available = 'Available';
  $("#dataTable td").each(function() {
    var t = $(this).text();
    if (t == installed) $(this).closest('tr').html('<td class="grey-gradient glossy no-padding" colspan="4" align="center"><span class="big-text">Installed</span></td>');  
    if (t == available) $(this).closest('tr').html('<td class="grey-gradient glossy no-padding" colspan="4" align="center"><span class="big-text">Available</span></td>');  
  });
  
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
      $('.hide-on-tablet').attr('style', 'display:none !important');
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
      $('.hide-on-tablet').attr('style', 'display:none !important');
  } else if ($.template.mediaQuery.name === 'tablet-landscape') { 
      $('#storeHeaderRightContainer').css('width', '100%');    
      $('#storeSearchContainer').css('width', '46%').css('float', 'right').addClass('margin-top');    
      $('#storeFilterContainer').css('width', '48%').css('float', 'left').addClass('margin-top'); 
      $('.hide-on-tablet').attr('style', 'display:none !important');
  } else { // desktop
  }  
  
} 
</script>