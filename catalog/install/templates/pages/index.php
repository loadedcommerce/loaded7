<?php
/**
  @package    catalog::install::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: index.php v1.0 2013-08-08 datazen $
*/
function cURLTest(){  
  $testText = 'data';
  $ch = curl_init(); 
  $request_type = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on')) ? 'https' : 'http';
  curl_setopt($ch, CURLOPT_URL, $request_type . '://api.loadedcommerce.com/1_0/check/serial/'); 
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)"); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
  $response = curl_exec($ch); 
  $errmsg = curl_error($ch); 
  $cInfo = curl_getinfo($ch); 
  curl_close($ch); 
  if (strlen($response) > 0) { 
    return true;
  } else { 
    return false;
  }
}

$vInfo = explode('|', array_shift(array_values(preg_split('/\r\n|\r|\n/', file_get_contents('../includes/version.txt'), 2))));
$version = $vInfo[0];
$ok = TRUE;
?>
<style>
.noticeBox { border:1px solid red; padding:5px; background-color:#fdecea; margin-bottom:10px; }
.info-block { border:1px solid #2964af; float:left; }
.info-block-title { background-color:#278dcb; color:white; padding:5px; }
.info-pane-contents { background-color:#dbeefa; padding:10px; }
/* .content-pane { margin-left:-32%; margin-right:20px; } */
.block-title { text-shadow:0 1px 0 black; } 
TD { height:19px; }
</style>
<form method="post" action="index.html" class="block wizard-enabled">
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="active"><span class="wizard-step">1</span> Welcome</li>
    <li class="hide-on-mobile"><span class="wizard-step">2</span> Database</li>
    <li class="hide-on-mobile"><span class="wizard-step">3</span> Server</li>
    <li class="hide-on-mobile"><span class="wizard-step">4</span> Settings</li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span> Finished!</li>
  </ul>
  <fieldset class="wizard-fieldset fields-list current active">
    <legend class="legend">Welcome</legend>
    <div id="languageContainer">
      <ul style="list-style-type: none; padding-right: 5px; margin: 0px; display: inline; float: right;">
        <li style="display: inline;"><?php echo $lC_Language->get('title_language'); ?></li>
        <?php
          foreach ($lC_Language->getAll() as $available_language) {
          ?>
          <li style="display: inline;"><?php echo '<a href="index.php?language=' . $available_language['code'] . '">' . $lC_Language->showImage($available_language['code']) . '</a>'; ?></li>
          <?php      
          }
        ?>
      </ul>
    </div>
    <div class="thin">
      <h3><?php echo sprintf($lC_Language->get('page_title_welcome'), $version); ?></h3>
    </div>
    <noscript>
      <div class="noticeBox">    
        <?php echo $lC_Language->get('error_javascript_disabled'); ?>
      </div>
    </noscript>  
    <?php
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../addons'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../addons') , 0775);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/backups'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../admin/backups') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/images/avatar'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../admin/images/avatar') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/images/graphs'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../admin/images/graphs') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/languages'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/languages') , 0775);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/templates'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/templates') , 0775);
    
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/') , 0775);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/languages'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/languages') , 0775);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/work') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/addons'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/work/addons') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache/vqmod'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache/vqmod') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs/vqmod'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs/vqmod') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/qrcode'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/work/qrcode') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/updates'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes/work/updates') , 0777);   
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/banners'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/banners') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/branding'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/branding') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/categories'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/categories') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/manufacturers'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/manufacturers') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/products') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/large'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/products/large') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/mini'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/products/mini') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/originals'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/products/originals') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/popup'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/products/popup') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/product_info'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/products/product_info') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/thumbnails'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../images/products/thumbnails') , 0777);
    if(is_dir(lc_realpath(dirname(__FILE__) . '/../../../templates'))) @chmod(lc_realpath(dirname(__FILE__) . '/../../../templates') , 0775);

    
    if (file_exists(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php') && !is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php')) {
      @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php', 0777);
    }

    if (file_exists(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php') && !is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php')) {
      ?>
      <p class="message icon-warning red-gradient">   
        <span class="stripes animated"></span>
        <?php echo sprintf($lC_Language->get('error_configuration_file_not_writeable'), lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php'); ?>
        <?php echo '<br /><br />' . $lC_Language->get('error_configuration_file_alternate_method'); ?>        
      </p>    
      <?php
    }
    ?> 
    <div class="left-column-200px">
      <div class="left-column">
        <div class="block">
          <h4 class="block-title green-gradient glossy"><?php echo $lC_Language->get('box_server_title'); ?></h4>
          <div style="padding: 0 7px 7px 7px;">

            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><b><?php echo $lC_Language->get('box_server_php_version'); ?></b></td>
                <td align="right"><?php echo phpversion(); ?></td>
                <?php if (version_compare(PHP_VERSION, '5.3') !== 1) $ok = FALSE; ?>
                <td align="right" width="25"><img src="templates/img/icons/<?php echo ((version_compare(PHP_VERSION, '5.3') === 1) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
            </table><br />
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><b><?php echo $lC_Language->get('box_server_php_settings'); ?></b></td>
                <td align="right"></td>
                <td align="right" width="25"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_register_globals'); ?></td>
                <td align="right"><?php echo (((int)ini_get('register_globals') === 0) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('register_globals') !== 0) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('register_globals') === 0) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_magic_quotes'); ?></td>
                <td align="right"><?php echo (((int)ini_get('magic_quotes') === 0) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('magic_quotes') !== 0) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('magic_quotes') === 0) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_file_uploads'); ?></td>
                <td align="right"><?php echo (((int)ini_get('file_uploads') !== 1) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('file_uploads') !== 1) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('file_uploads') === 1) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_session_auto_start'); ?></td>
                <td align="right"><?php echo (((int)ini_get('session.auto_start') === 0) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('session.auto_start') !== 0) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('session.auto_start') === 0) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_session_use_trans_sid'); ?></td>
                <td align="right"><?php echo (((int)ini_get('session.use_trans_sid') === 0) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('session.use_trans_sid') !== 0) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('session.use_trans_sid') === 0) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_post_max_size'); ?></td>
                <td align="right"><?php echo $lC_Language->get('post_max_size_text'); ?></td>
                <?php if ((int)ini_get('post_max_size') < 10) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('post_max_size') < 10) ? 'cross.gif' : 'tick.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_upload_max_filesize'); ?></td>
                <td align="right"><?php echo $lC_Language->get('upload_max_filesize_text'); ?></td>
                <?php if ((int)ini_get('upload_max_filesize') < 10) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('upload_max_filesize') < 10) ? 'cross.gif' : 'tick.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
            </table><br />
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><b><?php echo $lC_Language->get('box_server_php_extensions'); ?></b></td>
                <td align="right" width="25"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_mysqli'); ?></td>
                <?php if (!extension_loaded('mysqli')) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (extension_loaded('mysqli') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_gd'); ?></td>
                <?php if (!extension_loaded('gd')) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (extension_loaded('gd') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_curl'); ?></td>
                <?php 
                $okCurl = true;
                if (!extension_loaded('curl')) {
                  $okCurl = false;
                } else {
                  $okCurl = cURLTest();
                }
                if (!$okCurl) $ok = false;
                ?>                
                <td align="right"><img src="templates/img/icons/<?php echo (($okCurl) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_openssl'); ?></td>
                <?php if (!extension_loaded('openssl')) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (extension_loaded('openssl') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_phar'); ?></td>
                <?php if (!extension_loaded('Phar')) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (extension_loaded('Phar') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>              
            </table>
          </div>
        </div> 
        
        <div class="block margin-top">
          <h4 class="block-title green-gradient glossy"><?php echo $lC_Language->get('page_heading_ioncube'); ?></h4>
          <div style="padding: 0 7px 7px 7px;">
            <table id="ioncCubeTable" border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><p class="mid-margin-left mid-margin-right mid-margin-bottom"><?php echo $lC_Language->get('page_text_ioncube'); ?></p></td>
              </tr>
              <tr>
                <td>
                  <p class="mid-margin-left">
                  <?php 
                    if (function_exists('ioncube_test')) {
                      $ioncube = ioncube_test(); 
                      echo $ioncube['txt'];
                      if ($ioncube['ok'] != 1) {
                        $ok = FALSE;
                      }                       
                    } 
                  ?>
                  </p>
                </td>
              </tr>
            </table>
          </div>
        </div>         
      </div>
      <div class="right-column">
        <div class="content-pane">
          <p><?php echo $lC_Language->get('text_welcome'); ?></p>
          <h4 class="no-margin-top mid-margin-bottom"><?php echo $lC_Language->get('page_heading_server_requirements'); ?></h4>
          <ul>
            <li>PHP v5.3+ (with MySQLi extension)</li>
            <li>MySQL v4.1.13+ or v5.0.7+</li>
          </ul>
          <h4 class="no-margin-top mid-margin-bottom"><?php echo $lC_Language->get('page_heading_permissions'); ?></h4>
          <table id="permissionsTable" border="0" width="100%" cellspacing="0" cellpadding="2">         
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/backups/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../admin/backups/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../admin/backups/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">admin/backups/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>  
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/images/avatar/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../admin/images/avatar/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../admin/images/avatar/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">admin/images/avatar/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>            
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/images/graphs/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../admin/images/graphs/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../admin/images/graphs/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">admin/images/graphs/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>  
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/languages/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/languages/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/languages/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">admin/includes/languages/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                         
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/languages/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/languages/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/languages/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/languages/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                                            
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/work/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/work/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/work/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>        
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/addons/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/work/addons/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/work/addons/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/work/addons/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>  
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/work/cache/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>             
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache/vqmod/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache/vqmod/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/work/cache/vqmod/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/work/cache/vqmod/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                      
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/work/logs/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>  
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs/vqmod/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs/vqmod/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/work/logs/vqmod/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/work/logs/vqmod/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                       
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/qrcode/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/work/qrcode/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/work/qrcode/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/work/qrcode/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>   
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/updates/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/work/updates/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/work/updates/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/work/updates/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                     
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr> 
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/banners/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/banners/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/banners/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/banners/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                 
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/branding/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/branding/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/branding/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/branding/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>            
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/categories/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/categories/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/categories/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/categories/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/manufacturers/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/manufacturers/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/manufacturers/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/manufacturers/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>    
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/products/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/products/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/products/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                    
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/large/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/products/large/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/products/large/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/products/large/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>            
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/mini/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/products/mini/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/products/mini/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/products/mini/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr> 
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/originals/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/products/originals/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/products/originals/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/products/originals/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                           
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/popup/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/products/popup/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/products/popup/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/products/popup/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>             
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/product_info/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/products/product_info/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/products/product_info/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/products/product_info/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>     
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/small/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/products/small/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/products/small/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/products/small/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                     
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images/products/thumbnails/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images/products/thumbnails/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/products/thumbnails/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/products/thumbnails/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                         
          </table> 
        </div>
      </div>
      <?php
      if ($ok) {
        ?>
        <div id="buttonContainer" class="large-margin-top margin-left margin-bottom large-margin-right" style="float:right">
          <a href="javascript:void(0);" class="button margin-right" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#upgrade_method').val('S'); window.location.href = 'upgrade.php?step=1'">
            <span class="button-icon orange-gradient glossy"><span class="icon-cloud-upload"></span></span>
            <?php echo addslashes($lC_Language->get('image_button_upgrade')); ?>
          </a>          
          <a href="install.php" class="button">
            <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
            <?php echo addslashes($lC_Language->get('image_button_install')); ?>
          </a>
        </div>
        <?php          
      } else {
        ?>
        <div id="buttonContainer" class="large-margin-top large-margin-right" style="float:right">
          <a href="index.php" class="button">
            <span class="button-icon red-gradient glossy"><span class="icon-refresh"></span></span>
            <?php echo addslashes($lC_Language->get('image_button_retry')); ?>
          </a>
        </div>
        <div id="phpInfoContainer" class="large-margin-top large-margin-right" style="float:right;cursor:pointer;">
          <a class="button" id="phpInfoTrigger">
            <span class="button-icon blue-gradient glossy"><span class="icon-info-round"></span></span>
            <?php echo addslashes($lC_Language->get('image_button_phpinfo')); ?>
          </a>
        </div>
        <?php 
      }
      ?>
    </div>
    <div id="phpInfo" class="large-margin-top" style="display:none;">
      <style scoped="scoped">
        #phpinfo { background-color: #eeeeee; color: #000000; }
        #phpinfo pre { margin: 0px; font-family: monospace; }
        #phpinfo a:link { color: #000099; text-decoration: none; }
        #phpinfo a:hover { text-decoration: underline; }
        #phpinfo table { border-collapse: collapse; }
        #phpinfo .center { text-align: center; }
        #phpinfo .center table { margin-left: auto; margin-right: auto; text-align: left; }
        #phpinfo .center th { text-align: center !important; }
        #phpinfo td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline; }
        #phpinfo h1 {}
        #phpinfo h2 {}
        #phpinfo .p {}
        #phpinfo .e { background-color: #ccccff; font-weight: bold; color: #000000; }
        #phpinfo .h { background-color: #9999cc; font-weight: bold; color: #000000; }
        #phpinfo .v { background-color: #cccccc; color: #000000; }
        #phpinfo .vr { background-color: #cccccc; text-align: right; color: #000000; }
        #phpinfo img { float: right; border: 0px; }
        #phpinfo hr { width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000; margin: 0 auto 0 auto; }
      </style>
      <div id="phpinfo" style="padding-top:20px;">
        <?php 
          ob_start();
          phpinfo();
          $pinfo = ob_get_contents();
          ob_end_clean();
          // the name attribute "module_Zend Optimizer" of an anker-tag is not xhtml valide, so replace it with "module_Zend_Optimizer"
          echo (str_replace ("module_Zend Optimizer", "module_Zend_Optimizer", preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo)));
        ?>
      </div>
    </div>    
  </fieldset>  
</form>
<script>
  $(document).ready(function() {
    $("#phpInfoTrigger").click(function() {
      $("#phpInfo").show();
      $("#phpInfo").animate({
        scrollTop: $("#phpInfo").offset().top
      }, 2000);
    });
  });
</script>