/*
  $Id: checkout_payment.js v1.0 2011-11-04  datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

var selected;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment_method[0]) {
    document.checkout_payment.payment_method[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment_method.checked=true;
  }
}

function mod10(number) {
  var nCheck = 0;
  var nDigit = 0;
  var bEven = false;

  for (n = number.length - 1; n >= 0; n--) {
    var cDigit = number.charAt(n);
    var nDigit = parseInt(cDigit, 10);
    if (bEven) {
      if ((nDigit *= 2) > 9) {
        nDigit -= 9;
      }
    }
    nCheck += nDigit;
    bEven = !bEven;
  }

  return (nCheck % 10) == 0;
}