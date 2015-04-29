<?php
/**
  @package    catalog::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: compatibility.php v1.0 2013-08-08 datazen $
*/

/**
 * Forcefully disable register_globals if enabled
 *
 * Based from work by Richard Heyes (http://www.phpguru.org)
 */

if ((int)ini_get('register_globals') > 0) {
  if (isset($_REQUEST['GLOBALS'])) {
    die('GLOBALS overwrite attempt detected');
  }

  $noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

  $input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) ? (array)$_SESSION : array());

  foreach ($input as $k => $v) {
    if (!in_array($k, $noUnset) && isset($GLOBALS[$k])) {
      unset($GLOBALS[$k]);
    }
  }

  unset($noUnset);
  unset($input);
  unset($k);
  unset($v);
}

/**
* Forcefully disable magic_quotes_gpc if enabled
*
* Based from work by Ilia Alshanetsky (Advanced PHP Security)
*/

if ((int)get_magic_quotes_gpc() > 0) {
  $in = array(&$_GET, &$_POST, &$_COOKIE);

  while (list($k, $v) = each($in)) {
    foreach ($v as $key => $val) {
      if (!is_array($val)) {
        $in[$k][$key] = stripslashes($val);

        continue;
      }

      $in[] =& $in[$k][$key];
    }
  }

  unset($in);
  unset($k);
  unset($v);
  unset($key);
  unset($val);
}

/**
* checkdnsrr() not implemented on Microsoft Windows platforms
*/

if (!function_exists('checkdnsrr')) {
  function checkdnsrr($host, $type) {
    if(!empty($host) && !empty($type)) {
      @exec('nslookup -type=' . escapeshellarg($type) . ' ' . escapeshellarg($host), $output);

      foreach ($output as $k => $line) {
        if(preg_match('/^/i' . $host, $line)) {
          return true;
        }
      }
    }

    return false;
  }
}

/**
* ctype_alnum() natively supported from PHP 4.3
*/

if (!function_exists('ctype_alnum')) {
  function ctype_alnum($string) {
    return (preg_match('/^[a-z0-9]*$/i', $string) > 0);
  }
}

/**
* ctype_xdigit() natively supported from PHP 4.3
*/

if (!function_exists('ctype_xdigit')) {
  function ctype_xdigit($string) {
    return (preg_match('/^([a-f0-9][a-f0-9])*$/i', $string) > 0);
  }
}

/**
* is_a() natively supported from PHP 4.2
*/

if (!function_exists('is_a')) {
  function is_a($object, $class) {
    if (!is_object($object)) {
      return false;
    }

    if (get_class($object) == strtolower($class)) {
      return true;
    } else {
      return is_subclass_of($object, $class);
    }
  }
}

/**
* floatval() natively supported from PHP 4.2
*/

if (!function_exists('floatval')) {
  function floatval($float) {
    return doubleval($float);
  }
}

/**
* stream_get_contents() natively supported from PHP 5.0
*/

if (!function_exists('stream_get_contents')) {
  function stream_get_contents($resource) {
    $result = '';

    if (is_resource($resource)) {
      while (!feof($resource)) {
        $result .= @fread($resource, 2048);
      }
    }

    return $result;
  }
}

/**
* sha1() natively supported from PHP 4.3
*/

if (!function_exists('sha1')) {
  function sha1($source) {
    global $lC_Vqmod;
    
    if (function_exists('mhash')) {
      if (($hash = @mhash(MHASH_SHA1, $source)) !== false) {
        return bin2hex($hash);
      }
    }

    if (!function_exists('calc_sha1')) {
      include($lC_Vqmod->modCheck('ext/sha1/sha1.php'));
    }

    return calc_sha1($source);
  }
}

if (!function_exists('lc_strrpos_string')) {
  function lc_strrpos_string($haystack, $needle, $offset = 0) {
    if ( !empty($haystack) && !empty($needle) && ( $offset <= strlen($haystack) ) ) {
      $last_pos = $offset;
      $found = false;

      while ( ( $curr_pos = strpos($haystack, $needle, $last_pos) ) !== false ) {
        $found = true;
        $last_pos = $curr_pos + 1;
      }

      if ( $found === true ) {
        return $last_pos - 1;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}
/**
* Returns the request type
*
* @access  public
* @return  string;
*/
if (!function_exists('getRequestType')) {
  function getRequestType() {
    $isSecure = false;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
      $isSecure = true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
      $isSecure = true;
    }
    $request_type = $isSecure ? 'https' : 'http';   
     
    return $request_type;
  }
}

if (!function_exists('setLocalization')) {
  function setLocalization() {
    global $lC_Database, $lC_Currencies, $lC_Language, $lC_Session;
 
//unset($_SESSION['localization']);

 //   if (isset($_SESSION['localization']['currency']) || isset($_SESSION['localization']['language'])) return;
    
    $_SESSION['localization'] = array();
    
    $Qlocal = $lC_Database->query('select * from :table_localization where domain = :domain limit 1');
    $Qlocal->bindTable(':table_localization', TABLE_LOCALIZATION);
    $Qlocal->bindValue(':domain', $_SERVER['HTTP_HOST']);
    $Qlocal->execute(); 
    
    if ($Qlocal->numberOfRows() > 0) {
      $_SESSION['currency'] = $lC_Currencies->getCode($Qlocal->valueInt('currencies_id'));
      $_SESSION['language'] = $lC_Language->getCodeFromID($Qlocal->valueInt('language_id'));
      $_SESSION['localization']['domain'] = $Qlocal->value('domain');   
      $_SESSION['localization']['show_tax'] = $Qlocal->valueInt('show_tax');   
      $_SESSION['localization']['language'] = $lC_Language->getCodeFromID($Qlocal->valueInt('language_id'));   
      $_SESSION['localization']['currency'] = $lC_Currencies->getCode($Qlocal->valueInt('currencies_id')); 
      $_SESSION['localization']['default_tax_zone'] = $Qlocal->valueInt('default_tax_zone');   
      $_SESSION['localization']['base_price_modifier'] = $Qlocal->valueDecimal('base_price_modifier');   
      $_SESSION['localization']['session_id'] = $lC_Session->getID();   
      $_SESSION['localization']['session_name'] = $lC_Session->getName();  
      
      $lC_Language->set($_SESSION['localization']['language']);
      $lC_Language->load('general');
      $lC_Language->load('modules-boxes');
      $lC_Language->load('modules-content');      
      header('Content-Type: text/html; charset=' . $lC_Language->getCharacterSet());
      lc_setlocale(LC_TIME, explode(',', $lC_Language->getLocale()));      
    }

    $Qlocal->freeResult();
    
    // overrides
    $no_tax_or = (isset($_GET['no_tax']) && empty($_GET['no_tax']) === false) ? $_GET['no_tax'] : false;
    if ($no_tax_or !== false) $_SESSION['localization']['no_tax'] = $no_tax_or;
  }
}
?>