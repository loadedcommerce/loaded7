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
global $lC_Template, $lC_Language;
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
  } else if (module == 'content') {
    $("#shortcuts li").parent().find('li').removeClass("current");
    $("#sc-content").addClass('current'); 
  } else if (module == 'categories' || module == 'specials' || module == 'manufacturers' || module == 'reviews' || (module.indexOf('product') != -1) ) {
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
  } else if ($.template.mediaQuery.name === 'mobile-landscape') { 
    $('#logoImg').attr('style', 'margin-top:2px !important;');
  } else if ($.template.mediaQuery.name === 'tablet-portrait') {  
    $('#logoImg').attr('style', 'margin-top:2px !important;');
  } else if ($.template.mediaQuery.name === 'tablet-landscape') {  
    $('#logoImg').attr('style', 'margin-top:-1px !important;');
  } else { // desktop
    $('#logoImg').attr('style', 'margin-top:-1px !important;');
  }
  
  // profile slate addition
  // get the menu width
  var menuWidth = $("#menu").width();
  // apply twice the menu width to the inner div
  $("#profileInner").css({'width':menuWidth * 2});
  // on screen resize get the new menu width and apply it for click functions
  $(window).resize(function() {
    var menuWidthResized = $("#menu").width();
    $("#profileInner").css({'width':menuWidthResized * 2});
    $('#profileInner').css({"margin-left":"0px"});
  });
  // profile left is clicked
  $("#profileLeft").click(function(){
    // get the current menu width in case screen size has changed
    var menuWidth = $("#menu").width();
    // slide to the left
    $('#profileInner').animate({
      "marginLeft" : "-=" + menuWidth
    });
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
     
});

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

// added to pull in any added modals used across all admin pages
<?php
$generalModalDir = 'templates/' . $lC_Template->getCode() . '/modal/';
$files = scandir($generalModalDir);
foreach ($files as $file) {
  if ($file != "." && $file != ".." && $file != ".htaccess") {
    if (!is_dir($generalModalDir . $file) === true) {
      include($generalModalDir . $file); 
    }
  }
}
?>
</script>