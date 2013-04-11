<?php
/**
  $Id: general.js.php v1.0 2013-02-08 wa4u $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Default class manages default template functions
*/
global $lC_Language; 
?>
<script>
$(document).ready(function() {

  function setMaintenanceMode(s) {
    if (s == 'on') {
      $("body").mask('<span style="font-size:2em !important;"><?php echo $lC_Language->get('update_message_text1'); ?></span>');
      $('.loadmask-msg').css({'top':'200px'});
    } else {
      $("body").unmask();
    }
  }

  var maintMode = '<?php echo STORE_DOWN_FOR_MAINTENANCE; ?>';
  
  if (maintMode == 1) {
    setMaintenanceMode('on');
  } else {
    setMaintenanceMode('off');
  }
  
  // jBreadcrumb
  $("#breadCrumbContainer").jBreadCrumb();     
   
  //jCarousel
  $('.first-and-second-carousel').jcarousel();
  
  //mini cart slide toggle
  $(".minicart_link").toggle(function() {
     $('.cart_drop').slideDown(300);  
     }, function(){
     $('.cart_drop').slideUp(300);     
  });  

  //sub menu
  $("ul.departments > li.menu_cont > a").toggle(function(){
    $(this).addClass('active');
    $(this).siblings('.side_sub_menu').slideDown(300);
    }, function(){
    $(this).removeClass('active');
    $(this).siblings('.side_sub_menu').slideUp(300);
  });
  
  //style form select elements
  $("select").uniform();  
  
  //toggle box
  $(".toggle_box > li:first-child .toggle_title, .toggle_box > li:first-child .toggle_content").addClass('active');
  $(".toggle_box > li > a.toggle_title").toggle(function(){
                            
    $(this).addClass('active');
    $(this).siblings('.toggle_content').slideDown(300);
    }, function(){
    $(this).removeClass('active');
    $(this).siblings('.toggle_content').slideUp(300);  
  });  
  
  //twitter feed   //replace "rohithpaul" with your Twitter ID
  $('.twitter_feed').jTweetsAnywhere({
    username: 'rohithpaul',
    count: 1
  });
  
  $("#mobile-menu-button").click(function () {
    $("#mobile-menu").slideToggle("slow");
  });
  
  $("#mobile-grand-total").click(function () {
    $("#mobile-grand-total").toggle();
    $("#mobile-order-totals").slideToggle("slow");
  });
  
  $("#mobile-order-totals").click(function () {
    $("#mobile-order-totals").toggle();
    $("#mobile-grand-total").slideToggle("slow");
  });
  
  $("#browse-catalog").click(function () {
    $("#browse-catalog-div").slideToggle("slow");
  }); 
 
  // grid/list product view switch
  $('#listView').click(function(){
    $('#viewList').show();
    $('#viewGrid').hide();
  });
  $('#gridView').click(function(){
    $('#viewGrid').show();
    $('#viewList').hide();
  });
  
  // run this last - determine media type
  var mobile = $('#browse-catalog-div').is(':visible');
  if (mobile) {
    mtype = 'mobile';
  } else {
    mtype = 'desktop';
  }
  
  _setMediaType(mtype);  

});

function _setMediaType(mtype) {
  var jsonLink = '<?php echo lc_href_link('rpc.php', 'action=setMediaType&type=TYPE', 'AUTO'); ?>'
  $.getJSON(jsonLink.replace('TYPE', mtype),
    function (data) {
      return true;
    }
  );  
}
</script>