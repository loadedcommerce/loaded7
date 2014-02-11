<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: services.php v1.0 2013-08-08 datazen $
*/
class lC_Services {
  var $services,
      $started_services,
      $call_after_header_content = array(),
      $call_before_page_content = array(),
      $call_after_page_content = array(),
      $call_before_footer_content = array();

  public function lC_Services() {
    $this->services = explode(';', MODULE_SERVICES_INSTALLED);
  }

  public function startServices() {
    $this->started_services = array();

    foreach ($this->services as $service) {
      $this->startService($service);
    }
  }

  public function stopServices() {
    /*
    ugly workaround to force the output_compression/GZIP service module to be stopped last
    to make sure all content in the buffer is compressed and sent to the client
    */
    if ($this->isStarted('output_compression')) {
      $key = array_search('output_compression', $this->started_services);
      unset($this->started_services[$key]);

      $this->started_services[] = 'output_compression';
    }

    foreach ($this->started_services as $service) {
      $this->stopService($service);
    }
  }

  public function startService($service) {
    global $lC_Vqmod;
    
    include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/modules/services/' . $service . '.php'));

    if (@call_user_func(array('lC_Services_' . $service, 'start'))) {
      $this->started_services[] = $service;
    }
  }

  public function stopService($service) {
    @call_user_func(array(DIR_FS_CATALOG . 'lC_Services_' . $service, 'stop'));
  }


  public function isStarted($service) {
    return in_array($service, $this->started_services);
  }

  public function addCallAfterHeaderContent($object, $method) {
    $this->call_after_header_content[] = array($object, $method);
  }

  public function addCallBeforePageContent($object, $method) {
    $this->call_before_page_content[] = array($object, $method);
  }

  public function addCallAfterPageContent($object, $method) {
    $this->call_after_page_content[] = array($object, $method);
  }

  public function addCallBeforeFooterContent($object, $method) {
    $this->call_before_footer_content[] = array($object, $method);
  }

  public function hasAfterHeaderContentCalls() {
    return !empty($this->call_after_header_content);
  }

  public function hasBeforePageContentCalls() {
    return !empty($this->call_before_page_content);
  }

  public function hasAfterPageContentCalls() {
    return !empty($this->call_after_page_content);
  }

  public function hasBeforeFooterContentCalls() {
    return !empty($this->call_before_footer_content);
  }

  public function getCallAfterHeaderContent() {
    return $this->call_after_header_content;
  }

  public function getCallBeforePageContent() {
    return $this->call_before_page_content;
  }

  public function getCallAfterPageContent() {
    return $this->call_after_page_content;
  }

  public function getCallBeforeFooterContent() {
    return $this->call_before_footer_content;
  }
}
?>