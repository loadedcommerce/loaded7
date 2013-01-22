<?php
/*
  $Id: updates.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors", 1);
require_once('includes/applications/updates/classes/updates.php');  

$updatesDataArr = lC_Updates_Admin::getAvailablePackages(); 
$findDataArr = lC_Updates_Admin::findAvailablePackages('7.0'); 
$availInfoDataArr = lC_Updates_Admin::getAvailablePackageInfo('0'); 
$downloaded = lC_Updates_Admin::downloadPackage(); 
$localPackageExists = lC_Updates_Admin::localPackageExists(); 
$getPackageInfo = lC_Updates_Admin::getPackageInfo(); 
$getPackageContents = lC_Updates_Admin::getPackageContents(); 
$findPackageContents = lC_Updates_Admin::findPackageContents('osc'); 
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
  .dataColCheck { text-align: center; }
  </style>
  <div class="columns with-padding">
  
    <div class="six-columns twelve-columns-tablet">
 
<dl class="accordion same-height">

            <dt>First section</dt>
            <dd>
              <div class="with-padding">
                <p>First section content</p>
                <p>The accordion plugin may also equalize the sections' height, just by adding a class - as for this example.</p>
                <p>Give it a try!</p>
              </div>
            </dd>

            <dt>Second section</dt>
            <dd>
              <div class="with-padding">
                Second section content
              </div>
            </dd>

          </dl>
    
    </div>
    
    <div class="six-columns twelve-columns-tablet">

 
    </div>
    
  </div>
</section>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<!-- Main content end -->