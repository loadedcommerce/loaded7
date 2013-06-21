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
  $(".minicart_link").toggle(
    function() {
      $('.cart_drop').slideDown(300);  
    }, function(){
      $('.cart_drop').slideUp(300);     
    }
  );  

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
  
  // run this last - determine media type
  setTimeout('_setMediaType()', 1000);
  
  var loc = '<?php echo end(explode("/", $_SERVER['REQUEST_URI'])); ?>';
  if (loc == '' || loc == 'index.php') {
    $('#navHome').addClass('current');  
  } else {
    $('#primaryNav li a').each(function() {
      var urlStr = this.href.split('/').pop();
      if (loc.indexOf("index.php") != -1) {
        var locPath = loc.split('?').pop();
        var urlPath = urlStr.split('?').pop();
        if (urlStr == loc || (locPath.search(urlPath) != -1 && this.href.search('<?php echo HTTP_SERVER; ?>') != -1)) {
          $(this).addClass('current');
        }
      } else if (loc.indexOf("products.php") != -1) {
        if (urlStr == loc) {
          $(this).addClass('current');
        }
      } else if (loc.indexOf("info.php") != -1) {
        if (urlStr == loc) {
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

$(window).resize(function() {
  
  var type = _setMediaType();
  var width = '';
  
  // reset the payment iframe width
  if (type == 'mobile-portrait') {
    width = '254px';
  } else if (type == 'mobile-landscape') {
    width = '414px';
  } else if (type == 'small-tablet-portrait') {
    width = '490px';
  } else if (type == 'small-tablet-landscape') {
    width = '410px';
  } else if (type == 'tablet-portrait') {
    width = '390px';
  } else if (type == 'tablet-landscape') {
    width = '450px';
  } else {
    width = '478px';
  }
  
  $('#pmtFrame').css('width', width);
});

function _setMediaType() {
  var winW = $(window).width();
  
  if (winW < 321) {
    mtype = 'mobile-portrait'; //320 x 480
  } else if (winW > 320 && winW < 601) {
    if (winW > 463) {
      mtype = 'small-tablet-portrait'; //600 x 800
    } else {
      mtype = 'mobile-landscape'; //480 x 320
    }
  } else if (winW > 601 && winW < 769) {  
    mtype = 'tablet-portrait'; //768 x 1024   
  } else if (winW > 769 && winW < 1025) {
    if (winW < 784) {
      mtype = 'small-tablet-landscape'; //800 x 600
    } else {    
      mtype = 'tablet-landscape'; //1024 x 768    
    }
  } else if (winW > 1024) {
    mtype = 'desktop';    
  }
  
  var sizeStored = '<?php echo $_SESSION['mediaSize']; ?>';
  if (sizeStored != winW) {
    var jsonLink = '<?php echo lc_href_link('rpc.php', 'action=setMediaType&type=TYPE&size=SIZE', 'AUTO'); ?>'
    $.getJSON(jsonLink.replace('TYPE', mtype).replace('&amp;', '&').replace('SIZE', winW).replace('&amp;', '&'),
      function (data) {
        return true;
      }
    );  
  }

  return mtype;
}

$(window).resize(function() {
  _setMediaType();
});
</script>