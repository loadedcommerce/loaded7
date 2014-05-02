<?php
/**
  @package    catalog::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: general.php v1.0 2013-08-08 datazen $
*/

/**
* Redirect to a URL address
*
* @param string $url The URL address to redirect to
* @access public
*/
if (!function_exists('lc_redirect')) {
  function lc_redirect($url) {
    global $lC_Services;

    if ( ( strpos($url, "\n") !== false ) || ( strpos($url, "\r") !== false ) ) {
      $url = lc_href_link(FILENAME_DEFAULT, null, 'NONSSL', false);
    }

    if ( strpos($url, '&amp;') !== false ) {
      $url = str_replace('&amp;', '&', $url);
    }

    header('Location: ' . $url);

    if ( isset($lC_Services) && is_a($lC_Services, 'lC_Services') ) {
      $lC_Services->stopServices();
    }

    exit;
  }
}
/**
* Parse and output a user submited value
*
* @param string $string The string to parse and output
* @param array $translate An array containing the characters to parse
* @access public
*/
if (!function_exists('lc_output_string')) {
  function lc_output_string($string, $translate = null) {
    if (empty($translate)) {
      $translate = array('"' => '&quot;');
    }

    return strtr(trim($string), $translate);
  }
}
/**
* Strictly parse and output a user submited value
*
* @param string $string The string to strictly parse and output
* @access public
*/
if (!function_exists('lc_output_string_protected')) {
  function lc_output_string_protected($string) {
    return htmlspecialchars(trim($string));
  }
}
/**
* Sanitize a user submited value
*
* @param string $string The string to sanitize
* @access public
*/
if (!function_exists('lc_sanitize_string')) {
  function lc_sanitize_string($string) {
    $string = preg_replace("/ +/", ' ', trim($string));

    return preg_replace("/[<>]/", '_', $string);
  }
}
/**
* Get all parameters in the GET scope
*
* @param array $exclude A list of parameters to exclude
* @access public
*/
if (!function_exists('lc_get_all_get_params')) {
  function lc_get_all_get_params($exclude = null) {
    global $lC_Session;

    $params = '';

    $array = array($lC_Session->getName(),
      'error',
      'x',
      'y');

    if (is_array($exclude)) {
      foreach ($exclude as $key) {
        if (!in_array($key, $array)) {
          $array[] = $key;
        }
      }
    }

    if (isset($_GET) && !empty($_GET)) {
      foreach ($_GET as $key => $value) {
        if (!in_array($key, $array)) {
          $params .= $key . (!empty($value) ? '=' . $value : '') . '&';
        }
      }

      $params = substr($params, 0, -1);
    }

    return $params;
  }
}
/**
* Round a number with the wanted precision
*
* @param float $number The number to round
* @param int $precision The precision to use for the rounding
* @access public
*/
if (!function_exists('lc_round')) {
  function lc_round($number, $precision) {
    if ( (strpos($number, '.') !== false) && (strlen(substr($number, strpos($number, '.')+1)) > $precision) ) {
      $number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

      if (substr($number, -1) >= 5) {
        if ($precision > 1) {
          $number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
        } elseif ($precision == 1) {
          $number = substr($number, 0, -1) + 0.1;
        } else {
          $number = substr($number, 0, -1) + 1;
        }
      } else {
        $number = substr($number, 0, -1);
      }
    }

    return $number;
  }
}
/**
* Create a sort heading with appropriate sort link
*
* @param string $key The key used for sorting
* @param string $heading The heading to use the link on
* @access public
*/
if (!function_exists('lc_create_sort_heading')) {
  function lc_create_sort_heading($key, $heading) {
    global $lC_Language;

    $current = false;
    $direction = false;

    if (!isset($_GET['sort'])) {
      $current = 'name';
    } elseif (($_GET['sort'] == $key) || ($_GET['sort'] == $key . '|d')) {
      $current = $key;
    }

    if ($key == $current) {
      if (isset($_GET['sort'])) {
        $direction = ($_GET['sort'] == $key) ? '+' : '-';
      } else {
        $direction = '+';
      }
    }

    return lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('page', 'sort')) . '&sort=' . $key . ($direction == '+' ? '|d' : '')), $heading . (($key == $current) ? $direction : ''), 'title="' . (isset($_GET['sort']) && ($_GET['sort'] == $key) ? sprintf($lC_Language->get('listing_sort_ascendingly'), $heading) : sprintf($lC_Language->get('listing_sort_descendingly'), $heading)) . '" class="productListing-heading"');
  }
}
/**
* Generate a product ID string value containing its product attributes combinations
*
* @param string $id The product ID
* @param array $params An array of product attributes
* @access public
*/
if (!function_exists('lc_get_product_id_string')) {
  function lc_get_product_id_string($id, $params) {
    $string = (int)$id;

    if (is_array($params) && !empty($params)) {
      $attributes_check = true;
      $attributes_ids = array();

      foreach ($params as $option => $value) {
        if (is_numeric($option) && is_numeric($value)) {
          $attributes_ids[] = (int)$option . ':' . (int)$value;
        } else {
          $attributes_check = false;
          break;
        }
      }

      if ($attributes_check === true) {
        $string .= '#' . implode(';', $attributes_ids);
      }
    }

    return $string;
  }
}
/**
* Generate a numeric product ID without product attribute combinations
*
* @param string $id The product ID
* @access public
*/
if (!function_exists('lc_get_product_id')) {
  function lc_get_product_id($id) {
    if (is_numeric($id)) {
      return $id;
    }

    $product = explode('#', $id, 2);

    return (int)$product[0];
  }
}
/**
* Send an email
*
* @param string $to_name The name of the recipient
* @param string $to_email_address The email address of the recipient
* @param string $subject The subject of the email
* @param string $body The body text of the email
* @param string $from_name The name of the sender
* @param string $from_email_address The email address of the sender
* @access public
*/
if (!function_exists('lc_email')) {
  function lc_email($to_name, $to_email_address, $subject, $body, $from_name, $from_email_address) {
    if (SEND_EMAILS == '-1') {
      return false;
    }

    $lC_Mail = new lC_Mail($to_name, $to_email_address, $from_name, $from_email_address, $subject);
    if (EMAIL_USE_HTML == '1') {
      $lC_Mail->setBodyHTML(lc_nl2p($body));
    } else {
      $lC_Mail->setBodyPlain($body);
    }
    $lC_Mail->send();
  }
}
/**
* Create a random string
*
* @param int $length The length of the random string to create
* @param string $type The type of random string to create (mixed, chars, digits)
* @access public
*/
if (!function_exists('lc_create_random_string')) {
  function lc_create_random_string($length, $type = 'mixed') {
    if (!in_array($type, array('mixed', 'chars', 'digits'))) {
      return false;
    }

    $chars_pattern = 'abcdefghijklmnopqrstuvwxyz';
    $mixed_pattern = '1234567890' . $chars_pattern;

    $rand_value = '';

    while (strlen($rand_value) < $length) {
      if ($type == 'digits') {
        $rand_value .= lc_rand(0,9);
      } elseif ($type == 'chars') {
        $rand_value .= substr($chars_pattern, lc_rand(0, 25), 1);
      } else {
        $rand_value .= substr($mixed_pattern, lc_rand(0, 35), 1);
      }
    }

    return $rand_value;
  }
}
/**
* Alias function for empty()
*
* @param mixed $value The object to check if it is empty or not
* @access public
*/
if (!function_exists('lc_empty')) {
  function lc_empty($value) {
    return empty($value);
  }
}
/**
* Generate a random number
*
* @param int $min The minimum number to return
* @param int $max The maxmimum number to return
* @access public
*/
if (!function_exists('lc_rand')) {
  function lc_rand($min = null, $max = null) {
    static $seeded;

    if (!isset($seeded)) {
      if (version_compare(PHP_VERSION, '4.2', '<')) {
        mt_srand((double)microtime()*1000000);
      }

      $seeded = true;
    }

    if (is_numeric($min) && is_numeric($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }
}
/**
* Set a cookie
*
* @param string $name The name of the cookie
* @param string $value The value of the cookie
* @param int $expire Unix timestamp of when the cookie should expire
* @param string $path The path on the server for which the cookie will be available on
* @param string $domain The The domain that the cookie is available on
* @param boolean $secure Indicates whether the cookie should only be sent over a secure HTTPS connection
* @param boolean $httpOnly Indicates whether the cookie should only accessible over the HTTP protocol
* @access public
*/
if (!function_exists('lc_setcookie')) {
  function lc_setcookie($name, $value = null, $expires = 0, $path = null, $domain = null, $secure = false, $httpOnly = false) {
    global $request_type;

    if (empty($path)) {
      $path = ($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH;
    }

    if (empty($domain)) {
      $domain = ($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN;
    }

    header('Set-Cookie: ' . $name . '=' . urlencode($value) . '; expires=' . @date('D, d-M-Y H:i:s T', $expires) . '; path=' . $path . '; domain=' . $domain . (($secure === true) ? ' secure;' : '') . (($httpOnly === true) ? ' httponly;' : ''));
  }
}
/**
* Get the IP address of the client
*
* @access public
*/
if (!function_exists('lc_get_ip_address')) {
  function lc_get_ip_address() {
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }
$ip = "192.168.1.140";
    return $ip;
  }
}
/**
* Encrypt a string
*
* @param string $plain The string to encrypt
* @access public
*/
if (!function_exists('lc_encrypt_string')) {
  function lc_encrypt_string($plain) {
    $password = '';
    for ($i=0; $i<10; $i++) {
      $password .= lc_rand();
    }
    $salt = substr(hash('sha256', $password), 0, 2);
    $password = hash('sha256', $salt . $plain) . '::' . $salt;

    return $password;
  }
}
/**
* Validates the format of an email address
*
* @param string $email_address The email address to validate
* @access public
*/
if (!function_exists('lc_validate_email_address')) {
  function lc_validate_email_address($email_address) {
    $valid_address = true;

    $mail_pat = '/^(.+)@(.+)$/i';
    $valid_chars = "[^] \(\)<>@,;:\.\\\"\[]";
    $atom = "$valid_chars+";
    $quoted_user='(\"[^\"]*\")';
    $word = "($atom|$quoted_user)";
    $user_pat = "/^$word(\.$word)*$/i";
    $ip_domain_pat='/^\[([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\]$/i';
    $domain_pat = "/^$atom(\.$atom)*$/i";

    if (preg_match($mail_pat, $email_address, $components)) {
      $user = $components[1];
      $domain = $components[2];
      // validate user
      if (preg_match($user_pat, $user)) {
        // validate domain
        if (preg_match($ip_domain_pat, $domain, $ip_components)) {
          // this is an IP address
          for ($i=1;$i<=4;$i++) {
            if ($ip_components[$i] > 255) {
              $valid_address = false;
              break;
            }
          }
        } else {
          // Domain is a name, not an IP
          if (preg_match($domain_pat, $domain)) {
            // domain name seems valid, but now make sure that it ends in a valid TLD or ccTLD and that there's a hostname preceding the domain or country.
            $domain_components = explode(".", $domain);
            // Make sure there's a host name preceding the domain.
            if (sizeof($domain_components) < 2) {
              $valid_address = false;
            } else {
              $top_level_domain = strtolower($domain_components[sizeof($domain_components)-1]);
              // Allow all 2-letter TLDs (ccTLDs)
              if (preg_match('/^[a-z][a-z]$/i', $top_level_domain) != 1) {
                $tld_pattern = '';
                // Get authorized TLDs from text file
                $tlds = file(DIR_FS_CATALOG . 'includes/tld.txt');
                while (list(,$line) = each($tlds)) {
                  // Get rid of comments
                  $words = explode('#', $line);
                  $tld = trim($words[0]);
                  // TLDs should be 3 letters or more
                  if (preg_match('/^[a-z]{3,}$/i', $tld) == 1) {
                    $tld_pattern .= '^' . $tld . '$|';
                  }
                }
                // Remove last '|'
                $tld_pattern = substr($tld_pattern, 0, -1);
                if (preg_match("/$tld_pattern/i", $top_level_domain) == 0) {
                  $valid_address = false;
                }
              }
            }
          } else {
            $valid_address = false;
          }
        }
      } else {
        $valid_address = false;
      }
    } else {
      $valid_address = false;
    }

    if ($valid_address && ENTRY_EMAIL_ADDRESS_CHECK == '1') {
      if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
        $valid_address = false;
      }
    }

    return $valid_address;
  }
}
/**
* Sets the defined locale
*
* @param string $category The category of the locale to set
* @param mixed $locale The locale, or an array of locales to try and set
* @access public
*/
if (!function_exists('lc_setlocale')) {
  function lc_setlocale($category, $locale) {
    if (version_compare(PHP_VERSION, '4.3', '<')) {
      if (is_array($locale)) {
        foreach ($locale as $l) {
          if (($result = setlocale($category, $l)) !== false) {
            return $result;
          }
        }

        return false;
      } else {
        return setlocale($category, $locale);
      }
    } else {
      return setlocale($category, $locale);
    }
  }
}
/**
* Return the customer groups array
*
* @access  public
* @return  array
*/
if (!function_exists('lc_get_customer_groups_array')) {
  function lc_get_customer_groups_array() {
    global $lC_Database, $lC_Language;

    $Qgroups = $lC_Database->query('select customers_group_id, customers_group_name from :table_customers_groups where language_id = :language_id');
    $Qgroups->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
    $Qgroups->bindInt(':language_id', $lC_Language->getID());      
    $Qgroups->execute();

    $groups_array = array();
    while ( $Qgroups->next() ) {
      $groups_array[] = array('id' => $Qgroups->valueInt('customers_group_id'), 'text' => $Qgroups->value('customers_group_name'));      
    }
    $Qgroups->freeResult();

    return $groups_array;
  }
}
/**
* Return the customer groups name
*
* @param   integer $id The customer group id
* @access  public
* @return  string;
*/
if (!function_exists('lc_get_customer_groups_name')) { 
  function lc_get_customer_groups_name($id) {
    global $lC_Database, $lC_Language; 

    $Qgroups = $lC_Database->query('select customers_group_name from :table_customers_groups where customers_group_id = :customers_group_id and language_id = :language_id');
    $Qgroups->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
    $Qgroups->bindInt(':customers_group_id', $id); 
    $Qgroups->bindInt(':language_id', $lC_Language->getID());      
    $Qgroups->execute();

    $group_name = $Qgroups->value('customers_group_name');      

    $Qgroups->freeResult();

    return $group_name;
  } 
}
/**
* Clean the HTML
*
* @param   string  $html The HTML to clean
* @access  public
* @return  string;
*/
if (!function_exists('lc_clean_html')) {
  function lc_clean_html($html){
    $search = array('@<script[^>]*?>.*?</script>@si', 
      '@<[\/\!]*?[^<>]*?>@si',
      '@<style[^>]*?>.*?</style>@siU',
      '@<![\s\S]*?--[ \t\n\r]*>@'
    );
    return preg_replace($search, '', $html);
  }   
}

if (!function_exists('lc_get_country_data')) {
  function lc_get_country_data($countries_id = null, $countries_name = null, $countries_iso2 = null, $countries_iso3 = null) {
    global $lC_Database; 

    if ($countries_id == null && $country_name == null && $country_iso2 == null && $country_iso3 == null) return false;

    if ($countries_id != null) {
      $Qcountry = $lC_Database->query('select * from :table_countries where countries_id = :countries_id limit 1');
      $Qcountry->bindInt(':countries_id', $countries_id);
    } else if ($countries_name != null) {
      $Qcountry = $lC_Database->query('select * from :table_countries where countries_name = :countries_name limit 1');
      $Qcountry->bindInt(':countries_name', $countries_name);
    } else if ($countries_iso2 != null) {    
      $Qcountry = $lC_Database->query('select * from :table_countries where countries_iso_code_2 = :countries_iso2 limit 1');
      $Qcountry->bindInt(':countries_iso_code_2', $countries_iso2);
    } else if ($countries_iso3 != null) {    
      $Qcountry = $lC_Database->query('select * from :table_countries where countries_iso_code_3 = :countries_iso3 limit 1');
      $Qcountry->bindInt(':countries_iso_code_3', $countries_iso3);
    }
    $Qcountry->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qcountry->execute();

    $data = $Qcountry->toArray();
    
    $Qcountry->freeResult();

    return $data;
  }
}

/**
 * Returns string with newline formatting converted into HTML paragraphs.
 *
 * @author Michael Tomasello <miketomasello@gmail.com>
 * @copyright Copyright (c) 2007, Michael Tomasello
 * @license http://www.opensource.org/licenses/bsd-license.html BSD License
 * 
 * @param string $string String to be formatted.
 * @param boolean $line_breaks When true, single-line line-breaks will be converted to HTML break tags.
 * @param boolean $xml When true, an XML self-closing tag will be applied to break tags (<br />).
 * @return string
 */
 
 if (!function_exists('lc_nl2p')) {
   function lc_nl2p($string, $line_breaks = true, $xml = true){
     // Remove existing HTML formatting to avoid double-wrapping things
     $string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);
     
     // It is conceivable that people might still want single line-breaks
     // without breaking into a new paragraph.
     if ($line_breaks == true){
       return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '<br'.($xml == true ? ' /' : '').'>'), trim($string)).'</p>';
     } else {
       return '<p>'.preg_replace("/([\n]{1,})/i", "</p>\n<p>", trim($string)).'</p>';
     }
   }
 }
?>