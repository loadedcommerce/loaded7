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
<!-- main content -->
<style>
.dataColThumb { text-align: center; width: 80px; }
.dataColTitle { text-align: left; }
.dataColDesc { text-align: left; }
.dataColAction { text-align: center; width:110px; }
#storeHeaderRightContainer { width:64%; float:right; font-family:Arial; }
#storeSearchContainer { float:right; width:45%; }
#storeFilterContainer { float:left; width:45%; }
.version-tag { position:absolute; right:-10px; bottom:-6px; opacity:0.5; }
.tag { font-size:.8em; }
#dataTable TD { border: none; cursor: pointer; }

/* nav menu */
.store-type-selected { border-left-width: 4px !important; border-color: #00569c !important; padding-left:27px !important; height: 28px !important; }
.message-menu > li > a, li.message-menu > a { margin: -3px -65px -10px -30px; min-height: 0; }
.message-menu > li > a:hover, .no-touch li.message-menu > a:hover { min-height: 28px; }
.message-menu > li, li.message-menu { line-height: 10px; }

.info-bubble { min-width: 280px !important; }
<?php
$typesArr = lC_Store_Admin::getAllTypes();
foreach ($typesArr as $key => $value) {
  echo ".store-menu-" . strtolower($value['text']) . " { background-size:16px 16px !important; background: url('templates/default/img/icons/16/" . $value['icon'] . "') no-repeat scroll 8px 15px transparent !important; }";
}
?>   
</style>
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin" style="padding-bottom:0;">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
    <div class="columns margin-bottom" id="storeHeaderRightContainer">
      <!-- search -->
      <div class="six-columns twelve-columns-tablet no-margin-bottom" id="storeSearchContainer">
        <section>
          <div class="no-padding" id="storeSearchContainerInput">
            <form method="post" action="storeSearch" name="storeSearch">
              <ul class="inputs mega-search-border">
                <li>
                  <span class="icon-search mid-margin-left"></span>
                  <input type="text" id="storeSearch" name="s" value="" placeholder="Search" autocomplete="off" class="input-unstyled">
                </li>
              </ul>
            </form>
          </div>        
        </section>
      </div>
      <!-- filter -->
      <div class="six-columns twelve-columns-tablet no-margin-bottom" id="storeFilterContainer">
        <label class="label" for="sortby">Sort by:</label>
        <span class="button-group">
          <label for="sortby-1" class="button blue-active">
            <input type="radio" name="sortby" id="sortby-1" value="rating" checked>
            Rating
          </label>
          <label for="sortby-2" class="button blue-active">
            <input type="radio" name="sortby" id="sortby-2" value="popular">
            Popular
          </label>
          <label for="sortby-3" class="button blue-active">
            <input type="radio" name="sortby" id="sortby-3" value="date">
            New
          </label>
        </span>      
      </div>
    </div><div style="clear:both;"></div>
  </hgroup>

  <div class="with-padding">  
    <!-- main panel -->
    <div class="content-panel margin-bottom">
    
      <!-- menu nav panel -->
      <div class="panel-navigation silver-gradient">
        <div class="panel-control"><p align="center" class="big-text small-margin-left">Type</p></div>
        <div class="panel-load-target scrollable" style="height:450px">
          <div class="navigable">
            <ul class="unstyled-list open-on-panel-content">
              <?php echo lC_Store_Admin::drawMenu(); ?>
            </ul>
          </div>
        </div>
      </div>    
    
      <!-- content panel -->
      <div class="panel-content">
        <div class="panel-control">
          <p id="cfgTitle" align="center" class="big-text"><span id="cfgTitleText"></span>
          <a href="javascript://" onclick="_updateTitles();" class="button icon-undo float-right" style="margin-top:1px;"><?php echo $lC_Language->get('button_refresh'); ?></a></p>
        </div>
        <div class="panel-load-target scrollable" style="min-height:460px">
          <div class="large-box-shadow white-gradient">
            <div id="contentContainer">
            
              <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
                <tbody><!-- ajax delivered content --></tbody>
              </table>   
                     
            </div>
          </div>
        </div>
      </div>
      <!-- content panel eof-->
      
    </div>  
  </div>
</section>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<!-- End main content -->