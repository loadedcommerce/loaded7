<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: maxmind_geolite_country.php v1.0 2013-08-08 datazen $
*/
class lC_GeoIP_maxmind_geolite_country extends lC_GeoIP_Admin {

  var $_title;
  var $_description;
  var $_code = 'maxmind_geolite_country';
  var $_author_name = 'LoadedCommerce';
  var $_author_www = 'http://www.loadedcommerce.com';
  var $_handler;

  public function lC_GeoIP_maxmind_geolite_country() {
    global $lC_Language;

    $this->_title = $lC_Language->get('geoip_maxmind_geolite_country_title');
    $this->_description = $lC_Language->get('geoip_maxmind_geolite_country_description');
    $this->_status = (defined('MODULE_DEFAULT_GEOIP') && (MODULE_DEFAULT_GEOIP == $this->_code));
  }

  public function activate() {
    global $lC_Vqmod;
    
    include($lC_Vqmod->modCheck('external/maxmind/geoip/geoip.php'));

    $this->_handler = geoip_open('external/maxmind/geoip/geoip.dat', GEOIP_MEMORY_CACHE);
    $this->_active = true;
  }

  public function deactivate() {
    geoip_close($this->_handler);
    unset($this->_handler);
    $this->_active = false;
  }

  public function isValid($ip_address) {
    return (geoip_country_id_by_addr($this->_handler, $ip_address) !== false);
  }

  public function getCountryISOCode2($ip_address) {
    return strtolower(geoip_country_code_by_addr($this->_handler, $ip_address));
  }

  public function getCountryName($ip_address) {
    return geoip_country_name_by_addr($this->_handler, $ip_address);
  }

  public function getData($ip_address) {
    return array(lc_image('../images/worldflags/' . $this->getCountryISOCode2($ip_address) . '.png', $this->getCountryName($ip_address) . ', ' . $ip_address, 18, 12) . '&nbsp;' . $this->getCountryName($ip_address));
  }
}
?>