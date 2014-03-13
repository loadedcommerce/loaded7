<?php
  /**
  @package    catalog::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: footer.php v1.0 2013-08-08 datazen $
  */
?>
<!--footer.php start-->

<div id="footer" class="container">
  <hr>
  <div class="row">
    <div class="col-sm-3 col-lg-3">
      <h4 class="line3 center standart-h4title"><span>New Arrivals</span></h4>
      <ul class="footer-links list-indent list-unstyled">
        <?php echo lC_Template_output::newArrivalsListing(); ?>
      </ul>
    </div>

    <div class="col-sm-3 col-lg-3">
      <h4 class="line3 center standard-h4title"><span>Customer Service</span></h4>
      <ul class="footer-links list-indent list-unstyled">
        <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, 'cPath=2_3', 'AUTO'); ?>"><?php echo $lC_Language->get('text_shipping_returns'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, 'cPath=2_4', 'AUTO'); ?>"><?php echo $lC_Language->get('text_privacy'); ?></a></li>                                              
        <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, 'cPath=2_5', 'AUTO'); ?>"><?php echo $lC_Language->get('text_terms_conditions'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'SSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'sitemap', 'AUTO'); ?>"><?php echo $lC_Language->get('text_sitemap'); ?></a></li> 
      </ul>
    </div> 

    <div class="col-sm-3 col-lg-3">
      <h4 class="line3 center standard-h4title"><span>My Account</span></h4>
      <ul class="footer-links list-indent list-unstyled">
        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo $lC_Language->get('text_sign_in'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password', 'SSL'); ?>"><?php echo $lC_Language->get('text_my_password'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL'); ?>"><?php echo $lC_Language->get('text_my_order_history'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'); ?>"><?php echo $lC_Language->get('text_my_address_book'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'notifications', 'SSL'); ?>"><?php echo $lC_Language->get('text_my_updates_alerts'); ?></a></li>
      </ul>
    </div>

    <div class="col-sm-3 col-lg-3 large-margin-bottom">
      <h4 class="line3 center standard-h4title"><span>Our Office</span></h4>
      <address class="margin-left">
        <strong><?php echo STORE_NAME; ?></strong><br>
        <?php echo nl2br(STORE_NAME_ADDRESS); ?><br>
      </address>

      <!-- QR Code -->
      <a id="qrcode-tooltip">
        <span style="cursor:pointer;">
          <img src="images/icons/qr-icon.png" alt="<?php echo $lC_Language->get('text_click_and_scan');?>" style="vertical-align:middle; padding-right:6px;" /><span class="small-margin-left"><?php echo $lC_Language->get('text_click_and_scan');?></span>
        </span>
      </a>
      <div id="ShowQRCode"></div>
    </div>               
    <div class="margin-left small-padding-left margin-right small-padding-right"><?php echo $lC_Template->getBranding('footer_text');?></div>
  </div>
  <p class="margin-top">
    <?php
      if ($lC_Template->getBranding('social_facebook_page') != '') {
        echo '<a href="' . $lC_Template->getBranding('social_facebook_page') . '" target="_blank"><img alt="Facebook" src="' . DIR_WS_IMAGES . 'icons/facebook.png" /></a>';
      }
      if ($lC_Template->getBranding('social_tweeter') != '') {
        echo '<a href="' . $lC_Template->getBranding('social_twitter') . '" target="_blank"><img alt="Twitter" src="' . DIR_WS_IMAGES . 'icons/twitter_bird.png" /></a>';
      }
      if ($lC_Template->getBranding('social_pinterest') != '') {
        echo '<a href="' . $lC_Template->getBranding('social_pinterest') . '" target="_blank"><img alt="Pinterest" src="' . DIR_WS_IMAGES . 'icons/pinterest.png" /></a>';
      }
      if ($lC_Template->getBranding('social_google_plus') != '') {
        echo '<a href="' . $lC_Template->getBranding('social_google_plus') . '" target="_blank"><img alt="Google+" src="' . DIR_WS_IMAGES . 'icons/google_plus.png" /></a>';
      }
      if ($lC_Template->getBranding('social_youtube') != '') {
        echo '<a href="' . $lC_Template->getBranding('social_youtube') . '" target="_blank"><img alt="Youtube" src="' . DIR_WS_IMAGES . 'icons/youtube.png" /></a>';
      }
      if ($lC_Template->getBranding('social_linkedin') != '') {
        echo '<a href="' . $lC_Template->getBranding('social_linkedin') . '" target="_blank"><img alt="LinkedIn" src="' . DIR_WS_IMAGES . 'icons/linkedin.png" /></a>';
      }
    ?>
    <span class="float-right">
      <img style="border:0;width:88px;height:31px" alt="HTML5 Validator" src="http://upload.wikimedia.org/wikipedia/commons/b/bb/W3C_HTML5_certified.png">
      <img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" />
    </span>
  </p>       
</div>
<!--footer.php end-->