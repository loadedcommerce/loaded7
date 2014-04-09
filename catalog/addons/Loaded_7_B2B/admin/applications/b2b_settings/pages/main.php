<?php
/**
  @package    admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: main.php v1.0 2013-08-08 datazen $
*/
$sliderOptions    = '{"size":false,"tooltip":false,"innerMarks":25,"step":25,"knob":true,"topMarks":[{"value":0,"label":"' . $lC_Language->get('text_none') . '"},{"value":25,"label":"' . $lC_Language->get('text_view') . '"},{"value":50,"label":"' . $lC_Language->get('text_insert') . '"},{"value":75,"label":"' . $lC_Language->get('text_update') . '"},{"value":100,"label":"' . $lC_Language->get('text_delete') . '"}],"insetExtremes":true,"barClasses":"blue-gradient black","classes":"float-right"}';

?>
<!-- Main content -->
<style>
.allow-self-register { width:50% !important; }
</style>
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <div class="with-padding-no-top">
    <div id="modalMsg"></div>
    <div class="side-tabs main-tabs">

      <!-- Tabs -->
      <ul class="tabs">
        <li class="active"><a href="#customers"><?php echo $lC_Language->get('heading_customers'); ?></a></li>
        <li><a href="#products"><?php echo $lC_Language->get('heading_products'); ?></a></li>
      </ul>

      
      <!-- Content -->
      <div class="tabs-content">
        <div id="customers" class="with-padding">
        
          <fieldset class="fieldset">
            <legend class="legend"><?php echo $lC_Language->get('heading_customers'); ?></legend>
            <div class="columns no-margin-bottom">
              <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile mid-margin-bottom">
                <p class="button-height inline-label">
                  <label for="allow_self_register" class="label allow-self-register"><?php echo $lC_Language->get('label_allow_self_registrations'); ?></label>
                  <?php echo lc_draw_checkbox_field('allow_self_register', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?><span class="small margin-left"><?php echo $lC_Language->get('info_bubble_displays_create_account_form'); ?></span>                
                </p>
              </div>


              <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile mid-margin-bottom">

                <p class="inline-medium-label button-height">
                  <span class="label"><?php echo $lC_Language->get('label_guest_catalog_access'); ?></span>
                  <span class="guest-catalog-access-slider" data-slider-options='{"size":false,"innerMarks":20,"step":20,"knob":true,"tooltip":"bottom","topMarks":[{"value":0,"label":"Doh!"},{"value":20,"label":"Nope."},{"value":40,"label":"Go on"},{"value":60,"label":"Better"},{"value":80,"label":"Almost"},{"value":100,"label":"Yeah!"}],"insetExtremes":true,"barClasses":"orange-gradient"}'></span>
                </p>

              </div>
            </div>
          </fieldset>        
        </div>

        <div id="products" class="with-padding">
          products tab
        </div>        
        
      </div>
    </div>  
  </div>
</section>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<script>
$(document).ready(function() {  
  $('.guest-catalog-access-slider').slider();
});
</script>
<!-- End main content -->