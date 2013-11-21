<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: general.php v1.0 2013-08-08 datazen $
*/

/**
* Wrapper function for set_time_limit(), which can't be used in safe_mode
*
* @param int $limit The limit to set the maximium execution time to
* @access public
*/

function lc_set_time_limit($limit) {
  if (!get_cfg_var('safe_mode')) {
    set_time_limit($limit);
  }
}

/**
* Redirect to a URL address
*
* @param string $url The URL address to redirect to
* @access public
*/

function lc_redirect_admin($url) {
  global $lC_Session;

  if ( (strpos($url, "\n") !== false) || (strpos($url, "\r") !== false) ) {
    $url = lc_href_link_admin(FILENAME_DEFAULT);
  }

  if (strpos($url, '&amp;') !== false) {
    $url = str_replace('&amp;', '&', $url);
  }

  header('Location: ' . $url);

  $lC_Session->close();

  exit;
}

/**
* Retrieve web server and database server information
*
* @access public
*/

function lc_get_system_information() {
  global $lC_Database;

  $Qdb_date = $lC_Database->query('select now() as datetime');
  $Qdb_uptime = $lC_Database->query('show status like "Uptime"');

  @list($system, $host, $kernel) = preg_split('/[\s,]+/', @exec('uname -a'), 5);

  $db_uptime = intval($Qdb_uptime->valueInt('Value') / 3600) . ':' . str_pad(intval(($Qdb_uptime->valueInt('Value') / 60) % 60), 2, '0', STR_PAD_LEFT);

  return array('date' => lC_DateTime::getShort(null, true),
    'system' => $system,
    'kernel' => $kernel,
    'host' => $host,
    'ip' => gethostbyname($host),
    'uptime' => @exec('uptime'),
    'http_server' => $_SERVER['SERVER_SOFTWARE'],
    'php' => PHP_VERSION,
    'zend' => (function_exists('zend_version') ? zend_version() : ''),
    'db_server' => DB_SERVER,
    'db_ip' => gethostbyname(DB_SERVER),
    'db_version' => 'MySQL ' . (function_exists('mysql_get_server_info') ? @mysql_get_server_info() : ''),
    'db_date' => lC_DateTime::getShort($Qdb_date->value('datetime'), true),
    'db_uptime' => $db_uptime);
}

/**
* Parse file permissions to a human readable layout
*
* @param int $mode The file permission to parse
* @access public
*/

function lc_get_file_permissions($mode) {
  // determine type
  if ( ($mode & 0xC000) == 0xC000) { // unix domain socket
    $type = 's';
  } elseif ( ($mode & 0x4000) == 0x4000) { // directory
    $type = 'd';
  } elseif ( ($mode & 0xA000) == 0xA000) { // symbolic link
    $type = 'l';
  } elseif ( ($mode & 0x8000) == 0x8000) { // regular file
    $type = '-';
  } elseif ( ($mode & 0x6000) == 0x6000) { //bBlock special file
    $type = 'b';
  } elseif ( ($mode & 0x2000) == 0x2000) { // character special file
    $type = 'c';
  } elseif ( ($mode & 0x1000) == 0x1000) { // named pipe
    $type = 'p';
  } else { // unknown
    $type = '?';
  }

  // determine permissions
  $owner['read']    = ($mode & 00400) ? 'r' : '-';
  $owner['write']   = ($mode & 00200) ? 'w' : '-';
  $owner['execute'] = ($mode & 00100) ? 'x' : '-';
  $group['read']    = ($mode & 00040) ? 'r' : '-';
  $group['write']   = ($mode & 00020) ? 'w' : '-';
  $group['execute'] = ($mode & 00010) ? 'x' : '-';
  $world['read']    = ($mode & 00004) ? 'r' : '-';
  $world['write']   = ($mode & 00002) ? 'w' : '-';
  $world['execute'] = ($mode & 00001) ? 'x' : '-';

  // adjust for SUID, SGID and sticky bit
  if ($mode & 0x800 ) $owner['execute'] = ($owner['execute'] == 'x') ? 's' : 'S';
  if ($mode & 0x400 ) $group['execute'] = ($group['execute'] == 'x') ? 's' : 'S';
  if ($mode & 0x200 ) $world['execute'] = ($world['execute'] == 'x') ? 't' : 'T';

  return $type .
  $owner['read'] . $owner['write'] . $owner['execute'] .
  $group['read'] . $group['write'] . $group['execute'] .
  $world['read'] . $world['write'] . $world['execute'];
}

/*
* Recursively remove a directory or a single file
*
* @param string $source The source to remove
* @access public
*/

function lc_remove($source) {
  global $lC_Language, $lC_MessageStack;

  if (is_dir($source)) {
    $dir = dir($source);

    while ($file = $dir->read()) {
      if ( ($file != '.') && ($file != '..') ) {
        if (is_writeable($source . '/' . $file)) {
          lc_remove($source . '/' . $file);
        } else {
          $lC_MessageStack->add('header', sprintf($lC_Language->get('ms_error_file_not_removable'), $source . '/' . $file), 'error');
        }
      }
    }

    $dir->close();

    if (is_writeable($source)) {
      return rmdir($source);
    } else {
      $lC_MessageStack->add('header', sprintf($lC_Language->get('ms_error_directory_not_removable'), $source), 'error');
    }
  } else {
    if (is_writeable($source)) {
      return unlink($source);
    } else {
      $lC_MessageStack->add('header', sprintf($lC_Language->get('ms_error_file_not_removable'), $source), 'error');
    }
  }
}

/**
* Return an image type that the server supports
*
* @access public
*/

function lc_dynamic_image_extension() {
  static $extension;

  if (!isset($extension)) {
    if (function_exists('imagetypes')) {
      if (imagetypes() & IMG_PNG) {
        $extension = 'png';
      } elseif (imagetypes() & IMG_JPG) {
        $extension = 'jpeg';
      } elseif (imagetypes() & IMG_GIF) {
        $extension = 'gif';
      }
    } elseif (function_exists('imagepng')) {
      $extension = 'png';
    } elseif (function_exists('imagejpeg')) {
      $extension = 'jpeg';
    } elseif (function_exists('imagegif')) {
      $extension = 'gif';
    }
  }

  return $extension;
}

/**
* Parse a category path to avoid loops with duplicate values
*
* @param string $cPath The category path to parse
* @access public
*/

function lc_parse_category_path($cPath) {
  // make sure the category IDs are integers
  $cPath_array = array_map('intval', explode('_', $cPath));

  // make sure no duplicate category IDs exist which could lock the server in a loop
  $tmp_array = array();
  $n = sizeof($cPath_array);
  for ($i=0; $i<$n; $i++) {
    if (!in_array($cPath_array[$i], $tmp_array)) {
      $tmp_array[] = $cPath_array[$i];
    }
  }

  return $tmp_array;
}

/**
* Return an array as a string value
*
* @param array $array The array to return as a string value
* @param array $exclude An array of parameters to exclude from the string
* @param string $equals The equals character to symbolize what value a parameter is defined to
* @param string $separator The separate to use between parameters
*/

function lc_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
  if (!is_array($exclude)) $exclude = array();

  $get_string = '';
  if (sizeof($array) > 0) {
    while (list($key, $value) = each($array)) {
      if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
        $get_string .= $key . $equals . $value . $separator;
      }
    }
    $remove_chars = strlen($separator);
    $get_string = substr($get_string, 0, -$remove_chars);
  }

  return $get_string;
}

/**
* Return a variable value from a serialized string
*
* @param string $serialization_data The serialized string to return values from
* @param string $variable_name The variable to return
* @param string $variable_type The variable type
*/

function lc_get_serialized_variable(&$serialization_data, $variable_name, $variable_type = 'string') {
  $serialized_variable = '';

  switch ($variable_type) {
    case 'string':
      $start_position = strpos($serialization_data, $variable_name . '|s');

      $serialized_variable = substr($serialization_data, strpos($serialization_data, '|', $start_position) + 1, strpos($serialization_data, '|', $start_position) - 1);
      break;
    case 'array':
    case 'object':
      if ($variable_type == 'array') {
        $start_position = strpos($serialization_data, $variable_name . '|a');
      } else {
        $start_position = strpos($serialization_data, $variable_name . '|O');
      }

      $tag = 0;

      for ($i=$start_position, $n=sizeof($serialization_data); $i<$n; $i++) {
        if ($serialization_data[$i] == '{') {
          $tag++;
        } elseif ($serialization_data[$i] == '}') {
          $tag--;
        } elseif ($tag < 1) {
          break;
        }
      }

      $serialized_variable = substr($serialization_data, strpos($serialization_data, '|', $start_position) + 1, $i - strpos($serialization_data, '|', $start_position) - 1);
      break;
  }

  return $serialized_variable;
}

/**
* Call a function given in string format used by configuration set and use functions
*
* @param string $function The complete function to call
* @param string $default The default value to pass to the function
* @param string $key The key value to use for the input field
*/

function lc_call_user_func($function, $default = null, $key = null) {
  global $lC_Vqmod;

  if (strpos($function, '::') !== false) {
    $class_method = explode('::', $function);

    return call_user_func(array($class_method[0], $class_method[1]), $default, $key);
  } else {
    $function_name = $function;
    $function_parameter = '';

    if (strpos($function, '(') !== false) {
      $function_array = explode('(', $function, 2);

      $function_name = $function_array[0];
      $function_parameter = substr($function_array[1], 0, -1);
    }

    if (!function_exists($function_name)) {
      include($lC_Vqmod->modCheck('includes/functions/cfg_parameters/' . $function_name . '.php'));
    }

    if (!empty($function_parameter)) {
      return call_user_func($function_name, $function_parameter, $default, $key);
    } else {
      return call_user_func($function_name, $default, $key);
    }
  }
}

/**
* Validate a plain text password against an encrypted value
*
* @param string $plain The plain text password
* @param string $encrypted The encrypted password to validate against
*/

function lc_validate_password($plain, $encrypted) {
  if (!empty($plain) && !empty($encrypted)) {  
    if (strstr($encrypted, '::')) {  // sha256 hash
      // split apart the hash / salt
      $stack = explode('::', $encrypted);

      if (sizeof($stack) != 2) {
        return false;
      }

      if (hash('sha256', $stack[1] . $plain) == $stack[0]) {
        return true;
      }      

    } else { // legacy md5 hash - will be removed in production release       
      // split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) {
        return false;
      }

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }  
  }

  return false;
}

function lc_toObjectInfo($array) {
  return new lC_ObjectInfo($array);
}

function lc_get_zone_data($zone_id = null, $zone_code = null, $zone_name = null) {
  global $lC_Database;

  if ($zone_id == null && $zone_code == null && $zone_name = null) return false;

  if ($zone_id != null) {
    $Qzones = $lC_Database->query('select * from :table_zones where zone_id = :zone_id limit 1');
    $Qzones->bindInt(':zone_id', $zone_id);
  } else if ($zone_code != null) {
    $Qzones = $lC_Database->query('select * from :table_zones where zone_code = :zone_code limit 1');
    $Qzones->bindInt(':zone_code', $zone_code);
  } else if ($zone_name != null) {    
    $Qzones = $lC_Database->query('select * from :table_zones where zone_name = :zone_name limit 1');
    $Qzones->bindInt(':zone_name', $zone_name);
  }
  $Qzones->bindTable(':table_zones', TABLE_ZONES);
  $Qzones->execute();

  $data = $Qzones->toArray();

  $Qzones->freeResult();

  return $data;

}

function lc_store_country_has_zones() {
  global $lC_Database;

  $QcountryId = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = :configuration_key');
  $QcountryId->bindTable(':table_configuration', TABLE_CONFIGURATION);
  $QcountryId->bindValue(':configuration_key', 'STORE_COUNTRY');
  $QcountryId->execute();

  $Qcountry = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id limit 1');
  $Qcountry->bindTable(':table_zones', TABLE_ZONES);
  $Qcountry->bindValue(':zone_country_id', $QcountryId->value('configuration_value'));
  $Qcountry->execute();

  $result = ($Qcountry->value('zone_id')) ? 1 : 0;

  return $result;

}

function lc_get_weight_class_data($weight_class_id = null) {
  global $lC_Database; 

  if ($weight_class_id == null) return false;

  if ($weight_class_id != null) {
    $Qweight = $lC_Database->query('select * from :table_weight_class where weight_class_id = :weight_class_id limit 1');
    $Qweight->bindInt(':weight_class_id', $weight_class_id);
  }
  $Qweight->bindTable(':table_weight_class', TABLE_WEIGHT_CLASS);
  $Qweight->execute();

  $data = $Qweight->toArray();

  $Qweight->freeResult();

  return $data;
}
?>