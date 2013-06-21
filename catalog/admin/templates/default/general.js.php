<?php
/*
  $Id: general.js.php v1.0 2012-08-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Vqmod, $lC_Template, $lC_Language;
require_once($lC_Vqmod->modCheck('includes/applications/updates/classes/updates.php'));    
?>
<script>
$(document).ready(function() { 
  // set shortcuts current marker
  var loc = '<?php echo end(explode("/", $_SERVER['PHP_SELF'])); ?>';
  var noParams = '<?php echo (empty($_GET) ? true : false); ?>';
  var module = '<?php echo $lC_Template->getModule(); ?>';
  var countryID = '<?php echo $_GET['countries']; ?>'         
  if (loc == 'index.php' && noParams) {
    // do nothing
  } else if (module == 'store') {
    $("#shortcuts li").parent().find('li').removeClass("current");
    $('#sc-' + module).addClass("current");
    toggleChildMenu('settings');
  } else if (module == 'customers') {
    $("#shortcuts li").parent().find('li').removeClass("current");
    $("#sc-customers").addClass('current');
  } else if (module == 'orders') {
    $("#shortcuts li").parent().find('li').removeClass("current");
    $("#sc-orders").addClass('current');   
  } else if (module == 'categories' || module == 'content') {
    $("#shortcuts li").parent().find('li').removeClass("current");
    $("#sc-content").addClass('current'); 
  } else if (module == 'specials' || module == 'manufacturers' || module == 'reviews' || (module.indexOf('product') != -1) ) {
    $("#shortcuts li").parent().find('li').removeClass("current");
    $("#sc-products").addClass('current');
  } else if (module == 'banner_manager' || module == 'newsletters') {
    $("#shortcuts li").parent().find('li').removeClass("current");
    $("#sc-marketing").addClass('current');      
  } else if (module == 'statistics' || module == 'whos_online') {
    $("#shortcuts li").parent().find('li').removeClass("current");
    $("#sc-reports").addClass('current');
  } else {
    // remove the shortcuts active tab since most pages are not in the shortcuts menu.
    $("#shortcuts li").parent().find('li').removeClass("current");
  }
         
  // set the current menu marker
  var cfg = false;
  $('#menu-content .big-menu a').each(function() {
    if (this.href == document.location.href) {
      $(this).addClass('current navigable-current');
      if ($(this).is('.cfg-open')) cfg = true;
    } else if (module.indexOf("administrators") != -1 && document.location.href.indexOf("set=access") != -1) {
      $("#big-menu_groups").addClass('current navigable-current').change();
      cfg = true;
    } else if (module.indexOf("countries") != -1 && document.location.href.indexOf("countries") != -1) {
      $("#big-menu_countries").addClass('current navigable-current').change();
      cfg = true;
    } else if (module.indexOf("languages") != -1 && document.location.href.indexOf("languages") != -1) {
      $("#big-menu_languages").addClass('current navigable-current').change();
      cfg = true;
    } else if (module.indexOf("templates_modules") != -1 && document.location.href.indexOf("set=boxes") != -1) {
      $("#big-menu_templates_modules").addClass('current navigable-current').change();
      cfg = true;
    } else if (module.indexOf("templates_modules") != -1 && document.location.href.indexOf("set=content") != -1) {
      $("#big-menu_templates_modules").addClass('current navigable-current').change();
      cfg = true;
    } else if (module.indexOf("templates_modules_layout") != -1 && document.location.href.indexOf("set=boxes") != -1) {
      $("#big-menu_templates_modules_layout").addClass('current navigable-current').change();
      cfg = true;
    } else if (module.indexOf("templates_modules_layout") != -1 && document.location.href.indexOf("set=content") != -1) {
      $("#big-menu_templates_modules_layout").addClass('current navigable-current').change();
      cfg = true;
    } else if (module.indexOf("product_variants") != -1 && document.location.href.indexOf("product_variants") != -1) {
      $("#big-menu_product_variants").addClass('current navigable-current').change();
    } else if (module.indexOf("statistics") != -1 && document.location.href.indexOf("statistics") != -1) {
      $("#big-menu_statistics").addClass('current navigable-current').change();        
    }
  });
  if (cfg) toggleChildMenu('settings'); 
  
  // Call template init (optional, but faster if called manually)
  $.template.init();  

  // tweak template depending on view
  if ($.template.mediaQuery.name === 'mobile-portrait') { 
    $('#logoImg').attr('style', 'margin-top:2px !important;');
    $('#mainMessageContainer').css('margin', '6px 4px 0px 4px');
  } else if ($.template.mediaQuery.name === 'mobile-landscape') { 
    $('#logoImg').attr('style', 'margin-top:2px !important;');
    $('#mainMessageContainer').css('margin', '6px 4px 0px 4px');
  } else if ($.template.mediaQuery.name === 'tablet-portrait') {  
    $('#logoImg').attr('style', 'margin-top:2px !important;');
    $('#mainMessageContainer').css('margin', '54px 4px 0px 74px');    
  } else if ($.template.mediaQuery.name === 'tablet-landscape') {  
    $('#logoImg').attr('style', 'margin-top:2px !important;');
    $('#mainMessageContainer').css('margin', '50px 273px 0 84px');    
  } else { // desktop
    $('#logoImg').attr('style', 'margin-top:2px !important;');
    $('#mainMessageContainer').css('margin', '50px 273px 0 84px');    
  }
  
  // check for updates and show notification if necessary 
  if (module == 'index') {   
    var title = '<?php echo lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, 'updates'), $lC_Language->get('update_message_title'), 'style="color:white;"'); ?>';
    var uData = <?php echo json_encode(lC_Updates_Admin::hasUpdatesAvailable()); ?>;
    if (uData.hasUpdates) {
      notify(title, '<?php echo $lC_Language->get('update_message_text1'); ?> ' + uData.toVersion + ' <?php echo $lC_Language->get('update_message_text2'); ?>', {
        icon: 'templates/default/img/smiley.png',
        showCloseOnHover: false
      });    
    }
  }
  
  var dfm = '<?php echo STORE_DOWN_FOR_MAINTENANCE; ?>';
  if (dfm == 1) {
    if (module != 'login') { 
      $('#main').addClass('no-margin-top');
      $('#mainMessageContainer').show();
      $('#mainMessageContainer p').html('<?php echo $lC_Language->get('site_maintenance_message_text'); ?>');
    }
  } else {
    $('#mainMessageContainer').hide();    
    $('#main').removeClass('no-margin-top');
  }
  
  // begin shortcut key additions
  $(window).bind("load", function() {
    // set the disable var to false to begin
    var disableKeyCombo = false; 
    // if any inputs on the page are clicked into then set the disable var to true
    $(":input").focus(function(){
      disableKeyCombo = true;
    }); 
    // when the input fields are blurred set the disable var back to false
    $(":input").blur(function(){
      disableKeyCombo = false;
    });
    // when a key is pressed 
    $("*").keypress(function(e){
      // if the disable var is false we continue
      if (!disableKeyCombo == true) {
        // first check if the escape key has been presed
        $(document).keydown(function(e){
          var code = e.keyCode ? e.keyCode : e.which;
          if (code == 27) {
            disableKeyCombo = false; 
            $('#li-search').removeClass("current");
            $('#li-messages').removeClass("current");
            $('#li-add').removeClass("current");
            $('#li-settings').removeClass("current");
            $('#addContainer').hide();
            $('#searchContainer').hide();
            $('#messagesContainer').hide();
            $('#settingsContainer').hide();
            $('#mainMenuContainer').show();
            $('#recentContainer').show();
            $('body').focus();
          }
        });
        // check to see if a modal is open currently by it's class attribute
        var modalClass = $('#modals').attr('class');
        // if the modal's class is not with-blocker we can continue
        if (modalClass != 'with-blocker') {
          var code = e.keyCode ? e.keyCode : e.which;
          if (code == 32) { // space for mega search menu
            $('#li-add').removeClass("current");
            $('#li-messages').removeClass("current");
            $('#li-settings').removeClass("current");
            $('#messagesContainer').hide();
            $('#addContainer').hide();
            $('#mainMenuContainer').hide();
            $('#recentContainer').hide();
            $('#settingsContainer').hide();
            $('#li-search').addClass("current");
            $('#searchContainer').show();
            $('#searchContainerInput').find('input').focus(); 
            return false;
          };
          if (code == 97) { // a for quick add menu
            $('#li-search').removeClass("current");
            $('#li-messages').removeClass("current");
            $('#li-settings').removeClass("current");
            $('#messagesContainer').hide();
            $('#searchContainer').hide();
            $('#mainMenuContainer').hide();
            $('#recentContainer').hide();
            $('#settingsContainer').hide();
            $('#li-add').addClass("current");
            $('#addContainer').show();
          };
          if (code == 109) { // m for messages menu
            $('#li-search').removeClass("current");
            $('#li-settings').removeClass("current");
            $('#li-add').removeClass("current");
            $('#addContainer').hide();
            $('#searchContainer').hide();
            $('#mainMenuContainer').hide();
            $('#recentContainer').hide();
            $('#settingsContainer').hide();
            $('#li-messages').addClass("current");
            $('#messagesContainer').show();
          };
          if (code == 115) { // s for settings menu
            $('#li-search').removeClass("current");
            $('#li-messages').removeClass("current");
            $('#li-add').removeClass("current");
            $('#addContainer').hide();
            $('#searchContainer').hide();
            $('#messagesContainer').hide();
            $('#recentContainer').hide();
            $('#settingsContainer').show();
            $('#li-settings').addClass("current");
            $('#mainMenuContainer').hide();
          };
          var liAddClass = $('#li-add').attr("class");
          if (liAddClass == 'current') {
            if (code == 111) { // o for new (O)rder
              //window.location.href = '';
            };
            if (code == 99) { // c for new (C)ustomer
              window.location.href = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'customers&action=quick_add'); ?>';
            };
            if (code == 103) { // g for new cate(G)ory
              window.location.href = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'categories&action=quick_add'); ?>';
            };
            if (code == 112) { // p for new (P)roduct
              window.location.href = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'products&action=save'); ?>';
            };
            if (code == 108) { // l for new specia(L)
              window.location.href = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'specials&action=quick_add'); ?>';
            };
            if (code == 116) { // t for new manufac(T)urer
              window.location.href = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'manufacturers&action=quick_add'); ?>';
            };
            if (code == 98) { // b for new (B)anner
              window.location.href = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'banner_manager&action=quick_add'); ?>';
            };
            if (code == 110) { // n for new (N)ewsletter
              window.location.href = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'newsletters&action=quick_add'); ?>';
            };
          }
        }
      }
    });
  });
  // end shortcut key additions
  
  // get the menu width
  var menuWidth = $("#menu").width();
  // apply twice the menu width to the inner div
  $("#profileInner").css({'width':menuWidth * 2});
  // on screen resize get the new menu width and apply it for click functions
  $(window).resize(function() {
    // get the visible menu width
    var menuWidthResized = $("#menu").width();
    // set profile inner width twice that of the visible menu
    $("#profileInner").css({'width':menuWidthResized * 2});
    // reset the left margin to 0px on window resizing
    $('#profileInner').css({"margin-left":"0px"});
    // if window width drops below 1280px change category edit tabs from side to top
    if ($(window).width() < 1380) {
      $("#category_tabs").removeClass("side-tabs");
      $("#category_tabs").addClass("standard-tabs");
      $("#product_tabs").removeClass("side-tabs").addClass("standard-tabs");
    } if ($(window).width() >= 1380) {
      $("#category_tabs").removeClass("standard-tabs");
      $("#category_tabs").addClass("side-tabs");
      $("#product_tabs").removeClass("standard-tabs").addClass("side-tabs");
    }
  });
  
  // profile left is clicked
  $("#profileLeft").click(function(){
    // if any of the 4 mene areas are open close them
    $('#li-search').removeClass("current");
    $('#searchContainer').hide();
    $('#li-add').removeClass("current");
    $('#addContainer').hide();
    $('#li-messages').removeClass("current");
    $('#messagesContainer').hide();
    $('#li-settings').removeClass("current");
    $('#settingsContainer').hide();
    $('#mainMenuContainer').show();
    $('#recentContainer').show();
    // get the current menu width in case screen size has changed
    var menuWidth = $("#menu").width();
    // slide to the left
    if (!$('#profileInner').is(':animated')) {
           $('#profileInner').animate({
              "marginLeft" : "-=" + menuWidth
     });           
   }
    return false;
  });
  // profile right is clicked
  $("#profileBack").click(function(){
    // get the current menu width in case screen size has changed
    var menuWidth = $("#menu").width();
    // slide to the right
    $('#profileInner').animate({
      "marginLeft" : "+=" + menuWidth
    });
    return false;
  });
  
  // added for h1 titles to auto fit window width
  $.fn.fitText = function( kompressor, options ) {
    // Setup options
    var compressor = kompressor || 1,
        settings = $.extend({
          'minFontSize' : 14,
          'maxFontSize' : 40
        }, options);
    return this.each(function(){
      // Store the object
      var $this = $(this);
      // modified for Loaded7 
      // Resizer() resizes items based on the object width divided by the compressor * 17
      var resizer = function () {
        $this.css('font-size', Math.max(Math.min($this.width() / (compressor*17), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)));
      };
      // do the magic.
      resizer();
      // Call on resize. Opera debounces their resize by default. 
      $(window).on('resize', resizer);
    });
  };
  $("h1").fitText();
  
  // defeat Google Chrome form autofill and its yellow background
  if(navigator.userAgent.toLowerCase().indexOf("chrome") >= 0 || navigator.userAgent.toLowerCase().indexOf("safari") >= 0){
    window.setInterval(function(){
      $('input:-webkit-autofill').each(function(){
          var clone = $(this).clone(true, true);
          $(this).after(clone).remove();
      });
    }, 20);
  }
  
  $("#profileLoader").hide();
  $("#profileInner").fadeTo(1000, 1);
  
  $("#qrcode-tooltip").click(function() {
    $("#qr-message").show("500");
  });
  
  if ($(window).width() < 1380) {
    $("#category_tabs").removeClass("side-tabs");
    $("#category_tabs").addClass("standard-tabs");
  } if ($(window).width() >= 1380) {
    $("#category_tabs").removeClass("standard-tabs");
    $("#category_tabs").addClass("side-tabs");
  }        
     
});

// check width of window for product edit tabs placement
if ($(window).width() < 1380) {
  $("#product_tabs").removeClass("side-tabs");
  $("#product_tabs").addClass("standard-tabs");
}
  
/* show the upsell spot modal */
function showUpsellSpot(e) {  
  
  var title = $(e).closest('.field-block').find('.label').text();
  var desc = $(e).closest('.field-block').find('.label').attr('upsell');
  if (title == '') { 
    title = $(e).closest('.button').text().replace('Pro', '').replace('B2B', '');
    desc = $(e).closest('.button').attr('upsell');
  }
  
  var text = '<style>.modal { padding:0; }</style>'+
             '<div id="spotMainContainer" class="with-mid-padding no-margin anthracite" style="width:280px; min-height:200px; background-color:#fff; border:3px solid white; border-radius:4px 4px 4px 4px;">'+
             '  <div id="spotMainOutline" class="relative with-mid-padding" min-height:180px; style="border:2px solid red; border-radius:4px 4px 4px 4px;">'+
             '    <a onclick="closeUpsellSpot($(this).getModalWindow());" href="javascript://"><span onclick="closeUpsellSpot($(this).getModalWindow());" class="close">X</span></a>'+
             '    <div id="spotMainHeader" class="align-left"><small class="tag red-bg"><?php echo $lC_Language->get('text_pro'); ?></small><span class="thin mid-margin-left">Feature Information</span>'+ 
             '    </div>'+
             '    <div id="spotMainTitle" class="align-left"><h3 class="align-left margin-top mid-margin-bottom">' + title + '</h3>'+
             '    </div>'+
             '    <div id="spotMainDesc" class="align-left">'+ desc +
             '    </div>'+
             '    <div id="spotMainButton" class="with-padding"><a href="javascript://" class="button huge red-gradient glossy ">Upgrade Now</a>'+
             '    </div>'+
             '    <div id="spotMainFooter" class="small-margin-bottom"><a href="#" style="text-decoration:underline;">See Full Pro & B2B Feature List</a>'+
             '    </div>'+
             '    </div>';
             '</div>';
  $.modal({
     contentBg: false,
     contentAlign: 'center',
     content: text,
     resizable: false,
     actions: {},
     buttons: {}
   });
}
// added to prevent enter key on megasearch
$('.noEnterSubmit').keypress(function(e){
  if (e.which == 13) return false;
  if (e.which == 13) e.preventDefault();
});

// turn off maintenance mode
$("#mainMessageContainer").click(function(){
  $('#mainMessageContainer p').removeClass('icon-warning icon-black').html('<span class="loader on-dark small-margin-right" style="margin-left:-4px"></span><?php echo $lC_Language->get('site_maintenance_message_text'); ?>');
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', 'updates' . '&action=setMaintMode&s=MODE'); ?>'
  $.getJSON(jsonLink.replace('MODE', 'off').replace('&amp;', '&'),
    function (data) {
      $('#main').removeClass('no-margin-top');
      $('#mainMessageContainer').slideUp();
      return true;
    }
  );  
});

/* close the upsell spot modal */
function closeUpsellSpot(e) {
  $(e).closeModal();  
}

/* toggle checkboxes on table listings */
function toggleCheck() {
  checked = $("#check-all").is(':checked');
  $(".batch").each( function() {
    if (checked) {
      $(this).attr("checked","checked");
    } else {
      $(this).removeAttr("checked");
    }
  });
} 

/* toggle the main big menus */
function toggleChildMenu(section) {
  var isMainOpen = $('#mainMenuContainer').is(":visible");
  var settingsDisabled = '<?php echo (($_SESSION['admin']['access']['configuration'] > 0) ? 0 : 1); ?>';
  if (settingsDisabled == 1) return false;
  if (!(isMainOpen)) {
    $('#li-settings').removeClass("current");
  } else {
    $('#li-' + section).addClass('current');
  }
  // toggle the menus
  var settingsCurrent = $('#li-settings').is('.current');
  if (settingsCurrent) { 
    $('#mainMenuContainer').slideUp();
    $('#settingsContainer').slideDown();
  } else { 
    $('#mainMenuContainer').slideDown();
    $('#settingsContainer').slideUp();            
  }
} 

function toggleSubMenu(section) {
  var isVisible = $('#' + section + 'Container').is(':visible');
  if (isVisible) { 
    $('#' + section + 'Container').slideUp();
    $('#li-' + section).removeClass('current');
  } else {
    $('#' + section + 'Container').slideDown();
    $('#li-' + section).addClass('current');
  }  
}

/* checks to see if big-menu is open */
function isMenuOpen() {
  if ($('body').is('.menu-hidden')) {
    return 0;
  } else {
    return 1;
  }
}

function selectAllFromPullDownMenu(field) {
  var field = document.getElementById(field);

  for (i=0; i < field.length; i++) {
    field.options[i].selected = true;
  }
}

function resetPullDownMenuSelection(field) {
  var field = document.getElementById(field);

  for (i=0; i < field.length; i++) {
    field.options[i].selected = false;
  }
}

function mask() {
  $("body").mask('<span class="loader huge refreshing"></span>');
  $("body").removeClass("mask");
  // tweak template depending on view
  if ($.template.mediaQuery.name === 'mobile-portrait') { 
    $('.loadmask-msg').css({'top':'180px'});
  } else if ($.template.mediaQuery.name === 'mobile-landscape') { 
    $('.loadmask-msg').css({'top':'140px'});
  } else if ($.template.mediaQuery.name === 'tablet-portrait') {  
    $('.loadmask-msg').css({'top':'380px'});
  } else if ($.template.mediaQuery.name === 'tablet-landscape') {  
    $('.loadmask-msg').css({'top':'260px'});
  } else { // desktop
    $('.loadmask-msg').css({'top':'300px'});
  }  
}
 
function unmask() {
  $("body").unmask();
}

function modalMessage(text) {
  mm = $.modal({
          contentBg: false,
          contentAlign: 'center',
          content: text,
          resizable: false,
          actions: {},
          buttons: {}
        });
  $(mm);
  setTimeout ("$(mm).closeModal()", 2000);
}
 
;function print_r (array, return_val) {
    // http://kevin.vanzonneveld.net
    // +   original by: Michael White (http://getsprink.com)
    // +   improved by: Ben Bryan
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +      improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: echo
    // *     example 1: print_r(1, true);
    // *     returns 1: 1
    var output = '',
        pad_char = ' ',
        pad_val = 4,
        d = this.window.document,
        getFuncName = function (fn) {
            var name = (/\W*function\s+([\w\$]+)\s*\(/).exec(fn);
            if (!name) {
                return '(Anonymous)';
            }
            return name[1];
        },
        repeat_char = function (len, pad_char) {
            var str = '';
            for (var i = 0; i < len; i++) {
                str += pad_char;
            }
            return str;
        },
        formatArray = function (obj, cur_depth, pad_val, pad_char) {
            if (cur_depth > 0) {
                cur_depth++;
            }

            var base_pad = repeat_char(pad_val * cur_depth, pad_char);
            var thick_pad = repeat_char(pad_val * (cur_depth + 1), pad_char);
            var str = '';

            if (typeof obj === 'object' && obj !== null && obj.constructor && getFuncName(obj.constructor) !== 'PHPJS_Resource') {
                str += 'Array\n' + base_pad + '(\n';
                for (var key in obj) {
                    if (Object.prototype.toString.call(obj[key]) === '[object Array]') {
                        str += thick_pad + '[' + key + '] => ' + formatArray(obj[key], cur_depth + 1, pad_val, pad_char);
                    }
                    else {
                        str += thick_pad + '[' + key + '] => ' + obj[key] + '\n';
                    }
                }
                str += base_pad + ')\n';
            }
            else if (obj === null || obj === undefined) {
                str = '';
            }
            else { // for our "resource" class
                str = obj.toString();
            }

            return str;
        };

    output = formatArray(array, 0, pad_val, pad_char);

    if (return_val !== true) {
        if (d.body) {
            this.echo(output);
        }
        else {
            try {
                d = XULDocument; // We're in XUL, so appending as plain text won't work; trigger an error out of XUL
                this.echo('<pre xmlns="http://www.w3.org/1999/xhtml" style="white-space:pre;">' + output + '</pre>');
            } catch (e) {
                this.echo(output); // Outputting as plain text may work in some plain XML
            }
        }
        return true;
    }
    return output;
}

function search(q) {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=search&q=SEARCH'); ?>'
  $.getJSON(jsonLink.replace('SEARCH', q),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      } 
      $('#searchResults').html(data.html);
    }
  );
}

$("#li-search").click(function() {
  var addOpen = $('#addContainer').is(':visible');
  var msgOpen = $('#messagesContainer').is(':visible');
  var mainOpen = $('#mainMenuContainer').is(':visible');
  var setOpen = $('#settingsContainer').is(':visible');
  var srcClass = $('#li-search').attr('class');
  $('#searchContainerInput').find('input').focus(); 
  if (addOpen) {
    $('#li-add').removeClass("current");
    $('#addContainer').hide();
  }
  if(msgOpen) {
    $('#li-messages').removeClass("current");
    $('#messagesContainer').hide();
  }
  if(mainOpen) {
    $('#li-settings').removeClass("current");
    $('#mainMenuContainer').hide();
    $('#recentContainer').hide();
  }
  if(setOpen) {
    $('#li-settings').removeClass("current");
    $('#settingsContainer').hide();
    $('#recentContainer').hide();
  }
  if (srcClass == 'tracked') {
    $('#mainMenuContainer').show();
    $('#recentContainer').show();
  }
  // get the current menu width in case screen size has changed
  var menuWidth = $("#menu").width();
  // slide to the right
  $('#profileInner').animate({
    "marginLeft" : 0
  });
});

$("#li-add").click(function() {
  var srcOpen = $('#searchContainer').is(':visible');
  var msgOpen = $('#messagesContainer').is(':visible');
  var mainOpen = $('#mainMenuContainer').is(':visible');
  var setOpen = $('#settingsContainer').is(':visible');
  var addClass = $('#li-add').attr('class');
  if (srcOpen) {
    $('#li-search').removeClass("current");
    $('#searchContainer').hide();
  }
  if (msgOpen) {
    $('#li-messages').removeClass("current");
    $('#messagesContainer').hide();
  }
  if(mainOpen) {
    $('#li-settings').removeClass("current");
    $('#mainMenuContainer').hide();
    $('#recentContainer').hide();
  }
  if(setOpen) {
    $('#li-settings').removeClass("current");
    $('#settingsContainer').hide();
    $('#recentContainer').hide();
  }
  if (addClass == 'tracked') {
    $('#mainMenuContainer').show();
    $('#recentContainer').show();
  }
  // get the current menu width in case screen size has changed
  var menuWidth = $("#menu").width();
  // slide to the right
  $('#profileInner').animate({
    "marginLeft" : 0
  });
});

$("#li-messages").click(function() {
  var srcOpen = $('#searchContainer').is(':visible');
  var addOpen = $('#addContainer').is(':visible');
  var mainOpen = $('#mainMenuContainer').is(':visible');
  var setOpen = $('#settingsContainer').is(':visible');
  var msgClass = $('#li-messages').attr('class');
  if (srcOpen) {
    $('#li-search').removeClass("current");
    $('#searchContainer').hide();
  }
  if (addOpen) {
    $('#li-add').removeClass("current");
    $('#addContainer').hide();
  }
  if(mainOpen) {
    $('#li-settings').removeClass("current");
    $('#mainMenuContainer').hide();
    $('#recentContainer').hide();
  }
  if(setOpen) {
    $('#li-settings').removeClass("current");
    $('#settingsContainer').hide();
    $('#recentContainer').hide();
  }
  if (msgClass == 'tracked') {
    $('#mainMenuContainer').show();
    $('#recentContainer').show();
  }
  // get the current menu width in case screen size has changed
  var menuWidth = $("#menu").width();
  // slide to the right
  $('#profileInner').animate({
    "marginLeft" : 0
  });
});

$("#li-settings").click(function() {
  var srcOpen = $('#searchContainer').is(':visible');
  var addOpen = $('#addContainer').is(':visible');
  var msgOpen = $('#messagesContainer').is(':visible');
  var setOpen = $('#settingsContainer').is(':visible');  
  var mainOpen = $('#mainMenuContainer').is(':visible');  
  if (srcOpen) {
    $('#li-search').removeClass("current");
    $('#searchContainer').hide();
  }
  if (addOpen) {
    $('#li-add').removeClass("current");
    $('#addContainer').hide();
  }
  if (msgOpen) {
    $('#li-messages').removeClass("current");
    $('#messagesContainer').hide();
  }
  if (setOpen) {
    $('#li-settings').removeClass("current");
    $('#settingsContainer').hide();
    $('#mainMenuContainer').show();
    $('#recentContainer').show();
  } else {
    $('#li-settings').addClass("current");
    $('#settingsContainer').show();
  }
  if (mainOpen) {
    $('#mainMenuContainer').hide();
    $('#recentContainer').hide();
  }
  // get the current menu width in case screen size has changed
  var menuWidth = $("#menu").width();
  // slide to the right
  $('#profileInner').animate({
    "marginLeft" : 0
  });
});

// added to pull in any added modals used across all admin pages
<?php
  $lC_DirectoryListing = new lC_DirectoryListing('templates/' . $lC_Template->getCode() . '/modal/');
  $lC_DirectoryListing->setCheckExtension('php');
  foreach ($lC_DirectoryListing->getFiles() as $file) {
    include('templates/' . $lC_Template->getCode() . '/modal/' . $file['name']);
  }
?>
</script>