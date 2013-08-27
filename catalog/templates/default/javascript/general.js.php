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
  
  // jBreadcrumb
  $("#breadCrumbContainer").jBreadCrumb();     
   
  //sub menu
  $("ul.departments > li.menu_cont > a").toggle(
    function(){
      $(this).addClass('active');
      $(this).siblings('.side_sub_menu').slideDown(300);
      window.location.href = $(this).attr('href');
    }, function(){
      $(this).removeClass('active');
      $(this).siblings('.side_sub_menu').slideUp(300);
    }
  );
  
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
  
  //twitter feed
  $('.twitter_feed').jTweetsAnywhere({
    username: 'loadedcommerce',
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
  
  var loc = '<?php echo end(explode("/", $_SERVER['REQUEST_URI'])); ?>';
  if (loc == '' || loc == 'index.php') {
    $('#navHome').addClass('current');  
  } else {
    $('#primaryNav li a').each(function() {
      var urlStr = this.href.split('/').pop();
      var urlPath = urlStr.split('?').pop();
      var locPath = loc.split('?').pop();
      //var cPath = locPath.split('&')[0];
      if (loc.indexOf("index.php") != -1) {
        if ((locPath.search(urlPath) != -1 && this.href.search('<?php echo HTTP_SERVER; ?>') != -1)) {
          $(this).addClass('current');
        }
      } else if (loc.indexOf("products.php") != -1) {
        if (urlStr == loc) {
          $(this).addClass('current');
        }
      } else if (loc.indexOf("info.php") != -1) {
        if (urlStr == loc || (loc.search(urlPath) != -1 && this.href.search('<?php echo HTTP_SERVER; ?>') != -1)) {
          $(this).addClass('current');
        }
      } else {
        var str = loc.split("?");
        if (urlStr.match(str[0])) {
          $(this).addClass('current'); 
        }
      }
    });
  }  

});
</script>