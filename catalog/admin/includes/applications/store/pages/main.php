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
.dataColThumb { text-align: center; }
.dataColTitle { text-align: left; }
.dataColDesc { text-align: left; }
.dataColAction { text-align: center; }
#storeHeaderRightContainer { width:64%; float:right; font-family:Arial; }
#storeSearchContainer { float:right; width:45%; }
#storeFilterContainer { float:left; width:45%; }
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
            <input type="radio" name="sortby" id="sortby-1" value="1" checked>
            Rating
          </label>
          <label for="sortby-2" class="button blue-active">
            <input type="radio" name="sortby" id="sortby-2" value="2">
            Popular
          </label>
          <label for="sortby-3" class="button blue-active">
            <input type="radio" name="sortby" id="sortby-3" value="3">
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
      <div class="panel-navigation silver-gradient" style=overflow:hidden;">
        <div class="panel-control"><p align="center" class="big-text small-margin-left"><strong>Type</strong></p></div>
        <div class="panel-load-target scrollable" style="height:490px">
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
          <p id="cfgTitle" align="center" class="big-text">Installed
          <a href="javascript://" onclick="oTable.fnReloadAjax();" class="button icon-undo float-right" style="margin-top:1px;"><?php echo $lC_Language->get('button_refresh'); ?></a></p>
        </div>
        <div class="panel-load-target scrollable" style="min-height:460px">
          <div class="large-box-shadow white-gradient with-border" style="padding:3px;">
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
<script>
function showGroup(id, text) {
  $('#contentContainer').html(text + ' Addons Listing Area');
 
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA'); ?>';   
  oTable = $('#dataTable').dataTable({
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": false,
    "bSort": false,
    "bInfo": false,
    "aaSorting": [[3,'desc']],
    "aoColumns": [{ "sWidth": "80px", "sClass": "dataColThumb" },
                  { "sWidth": "20%", "sClass": "dataColTitle" },
                  { "sWidth": "65%", "sClass": "dataColDesc" },
                  { "sWidth": "15%", "sClass": "dataColAction" }]
  });
  oTable.responsiveTable();  
  
}
</script>