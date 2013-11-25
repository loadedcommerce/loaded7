<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: localization.php v1.0 2013-08-08 datazen $
*/
function quote_oanda_currency($code, $base = DEFAULT_CURRENCY) {
  $page = file('http://www.oanda.com/convert/fxdaily?value=1&redirected=1&exch=' . $code .  '&format=CSV&dest=Get+Table&sel_list=' . $base);

  $match = array();

  preg_match('/(.+),(\w{3}),([0-9.]+),([0-9.]+)/i', implode('', $page), $match);

  if (sizeof($match) > 0) {
    return $match[3];
  } else {
    return false;
  }
}

function quote_xe_currency($to, $from = DEFAULT_CURRENCY) {
  $page = file('http://www.xe.net/ucc/convert.cgi?Amount=1&From=' . $from . '&To=' . $to);

  $match = array();

  preg_match('/[0-9.]+\s*' . $from . '\s*=\s*([0-9.]+)\s*' . $to . '/', implode('', $page), $match);

  if (sizeof($match) > 0) {
    return $match[1];
  } else {
    return false;
  }
}
?>