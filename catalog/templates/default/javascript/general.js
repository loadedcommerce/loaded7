/*
  $Id: general.js v1.0 2011-11-04  datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

// IE9 does not support HTML5 placeholder so we have this fix
$(document).ready(function() {
  if ( $.browser.msie ) {  
    $("input[type=text],input[type=password],textarea").each(function(){
      if ($.trim($(this).attr("holder")) != "" && $.trim($(this).val() == "")) {
        var field = $(this);
        var ffield = $(field).attr("id")+"__jquery_placeholder_passwordFakeField";
        //replace password fields with a fake field
        if ($(field).attr("type") == "password") {
          var newfield  = $("<input type='text' style='width:99%;' class='"+$(field).attr("class")+"' id='"+ffield+"' tabindex='"+$(field).attr("tabindex")+"' holder='"+$(field).attr("holder")+"' />").focus(function() {
                    $(this).hide();
                    $(field).show();
                    $(field).focus();
                  }).keypress(function(event) {
                    event.preventDefault();
                  });
          $(newfield).insertBefore(field);
          $(field).hide();
        }
        //bind focus event
        $(field).bind("focus",function() {
          $("#"+ffield).hide();
          $(field).show();
          if ($(field).hasClass("holder")) {
            $(field).val("");
            $(field).removeClass("holder");
          }
        });
        //bind blur event, if is a password field and value='' show the fakefield
        $(field).bind("blur",function() {
          if ($(field).val() == "") {
            if ($(field).attr("type") == "password") {
              $(field).hide();
              $("#"+ffield).show();
            }
            else {
              $(field).val($(field).attr("holder"));
              $(field).addClass("holder");
            }
          }
        });
        //bind change event, if value changed return to non holding state
        $(field).bind("change",function() {
          $(field).removeClass("holder");
        });
        //bind parent form submit, clean holding fields
        $(field).parents("form").submit(function() {
          $(this).find(".holder").each(function() {
          if ($(this).val() == $(this).attr("holder")) { $(this).val(""); }
          });
        });
      }
    });
    setTimeout("__jquery_placeholder_goTitling()",100);
  }
});
//change the holding values
function __jquery_placeholder_goTitling() {
  $("input[type=text],textarea").each(function(){
    if (($(this).attr("holder") != "") && ($.trim($(this).val()) == "")) {
      $(this).val($(this).attr("holder"));
      $(this).addClass("holder");
    }
  });
}

$(document).ready(function() {
  $('#listView').click(function(){
    $('#viewList').show();
    $('#viewGrid').hide();
  });
  $('#gridView').click(function(){
    $('#viewGrid').show();
    $('#viewList').hide();
  });
});

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

function checkBox(object) {
  document.account_newsletter.elements[object].checked = !document.account_newsletter.elements[object].checked;
}

function popupWindow(url, name, params) {
  window.open(url, name, params).focus();
}