/*
  $Id: general.php v1.0 2011-11-04  datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

function rowOverEffect(object) {
  if (object.className == 'deactivatedRow') {
    object.className = 'mouseOverDeactivatedRow';
  } else {
    object.className = 'mouseOver';
  }
}

function rowOutEffect(object) {
  if (object.className == 'mouseOverDeactivatedRow') {
    object.className = 'deactivatedRow';
  } else {
    object.className = '';
  }
}

function updateDatePullDownMenu(objForm, fieldName) {
  var pdmDays = fieldName + "_days";
  var pdmMonths = fieldName + "_months";
  var pdmYears = fieldName + "_years";

  time = new Date(objForm[pdmYears].options[objForm[pdmYears].selectedIndex].text, objForm[pdmMonths].options[objForm[pdmMonths].selectedIndex].value, 1);

  time = new Date(time - 86400000);

  var selectedDay = objForm[pdmDays].options[objForm[pdmDays].selectedIndex].text;
  var daysInMonth = time.getDate();

  for (var i=0; i<objForm[pdmDays].length; i++) {
    objForm[pdmDays].options[0] = null;
  }

  for (var i=0; i<daysInMonth; i++) {
    objForm[pdmDays].options[i] = new Option(i+1);
  }

  if (selectedDay <= daysInMonth) {
    objForm[pdmDays].options[selectedDay-1].selected = true;
  } else {
    objForm[pdmDays].options[daysInMonth-1].selected = true;
  }
}

function toggleDivBlocks(group, exempt) {
  if (!document.getElementsByTagName) return null;

  if (!exempt) exempt = "";

  var divs = document.getElementsByTagName("div");

  for(var i=0; i < divs.length; i++) {
    var div = divs[i];
    var id = div.id;

    if ((id != exempt) && (id.indexOf(group) == 0)) {
      hideBlock(id);
    }
  }

  showBlock(exempt);
}

function toggleInfoBox(exempt) {
  if (!exempt || !document.getElementsByTagName) return null;

  var infoBox = "infoBox_" + exempt;

  var divs = document.getElementsByTagName("div");

  for(var i=0; i < divs.length; i++) {
    var div = divs[i];
    var id = div.id;

    if (id.indexOf("infoBox_") == 0) {
      var infoBoxForm = id.substring(8);

      if (document.forms[infoBoxForm]) {
        document.forms[infoBoxForm].reset();
      }

      if (id != infoBox) {
        hideBlock(id);
      }
    }
  }

  showBlock(infoBox);
}

function showBlock(id) {
  if (document.getElementById) {
    itm = document.getElementById(id);
  } else if (document.all){
    itm = document.all[id];
  } else if (document.layers){
    itm = document.layers[id];
  }

  if (itm) {
    itm.style.display = "block";
  }
}

function hideBlock(id) {
  if (document.getElementById) {
    itm = document.getElementById(id);
  } else if (document.all){
    itm = document.all[id];
  } else if (document.layers){
    itm = document.layers[id];
  }

  if (itm) {
    itm.style.display = "none";
  }
}

function toggleClass(removeClass, addClass, cssClass, tagName) {
  if (!document.getElementsByTagName) return null;

  if (!tagName) tagName = "div";

  var tags = document.getElementsByTagName(tagName);

  for(var i=0; i < tags.length; i++) {
    var tag = tags[i];
    var id = tag.id;

    if ((id != addClass) && (id.indexOf(removeClass) == 0)) {
      tag.className = "";
    }
  }

  document.getElementById(addClass).className = cssClass;
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

/*
function Checkboxes(element) {

  var elementForm = element.form;
  var i = 0;

  for (i = 0; i < elementForm.length; i++) {
    if (elementForm[i].type == 'checkbox') {
      elementForm[i].checked = element.checked;
    }
  }
}
*/

$(document).ready(function() {
  $('.fieldTitleAsDefault').focus(function(srcc) {
    if ( $(this).val() == $(this)[0].title ) {
      $(this).removeClass('fieldDefaultText');
      $(this).val('');
    }
  });

  $('.fieldTitleAsDefault').blur(function() {
    if ( $(this).val() == '') {
      $(this).addClass('fieldDefaultText');
      $(this).val($(this)[0].title);
    }
  });

  $('.fieldTitleAsDefault').blur();        
});

function htmlSpecialChars(string) {
  return $('<span>').text(string).html();
};

/* Javascript version of zM_Tax::displayTaxRateValue() */

function displayTaxRateValue(value, padding) {
  if ( padding == null ) {
    padding = taxDecimalPlaces;
  }

  if ( value.indexOf('.') != -1 ) {
    while ( true ) {
      if ( value.substr(-1) == '0' ) {
        value = value.substr(0, value.length - 1);
      } else {
        if ( value.substr(-1) == '.' ) {
          value = value.substr(0, value.length - 1);
        }

        break;
      }
    }
  }

  if ( padding > 0 ) {
    var decimal_pos = value.indexOf('.');

    if ( decimal_pos != -1 ) {
      var decimals = value.substr(decimal_pos + 1).length;

      for ( var i = decimals; i < padding; i++ ) {
        value += '0';
      }
    } else {
      value += '.';

      for ( var i = 0; i < padding; i++ ) {
        value += '0';
      }
    }
  }

  return value + '%';
}

function print_r (array, return_val) {
    // Prints out or returns information about the specified variable  
    // 
    // version: 909.322
    // discuss at: http://phpjs.org/functions/print_r    // +   original by: Michael White (http://getsprink.com)
    // +   improved by: Ben Bryan
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +      improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    // -    depends on: echo
    // *     example 1: print_r(1, true);
    // *     returns 1: 1
    
    var output = "", pad_char = " ", pad_val = 4, d = this.window.document;    var getFuncName = function (fn) {
        var name = (/\W*function\s+([\w\$]+)\s*\(/).exec(fn);
        if (!name) {
            return '(Anonymous)';
        }        return name[1];
    };
 
    var repeat_char = function (len, pad_char) {
        var str = "";        for (var i=0; i < len; i++) {
            str += pad_char;
        }
        return str;
    }; 
    var formatArray = function (obj, cur_depth, pad_val, pad_char) {
        if (cur_depth > 0) {
            cur_depth++;
        } 
        var base_pad = repeat_char(pad_val*cur_depth, pad_char);
        var thick_pad = repeat_char(pad_val*(cur_depth+1), pad_char);
        var str = "";
         if (typeof obj === 'object' && obj !== null && obj.constructor && getFuncName(obj.constructor) !== 'PHPJS_Resource') {
            str += "Array\n" + base_pad + "(\n";
            for (var key in obj) {
                if (obj[key] instanceof Array) {
                    str += thick_pad + "["+key+"] => "+formatArray(obj[key], cur_depth+1, pad_val, pad_char);                } else {
                    str += thick_pad + "["+key+"] => " + obj[key] + "\n";
                }
            }
            str += base_pad + ")\n";        } else if (obj === null || obj === undefined) {
            str = '';
        } else { // for our "resource" class
            str = obj.toString();
        } 
        return str;
    };
 
    output = formatArray(array, 0, pad_val, pad_char); 
    if (return_val !== true) {
        if (d.body) {
            this.echo(output);
        }        else {
            try {
                d = XULDocument; // We're in XUL, so appending as plain text won't work; trigger an error out of XUL
                this.echo('<pre xmlns="http://www.w3.org/1999/xhtml" style="white-space:pre;">'+output+'</pre>');
            }            catch (e) {
                this.echo(output); // Outputting as plain text may work in some plain XML
            }
        }
        return true;    } else {
        return output;
    }
}

function is_array (mixed_var) {
    var key = '';
    var getFuncName = function (fn) {
        var name = (/\W*function\s+([\w\$]+)\s*\(/).exec(fn);
        if (!name) {
            return '(Anonymous)';
        }
        return name[1];
    };

    if (!mixed_var) {
        return false;
    }

    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};
    this.php_js.ini = this.php_js.ini || {};
    // END REDUNDANT

    if (typeof mixed_var === 'object') {

        if (this.php_js.ini['phpjs.objectsAsArrays'] &&  // Strict checking for being a JavaScript array (only check this way if call ini_set('phpjs.objectsAsArrays', 0) to disallow objects as arrays)
            (
            (this.php_js.ini['phpjs.objectsAsArrays'].local_value.toLowerCase &&
                    this.php_js.ini['phpjs.objectsAsArrays'].local_value.toLowerCase() === 'off') ||
                parseInt(this.php_js.ini['phpjs.objectsAsArrays'].local_value, 10) === 0)
            ) {
            return mixed_var.hasOwnProperty('length') && // Not non-enumerable because of being on parent class
                            !mixed_var.propertyIsEnumerable('length') && // Since is own property, if not enumerable, it must be a built-in function
                                getFuncName(mixed_var.constructor) !== 'String'; // exclude String()
        }

        if (mixed_var.hasOwnProperty) {
            for (key in mixed_var) {
                // Checks whether the object has the specified property
                // if not, we figure it's not an object in the sense of a php-associative-array.
                if (false === mixed_var.hasOwnProperty(key)) {
                    return false;
                }
            }
        }

        return true;
    }

    return false;
}

function urldecode (str) {
  return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}

function flagCheckboxes () { 
  var n = $(":checkbox:checked").length;
  if (n > 0) {
    $(":checkbox").attr("checked", false); 
  } else {
    $(":checkbox").attr("checked", true);         
  }
}