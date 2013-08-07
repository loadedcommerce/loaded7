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
  $lC_ObjectInfo = new lC_ObjectInfo(lc_get_system_information());
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
  LABEL { width: 30% !important; } 
  </style>
  <div class="columns small-margin-left">

    <div class="six-columns twelve-columns-tablet">
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_server_host'); ?></label>
        <span><?php echo $lC_ObjectInfo->get('host') . ' (' . $lC_ObjectInfo->get('ip') . ')'; ?></span>
      </p>    
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_server_operating_system'); ?></label>
        <span><?php echo $lC_ObjectInfo->get('system') . ' ' . $lC_ObjectInfo->get('kernel'); ?></span>
      </p>    
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_server_date'); ?></label>
        <span><?php echo $lC_ObjectInfo->get('date'); ?></span>
      </p>       
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_server_up_time'); ?></label>
        <span><?php echo $lC_ObjectInfo->get('uptime'); ?></span>
      </p> 
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_http_server'); ?></label>
        <span><?php echo $lC_ObjectInfo->get('http_server'); ?></span>
      </p>           
    </div>
    <div class="six-columns twelve-columns-tablet">
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_database_host'); ?></label>
        <span><?php echo $lC_ObjectInfo->get('db_server') . ' (' . $lC_ObjectInfo->get('db_ip') . ')'; ?></span>
      </p>
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_database_version'); ?></label>
        <span><?php echo $lC_ObjectInfo->get('db_version'); ?></span>
      </p>   
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_database_date'); ?></label>
        <span><?php echo $lC_ObjectInfo->get('db_date'); ?></span>
      </p>            
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_database_up_time'); ?></label>
        <span><?php echo $lC_ObjectInfo->get('db_uptime'); ?></span>
      </p> 
      <p class="inline-label">
        <label for="group_name" class="label"><?php echo $lC_Language->get('field_php_version'); ?></label>
        <span><?php echo 'PHP: ' . $lC_ObjectInfo->get('php') . ' / Zend: ' . $lC_ObjectInfo->get('zend') . ' (' . lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=phpInfo'), $lC_Language->get('more_information'), 'target="_blank"') . ')'; ?></span>
      </p>          
    </div>
    <div class="new-row twelve-columns-tablet">
      <p class="align-center">
        <span>
          <a href="http://www.loaded7.com" target="_blank"><?php echo lc_image(lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=image'), 'Loaded Commerce'); ?><br /><b><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></b></a>
        </span>
      </p>
      <form id="install_id" name="install_id" action="" method="post">  
      <p class="align-center">
        <label for="installationID" class="label"><?php echo $lC_Language->get('field_installation_id'); ?></label>
        <span><input type="text" name="id" style="width:17%;" value="<?php echo (defined('INSTALLATION_ID') ? INSTALLATION_ID : $lC_Language->get('no_installation_id')); ?>"><span id="updateIcon" onclick="updateInstallID();" style="cursor:pointer;" class="mid-margin-left icon-cloud-upload icon-blue"></span></span>
      </p> 
      </form>  
    </div>
  </div>  
</section>
<script>
function updateInstallID() {
  $('#updateIcon').attr('class', 'loader mid-margin-left'); 
  var nvp = $("#install_id").serialize();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateInstallID&BATCH'); ?>'
  $.getJSON(jsonLink.replace('BATCH', nvp),
    function (rdata) {
      if (rdata.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (rdata.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      window.location.href = window.location.href;
    }
  );  
}
</script>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<!-- End main content -->