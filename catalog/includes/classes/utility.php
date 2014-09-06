<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: utility.php v1.1 2014-02-12 datazen $
*/
class utility {
 /**
  * Generate Globally Unique Identifier (GUID)
  *
  * @param boolean $include_braces Set to true if the final guid needs to be wrapped in curly braces
  * @param boolean $include_dashes Set to true include dashes in the guid
  * @access public
  * @return string
  */
  public static function generateUID($_include_braces = false, $_include_dashes = false) {
    mt_srand((double) microtime() * 10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));

    $uid = substr($charid,  0, 8) . '-' .
           substr($charid,  8, 4) . '-' .
           substr($charid, 12, 4) . '-' .
           substr($charid, 16, 4) . '-' .
           substr($charid, 20, 12);

    if ($_include_braces) {
      $uid = '{' . $uid . '}';
    }
    if (!$_include_dashes) {
      $uid = str_replace("-", "", $uid);
    }
     
    return $uid;
  }
 /**
  * Convert stdClass object to an array
  *
  * @param  object  $_class The object to convert
  * @access public
  * @return array
  */
  public static function obj2arr(stdClass $_class){
    $_class = (array)$_class;
    foreach($_class as $key => $value){
      if(is_object($value) && get_class($value) === 'stdClass'){
        $_class[$key] = self::obj2arr($value);
      }
    }
    return $_class;
  }
 /**
  * Convert array to stdClass object
  *
  * @param  array   $_array The array to convert
  * @access public
  * @return object
  */
  public static function arr2obj(array $_array){
    foreach($_array as $key => $value){
      if(is_array($value)){
        $_array[$key] = self::arr2obj($value);
      }
    }
    return (object)$_array;
  }
 /**
  * Convert array to XML
  *
  * @param  array   $_data          The array to convert
  * @param  string  $_rootNodeName  The root node name
  * @param  string  $_xml           The XML string (optional)
  * @access public
  * @return object
  */
  public static function arr2xml($_data, $_rootNodeName = 'data', $_xml = NULL, $encode = TRUE) {
    // turn off compatibility mode as simple xml throws a wobbly if you don't.
    if (ini_get('zend.ze1_compatibility_mode') == 1) {
      ini_set ('zend.ze1_compatibility_mode', 0);
    }

    if ($_xml == null) {
      $_xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$_rootNodeName />");
    }

    // loop through the data passed in.
    foreach($_data as $key => $value) {
      // no numeric keys in our xml please!
      if (is_numeric($key)) {
        // make string key...
        $key = "unknownNode_". (string) $key;
      }

      // replace anything not alpha numeric
      $key = preg_replace('/[^a-z0-9\_]/i', '', $key);

      // if there is another array found recrusively call this function
      if (is_array($value)) {
        $node = $_xml->addChild($key);
        // recrusive call.
        self::arr2xml($value, $_rootNodeName, $node);
      } else {
        // add single node.
        $value = ($encode) ? @htmlentities($value) : $value;
        $_xml->addChild($key,$value);
      }
    }

    // pass back as string. or simple xml object if you want!
    return $_xml->asXML();
  }
 /**
  * Convert xml string to array
  *
  * @param string $_xml  The XML string
  * @param string $ns   The XML namespace (optional)
  * @access public  
  * @return array
  */ 
  public static function xml2arr($_xml, $get_attributes = 1, $priority = 'tag') {
    if(!$_xml) return array();
    
    $xml = substr($_xml, strpos($_xml, '<?xml'));
  
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); 
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($xml), $xml_values);
    xml_parser_free($parser);

    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Reference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
      unset($attributes,$value);//Remove existing values, or there will be trouble

      //This command will extract these variables into the foreach scope
      // tag(string), type(string), level(int), attributes(array).
      extract($data);//We could use the array by itself, but this cooler.

      $result = array();
      $attributes_data = array();

      if(isset($value)) {
        if($priority == 'tag') $result = $value;
        else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
      }

      //Set the attributes too.
      if(isset($attributes) and $get_attributes) {
        foreach($attributes as $attr => $val) {
          if($priority == 'tag') $attributes_data[$attr] = $val;
          else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
        }
      }

      //See tag status and do the needed.
      if($type == "open") {//The starting of the tag '<tag>'
        $parent[$level-1] = &$current;
        if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
          $current[$tag] = $result;
          if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
          $repeated_tag_index[$tag.'_'.$level] = 1;

          $current = &$current[$tag];

        } else { //There was another element with the same tag name

          if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
            $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
            $repeated_tag_index[$tag.'_'.$level]++;
          } else {//This section will make the value an array if multiple tags with the same name appear together
            $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
            $repeated_tag_index[$tag.'_'.$level] = 2;

            if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
              $current[$tag]['0_attr'] = $current[$tag.'_attr'];
              unset($current[$tag.'_attr']);
            }

          }
          $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
          $current = &$current[$tag][$last_item_index];
        }

      } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
        //See if the key is already taken.
        if(!isset($current[$tag])) { //New Key
          $current[$tag] = $result;
          $repeated_tag_index[$tag.'_'.$level] = 1;
          if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

        } else { //If taken, put all things inside a list(array)
          if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

            // ...push the new element into that array.
            $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

            if($priority == 'tag' and $get_attributes and $attributes_data) {
              $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
            }
            $repeated_tag_index[$tag.'_'.$level]++;

          } else { //If it is not an array...
            $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
            $repeated_tag_index[$tag.'_'.$level] = 1;
            if($priority == 'tag' and $get_attributes) {
              if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                unset($current[$tag.'_attr']);
              }

              if($attributes_data) {
                $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
              }
            }
            $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
          }
        }

      } elseif($type == 'close') { //End of tag '</tag>'
        $current = &$parent[$level-1];
      }
    }
           
    return $xml_array;
  }     
 /**
  * Convert nvp string to array
  *
  * @param string $_str       The NVP string
  * @param string $_separator The NVP separator
  * @param string $_delim     The NVP delimiter
  * @access public  
  * @return array
  */
  public static function nvp2arr($_str, $_separator = '=', $_delim = '&') {
    $elems = explode($_delim, $_str);
    foreach( $elems as $elem => $val ) {
        $val = trim($val);
        $nameVal[] = explode($_separator, $val);
        $arr[trim($nameVal[$elem][0])] = trim($nameVal[$elem][1]);
    }
    
    return $arr;
  }
 /**
  * Decode html specialchars when missing semicolon
  *
  * @param string $_str The string to convert
  * @access public
  * @return string
  */ 
  public static function specialchars_decode($_str) {
    $str = preg_replace('/%3A/', ':', $_str);
    $str = preg_replace('/%7C/', '|', $str);

    return $str;
  } 
 /**
  * Convert array to nvp string
  *
  * @param array $_arr The array to convert
  * @access public
  * @return string
  */  
  public static function arr2nvp($_arr, $_removeNullValues = TRUE, $_urlencode = TRUE) {
    $nvp = '';
    while(list($key, $value) = each($_arr)) {
      if ($value == NULL && $_removeNullValues) continue;
      if (is_array($key) && empty($key) && $_removeNullValues) continue;
      if ($_urlencode) {
        $nvp .= $key . '=' . @urlencode(str_replace(',', '', $value)) . '&';
      } else {
        $nvp .= $key . '=' . str_replace(',', '', $value) . '&';
      }
    }
    $nvp = substr($nvp, 0, strlen($nvp) - 1);  
    
    return $nvp;
  } 
  /**
  * Convert array to JSON
  *
  * @param array $_arr The array to convert
  * @access public
  * @return json
  */  
  public static function arr2json($_arr) {
    return json_encode($_arr);
  }
  /**
  * Convert JSON to array
  *
  * @param array $_arr The array to convert
  * @access public
  * @return json
  */  
  public static function json2arr($_json) {
    return json_decode($_json);
  }  
 /**
  * Sanitize a string 
  *
  * @param string $_string The string to sanitize
  * @access public
  * @return string
  */   
  public static function sanitizeString($_string) {
    $string = str_replace(' +', ' ', trim($_string));

    return preg_replace("/[<>]/", '_', $string);
  } 
 /**
  * Remove blank values from an array 
  *
  * @param array $_arr The array to clean
  * @access public
  * @return array
  */   
  public static function cleanArr($_arr) {
    if (!is_array($_arr) || empty($_arr)) return;
    $result = array();
    foreach ($_arr as $key => $value) {
      if ($key == '') continue;
      if ($value == '') continue;
      $result[$key] = $value;  
    }
    
    return $result;
  }
 /**
  * Flatten a multidimensional array to one dimension, optionally preserving keys.
  *
  * @param array $_arr          The array to clean
  * @param array $_preserveKeys 0 (default) to not preserve keys, 1 to preserve string keys only, 2 to preserve all keys
  * @param array $_out          Internal use argument for recursion
  * @access public
  * @return array
  */  
  public static function flattenArr($_arr, $preserveKeys = 0, &$out = array()) {
    if (!is_array($_arr)) return $_arr;
    foreach($_arr as $key => $child) {
      if(is_array($child)) {
        $out = self::flattenArr($child, $preserveKeys, $out);
      } else if($preserveKeys + is_string($key) > 1) {
        $out[$key] = $child;
      } else {
        $out[] = $child;
      }
    }
    return $out;
  }
 /**
  * Sort a multidimensional array by key value.
  *
  * @param array $data        The data array to sort
  * @param array $sortKey     The value key to sort on
  * @param array $sortFlags   The ksort flags; default is SORT_ASC
  * @access public
  * @return array
  */    
  public static function sortByKeyValue($data, $sortKey, $sortFlags = SORT_ASC) {
    if (empty($data) or empty($sortKey)) return $data;

    $ordered = array();
    foreach ($data as $key => $value) {
        $ordered[$value[$sortKey]] = $value;
    }

    ksort($ordered, $sortFlags);

    return array_values($ordered); // array_values() added for identical result with multisort
}  
 /**
  * Return the product version
  *
  * @access public
  * @return string
  */
  public static function getVersion() {
    
    $vInfo = explode('|', array_shift(array_values(preg_split('/\r\n|\r|\n/', file_get_contents(DIR_FS_CATALOG . 'includes/version.txt'), 2))));

    return $vInfo[0];
  }
 /**
  * Return the version date
  *
  * @access public
  * @return string
  */
  public static function getVersionDate() {
    $vInfo = explode('|', array_shift(array_values(preg_split('/\r\n|\r|\n/', file_get_contents(DIR_FS_CATALOG . 'includes/version.txt'), 2))));

    return $vInfo[1];  
  } 
 /**
  * Check if exec() is available and has permission to use
  *
  * @access public
  * @return boolean
  */
  public static function execEnabled() {
    if ( function_exists('exec') && !in_array('exec', array_map('trim',explode(', ', ini_get('disable_functions')))) ) {
      if( @exec('echo EXEC') == 'EXEC' ){
        return true;
      }
    }
    
    return false;
  } 
 /**
  * Check which server OS
  *
  * @access public
  * @return string
  */
  public static function serverOS() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        return 'windows';
    } else {
        return 'linux';
    }    
  } 
 /**
  * Check if server OS is Linux
  *
  * @access public
  * @return boolean
  */  
  public static function isLinux() {
    return (self::serverOS() == 'linux') ? true : false;
  }
 /**
  * Check if server OS is Windows
  *
  * @access public
  * @return boolean
  */
  public static function isWindows() {
    return (self::serverOS() == 'windows') ? true : false;
  }  
 /**
  * Check if the Pro product is installed
  *
  * @access public
  * @return boolean
  */
  public static function isPro() {
    global $lC_Database;

    $Qcheck = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = :configuration_key limit 1');
    $Qcheck->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcheck->bindValue(':configuration_key', 'ADDONS_SYSTEM_LOADED_7_PRO_STATUS');
    $Qcheck->execute(); 
  
    $isPro = (($Qcheck->value('configuration_value') == '1') ? true : false);  
    
    $Qcheck->freeResult();    
    
    if ($isPro && file_exists(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/controller.php')) {
      return true;
    } 
    
    return false;
  }
 /**
  * Get the Pro version
  *
  * @access public
  * @return boolean
  */
  public static function getProVersion() {
    global $lC_Addons;

    if (!isset($lC_Addons)) $lC_Addons = new lC_Addons;
    $aoArr = $lC_Addons->getAddons('enabled');
         
    return $aoArr['Loaded_7_Pro']['version'];
  }  
 /**
  * Check if the B2B product is installed
  *
  * @access public
  * @return boolean
  */
  public static function isB2B() {
    global $lC_Database;

    $Qcheck = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = :configuration_key limit 1');
    $Qcheck->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcheck->bindValue(':configuration_key', 'ADDONS_SYSTEM_LOADED_7_B2B_STATUS');
    $Qcheck->execute(); 
  
    $isB2B = (($Qcheck->value('configuration_value') == '1') ? true : false);  
    
    $Qcheck->freeResult();    
    
    if ($isB2B && file_exists(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/controller.php')) {
      return true;
    } 
    
    return false;
  }
 /**
  * Get the B2B version
  *
  * @access public
  * @return boolean
  */
  public static function getB2BVersion() {
    global $lC_Addons;

    if (!isset($lC_Addons)) $lC_Addons = new lC_Addons;
    $aoArr = $lC_Addons->getAddons('enabled');
         
    return $aoArr['Loaded_7_B2B']['version'];
  }  
 /**
  * Detect browser type
  *  
  * @access public      
  * @return boolean
  */ 
  public static function detectBrowser() { 
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']); 
    if ((substr($_SERVER['HTTP_USER_AGENT'],0,6)=="Opera/") || (strpos($userAgent,'opera')) != false ){ 
      $name = 'opera';
    } elseif ((strpos($userAgent,'chrome')) != false) { 
      $name = 'chrome'; 
    } elseif ((strpos($userAgent,'safari')) != false && (strpos($userAgent,'chrome')) == false && (strpos($userAgent,'chrome')) == false){ 
      $name = 'safari'; 
    } elseif (preg_match('/msie/', $userAgent) || preg_match('/trident/', $userAgent)) { 
      $name = 'msie'; 
    } elseif ((strpos($userAgent,'firefox')) != false) { 
      $name = 'firefox'; 
    } else { 
      $name = 'unrecognized'; 
    } 
    if (preg_match('/.+(?:me|ox|it|ra|ie)[\/: ]([\d.]+)/', $userAgent, $matches) && $browser['name'] == 'safari' ) { 
      $version = $matches[1]; 
    }
    if (preg_match('/.+(?:me|ox|it|on|ra|ie)[\/: ]([\d.]+)/', $userAgent, $matches) && $browser['name'] != 'safari' ) { 
      $version = $matches[1]; 
    }
    else { 
      $version = 'unknown'; 
    } 

    return array('name' => $name, 'version' => $version); 
  } 
 /**
  * Recursively remove files and folders
  *  
  * @param string  $path  The path to remove
  * @access public      
  * @return boolean
  */ 
  public static function rmdir_r($path) {
    $i = new DirectoryIterator($path);
    foreach($i as $f) {
      if($f->isFile()) {
        @unlink($f->getRealPath());
      } else if(!$f->isDot() && $f->isDir()) {
        self::rmdir_r($f->getRealPath());
        @rmdir($f->getRealPath());
      }
    }
    @rmdir($path);
    
    return true;
  }  
 /**
  * Recursively set permissions on files and folders
  *  
  * @param string  $path     The path to start
  * @param integer $filePerm Permiaaion for files   
  * @param integer $dirPerm  Permission for directories
  * @access public      
  * @return boolean
  */
  public static function chmod_r($path, $filePerm = 0644, $dirPerm = 0755) {
    // Check if the path exists
    if (!file_exists($path)) {
      return(false);
    }

    if (is_file($path)) {
      @chmod($path, $filePerm);
    } elseif (is_dir($path)) {
      $foldersAndFiles = @scandir($path);
      $entries = @array_slice($foldersAndFiles, 2);
      foreach ($entries as $entry) {
        self::chmod_r($path."/".$entry, $filePerm, $dirPerm);
      }
      @chmod($path, $dirPerm);
    }

    return true;
  } 
 /**
  * Recursively create directory
  *  
  * @param string  $path  The path to remove
  * @access public      
  * @return boolean
  */
  public static function mkdir_r($path) {
    
    if (!self::execEnabled()) return false;
    
    $parts = explode('/', $path);
    $check = '';
    foreach ($parts as $val) {
      $check .= $val . '/';
      if (!is_dir($check)) exec('mkdir ' . $check);
    }
    
    return true;
  }  
 /**
  * Create a ZIP archive using PHP ZipArchive method -or- exec() method
  *  
  * @param string $source       The source destination
  * @param string $destination  The target destination   
  * @param string $include_dir  Recursively include directories 
  * @access public      
  * @return boolean
  */
  public static function makeZip($source, $destination, $include_dir = true) {

    if (extension_loaded('zip') || !file_exists($source)) {

      if (file_exists($destination)) {
        unlink ($destination);
      }

      $zip = new ZipArchive();
      if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
      }
      $source = str_replace('\\', '/', realpath($source));

      if (is_dir($source) === true) {

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        if ($include_dir) {

          $arr = explode("/",$source);
          $maindir = $arr[count($arr)- 1];

          $source = "";
          for ($i=0; $i < count($arr) - 1; $i++) { 
            $source .= '/' . $arr[$i];
          }

          $source = substr($source, 1);

          $zip->addEmptyDir($maindir);
        }

        foreach ($files as $file) {
          $file = str_replace('\\', '/', $file);

          // Ignore "." and ".." folders
          if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ) continue;

          $file = realpath($file);

          if (is_dir($file) === true) {
            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
          } else if (is_file($file) === true) {
            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
          }
        }
      } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
      }

      return $zip->close();
    } else if (self::execEnabled()) {
      // use exec
      try {
        @exec('\cd ' . $source . '; '  . CFG_APP_ZIP . ' -r ' . $destination . ' ' . $source . ' -x \*.zip\*;');
      } catch ( Exception $e ) {  
        return false;
      }      
      return true;
    } else {
      return false;
    }
  }
 /**
  * Extract a ZIP archive using PHP ZipArchive method -or- exec() method
  *  
  * @param string $source       The source destination
  * @param string $destination  The target destination
  * @access public      
  * @return boolean
  */
  public static function extractZip($source, $destination) {
    
    if (extension_loaded('zip') || !file_exists($source)) {
    
      $zip = new ZipArchive;
      
      $status = false;
      if ($zip->open($source)) {
        if ($zip->extractTo($destination)) {
          $status = true;
        }
        $zip->close();
      }    

    } else if (self::execEnabled()) {
      // use exec
      try {
        @exec(CFG_APP_UNZIP . ' ' . $source . ' ' . $destination . ';');
      } catch ( Exception $e ) {  
        return false;
      }      
      return true;      
    } else {
      $status = false;
    }
    
    return $status;
  }
 /**
  * Get the ioncube loader version number
  *  
  * @access public      
  * @return array
  */
  public static function ioncube_loader_version_array() {
    if (function_exists('ioncube_loader_iversion') ) {
      $ioncube_loader_iversion = ioncube_loader_iversion();
      $ioncube_loader_version_major = (int)substr($ioncube_loader_iversion, 0, 1);
      $ioncube_loader_version_minor = (int)substr($ioncube_loader_iversion, 1, 2);
      $ioncube_loader_version_revision = (int)substr($ioncube_loader_iversion, 3, 2);
      $ioncube_loader_version = "$ioncube_loader_version_major.$ioncube_loader_version_minor.$ioncube_loader_version_revision";
    } else {
      $ioncube_loader_version = ioncube_loader_version();
      $ioncube_loader_version_major = (int)substr($ioncube_loader_version, 0, 1);
      $ioncube_loader_version_minor = (int)substr($ioncube_loader_version, 2, 1);
      $ioncube_loader_version_revision = (int)substr($ioncube_loader_iversion, 3, 2);
    }
    return array('version' => $ioncube_loader_version, 'major' => $ioncube_loader_version_major, 'minor' => $ioncube_loader_version_minor, 'revision' => $ioncube_loader_version_revision);
  }
 /**
  * Check the ioncube installed status and version number
  *  
  * @access public      
  * @return array
  */
  public static function ioncubeCheck() {
    global $lC_Language;
    
    $ioncubeStatus = '';
    
    if (extension_loaded('ionCube Loader')) {
      $ioncube_loader_version = self::ioncube_loader_version_array();
      if (($ioncube_loader_version['major'] <= 4 && $ioncube_loader_version['minor'] < 4 && $ioncube_loader_version['revision'] < 1)) {
        $ioncubeStatus = array('installed' => true,
                               'version' => $ioncube_loader_version['major'] . '.' . $ioncube_loader_version['minor'] . '.' . $ioncube_loader_version['revision'],
                               'valid' => false);
      } else {
        $ioncubeStatus = array('installed' => true,
                               'version' => $ioncube_loader_version['major'] . '.' . $ioncube_loader_version['minor'] . '.' . $ioncube_loader_version['revision'],
                               'valid' => true);
      }
    } else {
      $ioncubeStatus = array('installed' => false,
                             'version' => null,
                             'valid' => false);
    }
    
    return $ioncubeStatus;
  } 
} 
?>