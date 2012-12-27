<?php
/*
  $Id: maxmind_geolite_country.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_GeoIP_maxmind_geolite_country extends lC_GeoIP_Admin {

    var $_title;
    var $_description;
    var $_code = 'maxmind_geolite_country';
    var $_author_name = 'LoadedCommerce';
    var $_author_www = 'http://www.loadedcommerce.com';
    var $_handler;

    function lC_GeoIP_maxmind_geolite_country() {
      global $lC_Language;

      $this->_title = $lC_Language->get('geoip_maxmind_geolite_country_title');
      $this->_description = $lC_Language->get('geoip_maxmind_geolite_country_description');
      $this->_status = (defined('MODULE_DEFAULT_GEOIP') && (MODULE_DEFAULT_GEOIP == $this->_code));
    }

    function activate() {
      include('external/maxmind/geoip/geoip.php');

      $this->_handler = geoip_open('external/maxmind/geoip/geoip.dat', GEOIP_MEMORY_CACHE);
      $this->_active = true;
    }

    function deactivate() {
      geoip_close($this->_handler);
      unset($this->_handler);
      $this->_active = false;
    }

    function isValid($ip_address) {
      return (geoip_country_id_by_addr($this->_handler, $ip_address) !== false);
    }

    function getCountryISOCode2($ip_address) {
      return strtolower(geoip_country_code_by_addr($this->_handler, $ip_address));
    }

    function getCountryName($ip_address) {
      return geoip_country_name_by_addr($this->_handler, $ip_address);
    }

    function getData($ip_address) {
      return array(lc_image('../images/worldflags/' . $this->getCountryISOCode2($ip_address) . '.png', $this->getCountryName($ip_address) . ', ' . $ip_address, 18, 12) . '&nbsp;' . $this->getCountryName($ip_address));
    }
  }
?>