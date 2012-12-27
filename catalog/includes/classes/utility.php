<?php
 /*
  $Id: utlity.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
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
  public static function xml2arr($_xml, $get_attributes = 1, $priority = 'tag', $clean = TRUE) {
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
} 
?>