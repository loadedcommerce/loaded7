<?php
/*
  $Id: html_output.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/ 

/**
* Generate an internal URL address for the catalog side
*
* @param string $page The page to link to
* @param string $parameters The parameters to pass to the page (in the GET scope)
* @param string $connection The connection type for the page (NONSSL, SSL, AUTO)
* @param boolean $add_session_id Conditionally add the session ID to the URL
* @param boolean $search_engine_friendly Convert the URL to be search engine friendly
* @param boolean $use_full_address Add the full server address to the URL
* @access public
*/
if (!function_exists('lc_href_link')) {
  function lc_href_link($page = null, $parameters = null, $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true, $use_full_address = false) {
    global $request_type, $lC_Session, $lC_Services, $lC_CategoryTree, $lC_Products;

    if (!in_array($connection, array('NONSSL', 'SSL', 'AUTO'))) {
      $connection = 'NONSSL';
    }

    if (!is_bool($add_session_id)) {
      $add_session_id = true;
    }

    if (!is_bool($search_engine_safe)) {
      $search_engine_safe = true;
    }

    if (!is_bool($use_full_address)) {
      $use_full_address = false;
    }

    if (($search_engine_safe === true) && ($use_full_address === false) && isset($lC_Services) && $lC_Services->isStarted('seo')) {
      $use_full_address = true;
    }

    if ($connection == 'AUTO') {
      if ( ($request_type == 'SSL') && (ENABLE_SSL === true) ) {
        $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } elseif ( ($connection == 'SSL') && (ENABLE_SSL === true) ) {
      if ($request_type == 'SSL') {
        $link = ($use_full_address === false) ? '' : HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
      } else {
        $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
      }
    } else {
      if ($request_type == 'NONSSL') {
        $link = ($use_full_address === false) ? '' : HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    }

    $link .= $page;

    if (!empty($parameters)) {
      $link .= '?' . lc_output_string($parameters);
      $separator = '&';
    } else {
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) {
      $link = substr($link, 0, -1);
    }

    // Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id === true) && $lC_Session->hasStarted() && (SERVICE_SESSION_FORCE_COOKIE_USAGE == '-1') ) {
      if (!lc_empty(SID)) {
        $_sid = SID;
      } elseif ( (($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL === true)) || (($request_type == 'SSL') && ($connection != 'SSL')) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = $lC_Session->getName() . '=' . $lC_Session->getID();
        }
      }
    }

    if (isset($_sid)) {
      $link .= $separator . lc_output_string($_sid);
    }

    while (strstr($link, '&&')) {
      $link = str_replace('&&', '&', $link);
    }

    if (!stristr($link, 'rpc.php')) {
      if ( ($search_engine_safe === true) && isset($lC_Services) && $lC_Services->isStarted('seo')) {
        $cat_path = '';
        if ( ($cPathPos = strpos($link, 'cPath')) || (strpos($link, 'products.php')) ) {
          if (defined('SERVICE_SEO_URL_ADD_CATEGORY_PARENT') && SERVICE_SEO_URL_ADD_CATEGORY_PARENT == 1) {
            // categories
            if ( (strpos($link, 'index.php') && strpos($link, 'cPath')) ) {
              $cat_id = explode("_", substr($link, $cPathPos+6));
              if (count($cat_id) < 2) {
                $cat_data = $lC_CategoryTree->getData($cat_id[0]);
                $cat_ids = explode("_", substr($cat_data['query'], 6));
              } else {
                $cat_ids = explode("_", substr($link, $cPathPos+6));
              }
            } 
            // products
            if ( (strpos($link, 'products.php') && !strpos($link, 'cart_add') && !strpos($link, 'reviews') && !strpos($link, '?specials') && !strpos($link, '?new')) ) {
              if (strpos($link, 'cPath')) {
                $cat_id = explode("_", substr($link, $cPathPos+6));
                if (count($cat_id) < 2) {
                  $cat_data = $lC_CategoryTree->getData($cat_id[0]);
                  $cat_ids = explode("_", substr($cat_data['query'], 6));
                }
              } else {
                $permalink = substr($link, strpos($link, 'products.php?')+13);
                if (!strpos($permalink, '/') && 
                    !strpos($permalink, '?') && 
                    !strpos($permalink, ',')) {
                    $pQuery = get_permalink_query($permalink);
                    $cat_ids = explode("_", substr($pQuery, 6));
                }
              }
            }            
            foreach ($cat_ids as $id => $value) {
              $cat_data = $lC_CategoryTree->getData($value);
              if ($cat_data['permalink'] != '') {
                $cat_path .= strtolower(str_replace(' ', '-', $cat_data['permalink'])) . '/'; 
              } else {
                $cat_path .= strtolower(str_replace(' ', '-', $cat_data['name'])) . '/';
              }
            }
          } else {
            if (!strpos($link, 'products.php')) {
              $cat_id = end(explode("_", substr($link, $cPathPos+6)));
              $cat_data = $lC_CategoryTree->getData($cat_id);
              if ($cat_data['permalink'] != '') {
                $cat_path .= strtolower(str_replace(' ', '-', $cat_data['permalink'])) . '/'; 
              } else {
                $cat_path .= strtolower(str_replace(' ', '-', $cat_data['name'])) . '/';
              }
            }
          }
          $link = str_replace(array('?', '&', '=', 'index.php', 'products.php'), array('/', '/', ',', 'category', 'product'), $link);
          $link = str_replace(array('category/', 'product/'), array('category/' . $cat_path, 'product/' . $cat_path), $link);
          $link = str_replace(array('product//'), array('product/'), $link);
          $link = preg_replace('/cPath,.*/', '', $link);
          $link = preg_replace('{/$}', '', $link);
        } else {
          if ( (strpos($link, 'products.php') && !strpos($link, '&')) || (strpos($link, 'cPath=') && !strpos($link, '&')) ) {
            $link = str_replace(array('?', '&', '=', 'products.php'), array('/', '/', ',', 'product'), $link);
          } else {
            $link = str_replace(array('?', '&', '='), array('/', '/', ','), $link);
          }
        }
      } else {
        if (strpos($link, '&') !== false) {
          $link = str_replace('&', '&amp;', $link);
        }
      }
    }
    return $link;
  }
}
/**
* Links an object with a URL address
*
* @param string $link The URL address to link the object to
* @param string $object The object to set the link on
* @param string $parameters Additional parameters for the link
* @access public
*/
if (!function_exists('lc_link_object')) {
  function lc_link_object($link, $object, $parameters = null) {
    return '<a href="' . $link . '"' . (!empty($parameters) ? ' ' . $parameters : '') . '>' . $object . '</a>';
  }
}
/**
* Outputs an image
*
* @param string $image The image filename to display
* @param string $title The title of the image button
* @param int $width The width of the image
* @param int $height The height of the image
* @param string $parameters Additional parameters for the image
* @access public
*/
if (!function_exists('lc_image')) {
  function lc_image($image, $title = null, $width = 0, $height = 0, $parameters = null) {
    if ( (empty($image) || ($image == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == '-1') ) {
      return false;
    }

    if (!is_numeric($width)) {
      $width = 0;
    }

    if (!is_numeric($height)) {
      $height = 0;
    }

    $image = '<img src="' . lc_output_string($image) . '" border="0" alt="' . lc_output_string($title) . '"';

    if (!empty($title)) {
      $image .= ' title="' . lc_output_string($title) . '"';
    }

    if ($width > 0) {
      $image .= ' width="' . (int)$width . '"';
    }

    if ($height > 0) {
      $image .= ' height="' . (int)$height . '"';
    }

    if (!empty($parameters)) {
      $image .= ' ' . $parameters;
    }

    $image .= ' />';

    return $image;
  }
}
/**
* Outputs an image submit button
*
* @param string $image The image filename to display
* @param string $title The title of the image button
* @param string $parameters Additional parameters for the image submit button
* @access public
*/
if (!function_exists('lc_draw_image_submit_button')) {
  function lc_draw_image_submit_button($image, $title = null, $parameters = null) {
    global $lC_Language;

    $image_submit = '<input type="image" src="' . lc_output_string(DIR_WS_TEMPLATE . 'includes/languages/' . $lC_Language->getCode() . '/images/buttons/' . $image) . '"';

    if (!empty($title)) {
      $image_submit .= ' alt="' . lc_output_string($title) . '" title="' . lc_output_string($title) . '"';
    }

    if (!empty($parameters)) {
      $image_submit .= ' ' . $parameters;
    }

    $image_submit .= ' />';

    return $image_submit;
  }
}
/**
* Outputs an image button
*
* @param string $image The image filename to display
* @param string $title The title of the image button
* @param string $parameters Additional parameters for the image button
* @access public
*/
if (!function_exists('lc_draw_image_button')) {
  function lc_draw_image_button($image, $title = null, $parameters = null) {
    global $lC_Language;

    return lc_image(DIR_WS_TEMPLATE . 'includes/languages/' . $lC_Language->getCode() . '/images/buttons/' . $image, $title, null, null, $parameters);
  }
}
/**
* Outputs a form input field (text/password)
*
* @param string $name The name and ID of the input field
* @param string $value The default value for the input field
* @param string $parameters Additional parameters for the input field
* @param boolean $override Override the default value with the value found in the GET or POST scope
* @param string $type The type of input field to use (text/password/file)
* @access public
*/
if (!function_exists('lc_draw_input_field')) {
  function lc_draw_input_field($name, $value = null, $parameters = null, $override = true, $type = 'text') {
    if (!is_bool($override)) {
      $override = true;
    }

    if ($override === true) {
      if ( strpos($name, '[') !== false ) {
        $name_string = substr($name, 0, strpos($name, '['));
        $name_key = substr($name, strpos($name, '[') + 1, strlen($name) - (strpos($name, '[') + 2));

        if ( isset($_GET[$name_string][$name_key]) ) {
          $value = $_GET[$name_string][$name_key];
        } elseif ( isset($_POST[$name_string][$name_key]) ) {
          $value = $_POST[$name_string][$name_key];
        }
      } else {
        if ( isset($_GET[$name]) ) {
          $value = $_GET[$name];
        } elseif ( isset($_POST[$name]) ) {
          $value = $_POST[$name];
        }
      }
    }

    if (!in_array($type, array('text', 'password', 'file'))) {
      $type = 'text';
    }

    $field = '<input type="' . lc_output_string($type) . '" name="' . lc_output_string($name) . '"';

    if (strpos($parameters, 'id=') === false) {
      $field .= ' id="' . lc_output_string($name) . '"';
    }

    if (trim($value) != '') {
      $field .= ' value="' . lc_output_string($value) . '"';
    }

    if (!empty($parameters)) {
      $field .= ' ' . $parameters;
    }

    $field .= ' />';

    return $field;
  }
}
/**
* Outputs a form password field
*
* @param string $name The name and ID of the password field
* @param string $parameters Additional parameters for the password field
* @access public
*/
if (!function_exists('lc_draw_password_field')) {
  function lc_draw_password_field($name, $parameters = null, $default = null) {
    return lc_draw_input_field($name, $default, $parameters, false, 'password');
  }
}
/**
* Outputs a form file upload field
*
* @param string $name The name and ID of the file upload field
* @param boolean $show_max_size Show the maximum file upload size beside the field
* @access public
*/
if (!function_exists('lc_draw_file_field')) {
  function lc_draw_file_field($name, $show_max_size = false, $params = 'size="32"') {
    global $lC_Language;

    static $upload_max_filesize;

    if (!is_bool($show_max_size)) {
      $show_max_size = false;
    }

    $field = lc_draw_input_field($name, null, $params, false, 'file');

    if ($show_max_size === true) {
      if (!isset($upload_max_filesize)) {
        $upload_max_filesize = @ini_get('upload_max_filesize');
      }

      if (!empty($upload_max_filesize)) {
        $field .= '&nbsp;' . sprintf($lC_Language->get('maximum_file_upload_size'), lc_output_string($upload_max_filesize));
      }
    }

    return $field;
  }
}
/**
* Outputs a form selection field (checkbox/radio)
*
* @param string $name The name and indexed ID of the selection field
* @param string $type The type of the selection field (checkbox/radio)
* @param mixed $values The value of, or an array of values for, the selection field
* @param string $default The default value for the selection field
* @param string $parameters Additional parameters for the selection field
* @param string $separator The separator to use between multiple options for the selection field
* @access public
*/
if (!function_exists('lc_draw_selection_field')) {
  function lc_draw_selection_field($name, $type, $values, $default = null, $parameters = null, $separator = '&nbsp;&nbsp;') {
    if (!is_array($values)) {
      $values = array($values);
    }

    if ( strpos($name, '[') !== false ) {
      $name_string = substr($name, 0, strpos($name, '['));

      if ( isset($_GET[$name_string]) ) {
        $default = $_GET[$name_string];
      } elseif ( isset($_POST[$name_string]) ) {
        $default = $_POST[$name_string];
      }
    } else {
      if ( isset($_GET[$name]) ) {
        $default = $_GET[$name];
      } elseif ( isset($_POST[$name]) ) {
        $default = $_POST[$name];
      }
    }

    $field = '';

    $counter = 0;

    foreach ($values as $key => $value) {
      $counter++;

      if (is_array($value)) {
        $selection_value = $value['id'];
        $selection_text = '&nbsp;' . $value['text'];
      } else {
        $selection_value = $value;
        $selection_text = '';
      }

      if (strstr($separator, '&nbsp;')) {
        $field .= '<span style="display:inline-block"><label for="' . lc_output_string($name) . (sizeof($values) > 1 ? $counter : '') . '" class="fieldLabel"><input type="' . lc_output_string($type) . '" name="' . lc_output_string($name) . '"';
      } else {
        $field .= '<span><input type="' . lc_output_string($type) . '" name="' . lc_output_string($name) . '"';

      }

      if (strpos($parameters, 'id=') === false) {
        $field .= ' id="' . lc_output_string($name) . (sizeof($values) > 1 ? '_' . $counter : '') . '"';
      } elseif (sizeof($values) > 1) {
        $offset = strpos($parameters, 'id="');
        $field .= ' id="' . lc_output_string(substr($parameters, $offset+4, strpos($parameters, '"', $offset+4)-($offset+4))) . '_' . $counter . '"';
      }

      if (trim($selection_value) != '') {
        $field .= ' value="' . lc_output_string($selection_value) . '"';
      }

      if ((is_bool($default) && $default === true) || ((is_string($default) && (trim($default) == trim($selection_value))) || (is_array($default) && in_array(trim($selection_value), $default)))) {
        $field .= ' checked="checked"';
      }

      if (!empty($parameters)) {
        $field .= ' ' . $parameters;
      }

      $field .= ' />';

      if (!empty($selection_text)) {
        if (strstr($separator, '&nbsp;')) {
          $field .= $selection_text . '</label></span>';
        } else {
          $field .= '<label for="' . lc_output_string($name) . (sizeof($values) > 1 ? $counter : '') . '" class="fieldLabel">' . $selection_text . '</label></span>';
        }
      }

      $field .= $separator;
    }

    if (!empty($field)) {
      $field = substr($field, 0, strlen($field)-strlen($separator));
    }

    return $field;
  }
}
/**
* Outputs a form checkbox field
*
* @param string $name The name and indexed ID of the checkbox field
* @param mixed $values The value of, or an array of values for, the checkbox field
* @param string $default The default value for the checkbox field
* @param string $parameters Additional parameters for the checkbox field
* @param string $separator The separator to use between multiple options for the checkbox field
* @access public
*/
if (!function_exists('lc_draw_checkbox_field')) {
  function lc_draw_checkbox_field($name, $values = null, $default = null, $parameters = null, $separator = '&nbsp;&nbsp;') {
    return lc_draw_selection_field($name, 'checkbox', $values, $default, $parameters, $separator);
  }
}
/**
* Outputs a form radio field
*
* @param string $name The name and indexed ID of the radio field
* @param mixed $values The value of, or an array of values for, the radio field
* @param string $default The default value for the radio field
* @param string $parameters Additional parameters for the radio field
* @param string $separator The separator to use between multiple options for the radio field
* @access public
*/
if (!function_exists('lc_draw_radio_field')) {
  function lc_draw_radio_field($name, $values, $default = null, $parameters = null, $separator = '&nbsp;&nbsp;') {
    return lc_draw_selection_field($name, 'radio', $values, $default, $parameters, $separator);
  }
}
/**
* Outputs a form textarea field
*
* @param string $name The name and ID of the textarea field
* @param string $value The default value for the textarea field
* @param int $width The width of the textarea field
* @param int $height The height of the textarea field
* @param string $parameters Additional parameters for the textarea field
* @param boolean $override Override the default value with the value found in the GET or POST scope
* @access public
*/
if (!function_exists('lc_draw_textarea_field')) {
  function lc_draw_textarea_field($name, $value = null, $width = 60, $height = 5, $parameters = null, $override = true) {
    if (!is_bool($override)) {
      $override = true;
    }

    if ($override === true) {
      if (isset($_GET[$name])) {
        $value = $_GET[$name];
      } elseif (isset($_POST[$name])) {
        $value = $_POST[$name];
      }
    }

    if (!is_numeric($width)) {
      $width = 60;
    }

    if (!is_numeric($height)) {
      $width = 5;
    }

    $field = '<textarea name="' . lc_output_string($name) . '" cols="' . (int)$width . '" rows="' . (int)$height . '"';

    if (strpos($parameters, 'id=') === false) {
      $field .= ' id="' . lc_output_string($name) . '"';
    }

    if (!empty($parameters)) {
      $field .= ' ' . $parameters;
    }

    $field .= '>' . lc_output_string_protected($value) . '</textarea>';

    return $field;
  }
}
/**
* Outputs a form hidden field
*
* @param string $name The name of the hidden field
* @param string $value The value for the hidden field
* @param string $parameters Additional parameters for the hidden field
* @access public
*/
if (!function_exists('lc_draw_hidden_field')) {
  function lc_draw_hidden_field($name, $value = null, $parameters = null) {
    $field = '<input type="hidden" name="' . lc_output_string($name) . '"';

    if (trim($value) != '') {
      $field .= ' value="' . lc_output_string($value) . '"';
    }

    if (!empty($parameters)) {
      $field .= ' ' . $parameters;
    }

    $field .= ' />';

    return $field;
  }
}
/**
* Outputs a form hidden field containing the session name and ID if SID is not empty
*
* @access public
*/
if (!function_exists('lc_draw_hidden_session_id_field')) {
  function lc_draw_hidden_session_id_field() {
    global $lC_Session;

    if ($lC_Session->hasStarted() && !lc_empty(SID)) {
      return lc_draw_hidden_field($lC_Session->getName(), $lC_Session->getID());
    }
  }
}
/**
* Outputs a form pull down menu field
*
* @param string $name The name of the pull down menu field
* @param array $values Defined values for the pull down menu field
* @param string $default The default value for the pull down menu field
* @param string $parameters Additional parameters for the pull down menu field
* @access public
*/
if (!function_exists('lc_draw_pull_down_menu')) {
  function lc_draw_pull_down_menu($name, $values, $default = null, $parameters = null) {
    $group = false;

    if (isset($_GET[$name])) {
      $default = $_GET[$name];
    } elseif (isset($_POST[$name])) {
      $default = $_POST[$name];
    }

    $field = '<select name="' . lc_output_string($name) . '"';

    if (strpos($parameters, 'id=') === false) {
      $field .= ' id="' . lc_output_string($name) . '"';
    }

    if (!empty($parameters)) {
      $field .= ' ' . $parameters;
    }

    $field .= '>';

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      if (isset($values[$i]['group'])) {
        if ($group != $values[$i]['group']) {
          $group = $values[$i]['group'];

          $field .= '<optgroup label="' . lc_output_string($values[$i]['group']) . '">';
        }
      }

      $field .= '<option value="' . lc_output_string($values[$i]['id']) . '"';

      if ( (!is_null($default) && !is_array($default) && ((string)$default == (string)$values[$i]['id'])) || (is_array($default) && in_array($values[$i]['id'], $default)) ) {
        $field .= ' selected="selected"';
      }

      $field .= '>' . lc_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';

      if ( ($group !== false) && (($group != $values[$i]['group']) || !isset($values[$i+1])) ) {
        $group = false;

        $field .= '</optgroup>';
      }
    }

    $field .= '</select>';

    return $field;
  }
}
/**
* Outputs a label for form field elements
*
* @param string $text The text to use as the form field label
* @param string $for The ID of the form field element to assign the label to
* @param string $access_key The access key to use for the form field element
* @param bool $required A flag to show if the form field element requires input or not
* @access public
*/
if (!function_exists('lc_draw_label')) {
  function lc_draw_label($text, $for, $access_key = null, $required = false) {
    if (!is_bool($required)) {
      $required = false;
    }

    return '<label for="' . lc_output_string($for) . '"' . (!empty($access_key) ? ' accesskey="' . lc_output_string($access_key) . '"' : '') . '>' . lc_output_string($text) . ($required === true ? '<em>*</em>' : '') . '</label>';
  }
}
/**
* Outputs a form pull down menu for a date selection
*
* @param string $name The base name of the date pull down menu fields
* @param array $value An array containing the year, month, and date values for the default date (year, month, date)
* @param boolean $default_today Default to todays date if no default value is used
* @param boolean $show_days Show the days in a pull down menu
* @param boolean $use_month_names Show the month names in the month pull down menu
* @param int $year_range_start The start of the years range to use for the year pull down menu
* @param int $year_range_end The end of the years range to use for the year pull down menu
* @access public
*/
if (!function_exists('lc_draw_date_pull_down_menu')) {
  function lc_draw_date_pull_down_menu($name, $value = null, $default_today = true, $show_days = true, $use_month_names = true, $year_range_start = 0, $year_range_end = 1) {
    $year = @date('Y');

    if (!is_bool($default_today)) {
      $default_today = true;
    }

    if (!is_bool($show_days)) {
      $show_days = true;
    }

    if (!is_bool($use_month_names)) {
      $use_month_names = true;
    }

    if (!is_numeric($year_range_start)) {
      $year_range_start = 0;
    }

    if (!is_numeric($year_range_end)) {
      $year_range_end = 1;
    }

    if (!is_array($value)) {
      $value = array();
    }

    if (!isset($value['year']) || !is_numeric($value['year']) || ($value['year'] < ($year - $year_range_start)) || ($value['year'] > ($year + $year_range_end))) {
      if ($default_today === true) {
        $value['year'] = $year;
      } else {
        $value['year'] = $year - $year_range_start;
      }
    }

    if (!isset($value['month']) || !is_numeric($value['month']) || ($value['month'] < 1) || ($value['month'] > 12)) {
      if ($default_today === true) {
        $value['month'] = @date('n');
      } else {
        $value['month'] = 1;
      }
    }

    if (!isset($value['date']) || !is_numeric($value['date']) || ($value['date'] < 1) || ($value['date'] > 31)) {
      if ($default_today === true) {
        $value['date'] = @date('j');
      } else {
        $value['date'] = 1;
      }
    }

    $params = '';

    $days_select_string = '';

    if ($show_days === true) {
      $params = 'onchange="updateDatePullDownMenu(this.form, \'' . $name . '\');"';

      $days_in_month = ($default_today === true) ? @date('t') : 31;

      $days_array = array();
      for ($i=1; $i<=$days_in_month; $i++) {
        $days_array[] = array('id' => $i,
          'text' => $i);
      }

      $days_select_string = lc_draw_pull_down_menu($name . '_days', $days_array, $value['date']);
    }

    $months_array = array();
    for ($i=1; $i<=12; $i++) {
      $months_array[] = array('id' => $i,
        'text' => (($use_month_names === true) ? @strftime('%B', @mktime(0, 0, 0, $i, 1)) : $i));
    }

    $months_select_string = lc_draw_pull_down_menu($name . '_months', $months_array, $value['month'], $params);

    $years_array = array();
    for ($i = ($year - $year_range_start); $i <= ($year + $year_range_end); $i++) {
      $years_array[] = array('id' => $i,
        'text' => $i);
    }

    $years_select_string = lc_draw_pull_down_menu($name . '_years', $years_array, $value['year'], $params);

    return $days_select_string . $months_select_string . $years_select_string;
  }
}
/**
* Display an icon from a template set
*
* @param string $image The icon to display
* @param string $title The title of the icon
* @param string $group The size group of the icon
* @param string $parameters The parameters to pass to the image
* @access public
*/
if (!function_exists('lc_icon')) {
  function lc_icon($image, $title = null, $group = null, $parameters = null, $default = true) {
    global $lC_Language;

    if ($image == null) return false;

    if ( is_null($title) ) {
      $title = $lC_Language->get('icon_' . substr($image, 0, strpos($image, '.')));
    }

    if ( is_null($group) ) {
      $group = '16';
    }

    $template_code = (isset($_SESSION['template']['code'])) ? $_SESSION['template']['code'] : $lC_Template->getCode();

    return lc_image('templates/' . $template_code . '/images/icons/' . (!empty($group) ? $group . '/' : null) . $image, $title, null, null, $parameters);
  }
}
/**
* Get the raw URL to an icon from a template set
*
* @param string $image The icon to display
* @param string $group The size group of the icon
* @access public
*/
if (!function_exists('lc_icon_raw')) {
  function lc_icon_raw($image, $group = '16') {
    global $lC_Template;

    return 'templates/' . $lC_Template->getCode() . '/images/icons/' . (!empty($group) ? $group . '/' : null) . $image;
  }
}
/**
* Return a utf8 encoded string
*
* @param string $text The text to encode
* @access public
*/
if (!function_exists('lc_output_utf8_encoded')) {
  function lc_output_utf8_encoded($text) {
    return utf8_encode($text);
  }
}
/**
* Output a decoded utf8 string
*
* @param string $text The text to decode
* @access public
*/
if (!function_exists('lc_output_utf8_decoded')) {
  function lc_output_utf8_decoded($text) {
    return utf8_decode($text);
  }
}

if (!function_exists('get_permalink_query')) {
  function get_permalink_query($key) {
    global $lC_Database;
    
    $Qpermalink = $lC_Database->query('select query from :table_permalink where keyword = :keyword and type = 2');
    $Qpermalink->bindTable(':table_permalink', 'lc_permalink');
    $Qpermalink->bindValue(':keyword', $key);
    $Qpermalink->execute();          
    
    return $Qpermalink->value('query');
  }
}
?>