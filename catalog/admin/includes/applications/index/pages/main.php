<?php
/*
  $Id: main.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/ 
?>
<?php
global $lC_Vqmod;
require_once($lC_Vqmod->modCheck('includes/applications/index/classes/index.php')); 
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>

  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Language->get('heading_title_dashboard'); ?></h1>
  </hgroup>

  <div class="dashboard">

    <div class="columns">
      <div class="nine-columns twelve-columns-mobile" id="dashboard-chart">
        <!-- This div will hold the generated chart -->
      </div>
      <div class="three-columns twelve-columns-mobile new-row-mobile">
        <ul class="stats split-on-mobile">
          <li>
            <a href="#">
              <strong><?php echo lC_Administrators_Index::get_live_data_total('Sessions'); ?></strong> <?php echo $lC_Language->get('text_new') . '<br />' . $lC_Language->get('text_visits'); ?>
            </a>
          </li>
          <li>
            <a href="#">
              <strong><?php echo lC_Administrators_Index::get_live_data_total('Customers'); ?></strong> <?php echo $lC_Language->get('text_new') . '<br />' . $lC_Language->get('text_signups'); ?>
            </a>
          </li>
          <li>
            <a href="#">
              <strong><?php echo lC_Administrators_Index::get_live_data_total('Carts'); ?></strong> <?php echo $lC_Language->get('text_cart') . '<br />' . $lC_Language->get('text_sessions'); ?>
            </a>
          </li>
          <li>
            <a href="#">
              <strong><?php echo lC_Administrators_Index::get_live_data_total('Orders'); ?></strong> <?php echo $lC_Language->get('text_new') . '<br />' . $lC_Language->get('text_orders'); ?>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="with-padding">
    <div class="columns clearfix">
      <?php
      $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/summary');
      $lC_DirectoryListing->setIncludeDirectories(false);
      $files = $lC_DirectoryListing->getFiles();
      $content = array();
      foreach ($files as $file) {
        include($lC_Vqmod->modCheck('includes/modules/summary/' . $file['name']));
        $module = substr($file['name'], 0, strrpos($file['name'], '.'));
        $module_class = 'lC_Summary_' . $module;
        $lC_Summary = new $module_class();
        if ($lC_Summary->hasData()) {
          $content[$lC_Summary->getSortOrder()] = $lC_Summary->getData();
        }
        unset($lC_Summary);
      }
      ksort($content); 
      foreach ($content as $block) {
        echo $block;  
      }            
      ?>
    </div>
  </div>
</section>
<!-- End main content -->