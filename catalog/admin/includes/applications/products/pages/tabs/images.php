<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: images.php v1.0 2013-08-08 datazen $
*/
global $lC_Language, $pInfo; 
?>   
<div id="section_images_content" class="with-padding"<?php echo (isset($pInfo) && !empty($pInfo)) ? null : ' style="display:none;"'; ?>>
  <div class="content-panel margin-bottom enabled-panels">
    <div class="panel-navigation silver-gradient scrollable">
      <div class="panel-control"></div>
      <div class="scrollable custom-scroll">
        <div class="navigable">
          <ul class="files-list mini open-on-panel-content">
            <li id="images-gallery-trigger" class="with-right-arrow grey-arrow">     
              <a class="file-link selected-menu" href="javascript:void(0);" onclick="showContent('default');">
                <span class="icon file-jpg"></span>
                <b><?php echo $lC_Language->get('text_product_default_image'); ?></b>
              </a>            
            </li>
            <?php 
            if ($pInfo) { 
              ?>
              <li id="additional-gallery-trigger" class="upsellwrapper"><span class="icon file-jpg"></span><b><?php echo $lC_Language->get('text_additional_images'); ?></b><span class="upsellinfo" upselltitle="<?php echo $lC_Language->get('text_additional_images_upsell_title'); ?>" upselldesc="<?php echo $lC_Language->get('text_additional_images_upsell_desc'); ?>"><?php echo lc_go_pro(); ?></span></li>
              <?php 
            } 
            ?>            
          </ul>
        </div> 
      </div>
    </div>
    <div class="panel-content linen scrollable" style="height:auto">
      <div class="panel-control align-right"></div>
      <a href="javascript:void(0);" onclick="backToNav();" class="imagesBackNav"><div class="back"><span class="back-arrow"></span>Back</div></a>
      <div style="height: auto; position: relative;" class="scrollable with-padding custom-scroll">
        <div class="gallery" id="images-gallery">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="100%" height="100%" valign="top">
                <div class="message white-gradient margin-bottom" style="min-height:37px;">
                  <div style="float: right;">
                    <?php echo (isset($pInfo) ? $lC_Language->get('text_product_image_drag_n_drop') : null); ?>
                    <!--<a href="#" id="remoteFilesLink" onclick="switchImageFilesView('remote');" style="font-weight:bolder; color:#000;"><?php echo $lC_Language->get('image_remote_upload'); ?></a> | <a href="#" id="localFilesLink" onclick="switchImageFilesView('local');" style="color:#000;"><?php echo $lC_Language->get('image_local_files'); ?></a>-->
                  </div>
                  
                  <div id="remoteFiles" style="white-space:nowrap;">
                    <span id="fileUploadField" style="width:90%;"></span>
                    <?php
                    if ( isset($pInfo) ) {
                      ?>
                      <div id="fileUploaderContainer" class="small-margin-top">
                        <noscript>
                          <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
                        </noscript>
                      </div>
                      <?php
                    } else {
                      echo '<div id="fileUploaderContainer" style="display:none;"></div>';
                    }
                    ?>                              
                  </div>
                  <div id="localFiles" style="display: none;">
                    <p><?php echo $lC_Language->get('text_introduction_select_local_images'); ?></p>
                    <select id="localImagesSelection" name="localimages[]" size="5" multiple="multiple" style="width: 100%;"></select>
                    <div id="showProgressGetLocalImages" style="display: none; float: right; padding-right: 10px;"><?php echo lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('image_retrieving_local_files'); ?></div>
                    <p><?php echo realpath('../images/products/_upload'); ?></p>
                    <?php
                      if ( isset($pInfo) ) {
                        echo '<input type="button" value="Assign To Product" class="operationButton" onclick="assignLocalImages();" /><div id="showProgressAssigningLocalImages" style="display: none; padding-left: 10px;">' . lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('image_multiple_upload_progress') . '</div>';
                      }
                    ?>
                  </div>
                </div>
                <?php
                  if ( isset($pInfo) ) {
                    ?>
                    <div id="defaultImagesContainer">
                      <div id="defaultImages" style="overflow: auto;" class="small-margin-top"></div>
                    </div> 
                    
                    <!--VQMOD3-->                   
                    
                  <?php
                  }
                ?>
              </td>
            </tr>
          </table>
        </div>  
      </div>
    </div>
  </div>
</div> 