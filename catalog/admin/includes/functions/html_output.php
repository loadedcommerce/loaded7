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
 * Generate an internal URL address for the administration side
 *
 * @param string $page The page to link to
 * @param string $parameters The parameters to pass to the page (in the GET scope)
 * @access public
 */

  function lc_href_link_admin($page = null, $parameters = null) {
    if (ENABLE_SSL === true) {
      $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . DIR_WS_ADMIN;
    } else {
      $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_ADMIN;
    }

    $link .= $page;

    if (empty($parameters) && !lc_empty(SID)) {
      $link .= '?' . SID;
    } else {
      $link .= '?' . $parameters;

      if (!lc_empty(SID)) {
        $link .= '&' . SID;
      }
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) {
      $link = substr($link, 0, -1);
    }
    
    return $link;
  }

////
// javascript to dynamically update the states/provinces list when the country is changed
// TABLES: zones
  function lc_js_zone_list($country, $form, $field) {
    global $lC_Database, $lC_Language;

    $num_country = 1;
    $output_string = '';

    $Qcountries = $lC_Database->query('select distinct zone_country_id from :table_zones order by zone_country_id');
    $Qcountries->bindTable(':table_zones', TABLE_ZONES);
    $Qcountries->execute();

    while ($Qcountries->next()) {
      if ($num_country == 1) {
        $output_string .= '  if (' . $country . ' == "' . $Qcountries->valueInt('zone_country_id') . '") {' . "\n";
      } else {
        $output_string .= '  } else if (' . $country . ' == "' . $Qcountries->valueInt('zone_country_id') . '") {' . "\n";
      }

      $num_state = 1;

      $Qzones = $lC_Database->query('select zone_name, zone_id from :table_zones where zone_country_id = :zone_country_id order by zone_name');
      $Qzones->bindTable(':table_zones', TABLE_ZONES);
      $Qzones->bindInt(':zone_country_id', $Qcountries->valueInt('zone_country_id'));
      $Qzones->execute();

      while ($Qzones->next()) {
        if ($num_state == '1') {
          $output_string .= '    ' . $form . '.' . $field . '.options[0] = new Option("' . $lC_Language->get('all_zones') . '", "");' . "\n";
        }

        $output_string .= '    ' . $form . '.' . $field . '.options[' . $num_state . '] = new Option("' . $Qzones->value('zone_name') . '", "' . $Qzones->valueInt('zone_id') . '");' . "\n";

        $num_state++;
      }

      $num_country++;
    }

    $output_string .= '  } else {' . "\n" .
                      '    ' . $form . '.' . $field . '.options[0] = new Option("' . $lC_Language->get('all_zones') . '", "");' . "\n" .
                      '  }' . "\n";

    return $output_string;
  }

  function lc_legend($params) {
    global $lC_Language;

    if (!is_array($params)) return false;
    
    $legend = '<div id="dataTableLegendBlock"><span id="dataTableLegend"><div id="dataTableLegendTitle">' . $lC_Language->get('table_action_legend') . ':</div><div id="dataTableLegendContent"><ul>';
    foreach ($params as $value) {
      $icon_name = $value . '.png';
      $icon_text = 'icon_' . $value;
      $legend .= '<li><div id="dataTableLegendIconBlock"><div id="dataTableLegendIcon">' . lc_icon($icon_name) . '</div><div id="dataTableLegendText">' . $lC_Language->get($icon_text) . '</div></div></li>';
    }
    $legend .= '</ul></div></span></div>';

    return $legend;
  }

  function lc_status() {
    $status = '<style>
              .status { position:relative; }
              .status-pos { position:absolute; top:0; right:150px; }
              .status-img { padding-top:2px; padding-left:3px; }      
              </style>
              <div class="status">
                <div id="status-working" style="display:none;"><div class="status-pos"><div class="status-img"><img src="images/loading.gif"></div></div></div>  
                <div id="status-success" style="display:none;"><div class="status-pos"><div class="status-img"><img src="images/success.png"></div></div></div>   
                <div id="status-failure" style="display:none;"><div class="status-pos"><div class="status-img"><img src="images/failure.png"></div></div></div>   
              </div>';

    return $status;
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

  function lc_icon_admin($image, $title = null, $group = null, $parameters = null, $default = true) {
    global $lC_Language;

    if ($image == null) return false;

    if ( is_null($title) ) {
      $title = $lC_Language->get('icon_' . substr($image, 0, strpos($image, '.')));
    }

    if ( is_null($group) ) {
      $group = '16';
    }

    $template_code = (isset($_SESSION['template']['code'])) ? $_SESSION['template']['code'] : $lC_Template->getCode();

    return lc_image('templates/' . $template_code . '/img/icons/' . (!empty($group) ? $group . '/' : null) . $image, $title, null, null, $parameters);
  }

/**
 * Get the raw URL to an icon from a template set
 *
 * @param string $image The icon to display
 * @param string $group The size group of the icon
 * @access public
 */

  function lc_icon_admin_raw($image, $group = '16') {
    global $lC_Template;

    return 'templates/' . $lC_Template->getCode() . '/img/icons/' . (!empty($group) ? $group . '/' : null) . $image;
  }
  
  function lc_go_pro($no_tooltip = false) {
    global $lC_Language;

    if ($no_tooltip) {
      $html = '<span class="upsell-spot">' . 
              '  <a href="javascript(void);" onclick="showUpsellSpot(this); return false;" style="cursor:pointer !important;">' .
              '    <small class="tag red-bg" title="' . $lC_Language->get('text_cick_for_info') . '">' . $lC_Language->get('text_pro') . '</small>' . 
              '  </a>' .
              '</span>';
    } else {
      $html = '<span class="upsell-spot">' . 
              '  <a href="javascript(void);" onclick="showUpsellSpot(this); return false;" style="cursor:pointer !important;">' .
              '    <small class="tag red-bg with-tooltip" title="' . $lC_Language->get('text_cick_for_info') . '" data-tooltip-options=\'{"classes":["anthracite-gradient glossy small no-padding"],"position":"right"}\'>' . $lC_Language->get('text_pro') . '</small>' . 
              '  </a>' .
              '</span>';
    }
    
    return $html;
  }
  
  function lc_show_info_bubble($msg, $styleA = 'margin-right:6px;', $classA = 'on-left grey float-right', $classB = 'blue-bg', $minWidth = '180px') {
    $html = '<span style="' . $styleA . '" class="info-spot ' . $classA . '"><span class="icon-info-round"></span><span style="min-width:' . $minWidth . ';" class="info-bubble ' . $classB . '">' . $msg . '</span></span>';
    
    return $html;    
  }
?>