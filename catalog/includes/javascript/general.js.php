<?php
/**
  @package    catalog::javascript
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: general.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template, $lC_Language; 
?>
<script>
$(document).ready(function() {
  
  function setMaintenanceMode(s) {
    if (s == 'on') {
      $("#loaded7").mask('<span style="font-size:2em !important;"><?php echo $lC_Language->get('update_message_text1'); ?></span>');
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
 
  var showDebug = '<?php echo $lC_Template->showDebugMessages(); ?>';
  if (showDebug) {
    var debugOutput = <?php echo (isset($_SESSION['debugStack']) && !empty($_SESSION['debugStack'])) ? $_SESSION['debugStack'] : "''" ?>;
    $('#debug-info-container > span').html(debugOutput);
    $('#debug-info-container').show();
  } else {
    $('#debug-info-container').hide();
  }  
   
  // run this last - determine media type
  setTimeout('_setMediaType()', 1000);

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
  
  // reset responsive helper classes
  $('.hide-on-mobile-portrait').show();
  $('.hide-on-mobile-landscape').show();
  $('.hide-on-tablet-portrait').show();
  $('.hide-on-tablet-landscape').show();  
  
  if (winW <= 320) {
    mtype = 'mobile-portrait'; //320
    $('.hide-on-mobile-portrait').hide();
  } else if (winW > 320 && winW <= 480) {
    mtype = 'mobile-landscape'; //480
    $('.hide-on-mobile-landscape').hide();
  } else if (winW > 480 && winW <= 600) {
    mtype = 'small-tablet-portrait'; //600
    $('.hide-on-mobile-landscape').hide();
  } else if (winW > 600 && winW <= 768) {  
    mtype = 'tablet-portrait'; //768   
    $('.hide-on-tablet-portrait').hide();
  } else if (winW > 768 && winW <= 800) {
    mtype = 'small-tablet-landscape'; //800
    $('.hide-on-tablet-landscape').hide();
  } else if (winW > 800 && winW <= 1024) {
    mtype = 'tablet-landscape'; //1024    
    $('.hide-on-tablet-landscape').hide();
  } else if (winW > 1024) {
    mtype = 'desktop';    
  }
  
  var sizeStored = '<?php echo $_SESSION['mediaSize']; ?>';
  if (sizeStored != winW) {
    var jsonLink = '<?php echo lc_href_link('rpc.php', 'index&action=setMediaType&type=MTYPE&size=MSIZE', 'AUTO'); ?>';
    var jsonLink = jsonLink.replace('MTYPE', mtype).replace('MSIZE', parseInt(winW));
    $.getJSON(jsonLink.split('amp;').join(''),
      function (data) {
        return true;
      }
    );  
  }

  return mtype;
}

function addCoupon() {
  var code = $('#coupon_code').val();
  var module = '<?php echo $lC_Template->getModule(); ?>';
  var jsonLink = '<?php echo lc_href_link('rpc.php', 'checkout&action=addCoupon&code=CODE', 'AUTO'); ?>';   
  $.getJSON(jsonLink.replace('CODE', code).split('amp;').join(''),
    function (data) {
      if (data.rpcStatus != 1) {
        $('#coupon_code').val('');
        if (data.rpcStatus == -2) {
          alert('<?php echo $lC_Language->get('ms_error_coupon_not_found'); ?>');
        } else if (data.rpcStatus == -3) {
          alert('<?php echo $lC_Language->get('ms_error_coupon_not_valid'); ?>');
        } else if (data.rpcStatus == -4) {
          alert('<?php echo $lC_Language->get('ms_error_coupon_purchase_not_over_1'); ?> ' + data.msg + ' <?php echo $lC_Language->get('ms_error_coupon_purchase_not_over_2'); ?>');
        } else if (data.rpcStatus == -5) {
          alert('<?php echo $lC_Language->get('ms_error_coupon_start_date'); ?> ' + data.msg + '. <?php echo $lC_Language->get('ms_error_coupon_check_coupon'); ?>');                    
        } else if (data.rpcStatus == -6) {
          alert('<?php echo $lC_Language->get('ms_error_coupon_expires_date'); ?> ' + data.msg + '. <?php echo $lC_Language->get('ms_error_coupon_check_coupon'); ?>');
        } else if (data.rpcStatus == -7) {
          alert('<?php echo $lC_Language->get('ms_error_coupon_max_uses'); ?>');
        } else if (data.rpcStatus == -8) {
          alert('<?php echo $lC_Language->get('ms_error_coupon_max_uses'); ?>');                    
        } else {
          alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        }       
        return false;
      }
      window.location.href = window.location.href;
    }
  );  
}

function removeCoupon(code) {
  var module = '<?php echo $lC_Template->getModule(); ?>';
  var jsonLink = '<?php echo lc_href_link('rpc.php', 'checkout&action=removeCoupon&code=CODE', 'AUTO'); ?>';   
  $.getJSON(jsonLink.replace('CODE', code).split('amp;').join(''),
    function (data) {
      if (data.rpcStatus != 1) {
        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      window.location.href = window.location.href;
    }
  );  
}

// make product listing boxes equal heights
(function($) {
  $.fn.equalHeights = function(minHeight, maxHeight) {
    tallest = (minHeight) ? minHeight : 0;
    this.each(function() {
      if($(this).height() > tallest) {
        tallest = $(this).height();
      }
    });
    if((maxHeight) && tallest > maxHeight) tallest = maxHeight;
    return this.each(function() {
      $(this).height(tallest).css("overflow","hidden");
    });
  }
})(jQuery);

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
</script>