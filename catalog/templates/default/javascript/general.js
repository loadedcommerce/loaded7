/*
  $Id: general.js v1.0 2011-11-04  datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$(function(){
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