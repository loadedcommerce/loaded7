<?php
/**
  $Id: services.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Services {
  var $services,
      $started_services,
      $call_before_page_content = array(),
      $call_after_page_content = array();

  function lC_Services() {
    $this->services = explode(';', MODULE_SERVICES_INSTALLED);
  }

  function startServices() {
    $this->started_services = array();

    foreach ($this->services as $service) {
      $this->startService($service);
    }
  }

  function stopServices() {
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

  function startService($service) {
    global $lC_Vqmod;
    
    include($lC_Vqmod->modCheck('includes/modules/services/' . $service . '.php'));

    if (@call_user_func(array('lC_Services_' . $service, 'start'))) {
      $this->started_services[] = $service;
    }
  }

  function stopService($service) {
    @call_user_func(array('lC_Services_' . $service, 'stop'));
  }


  function isStarted($service) {
    return in_array($service, $this->started_services);
  }

  function addCallBeforePageContent($object, $method) {
    $this->call_before_page_content[] = array($object, $method);
  }

  function addCallAfterPageContent($object, $method) {
    $this->call_after_page_content[] = array($object, $method);
  }

  function hasBeforePageContentCalls() {
    return !empty($this->call_before_page_content);
  }

  function hasAfterPageContentCalls() {
    return !empty($this->call_after_page_content);
  }

  function getCallBeforePageContent() {
    return $this->call_before_page_content;
  }

  function getCallAfterPageContent() {
    return $this->call_after_page_content;
  }
}
?>