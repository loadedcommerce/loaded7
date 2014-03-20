<?php
/**
  @package    catalog::templates
  @author     AlgoZone, Inc
  @copyright  Copyright 2013 AlgoZone, Inc
  @copyright  Portions Copyright Loaded Commerce, LLC and osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: footer.php v1.0 2013-08-08 datazen $
*/
?>
<!--footer.php start-->

<div id="sub-footer">
<div class="container">
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
        <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'SSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'shipping', 'AUTO'); ?>"><?php echo $lC_Language->get('text_shipping_returns'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'privacy', 'AUTO'); ?>"><?php echo $lC_Language->get('text_privacy'); ?></a></li>                                              
        <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'conditions', 'AUTO'); ?>"><?php echo $lC_Language->get('text_terms_conditions'); ?></a></li>
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
	
<?php
/*	
    <div class="col-sm-3 col-lg-3 large-margin-bottom">
      <h4 class="line3 center standard-h4title"><span>Our Office</span></h4>
      <address class="margin-left">
        <strong><?php echo STORE_NAME; ?></strong><br>
        <?php echo nl2br(STORE_NAME_ADDRESS); ?><br>
      </address>
      <!-- QR Code -->
      <?php echo $lC_Template->getQRCode(); ?>
    </div>               
*/
?>

	<div class="col-sm-3 col-lg-3 large-margin-bottom">
		<h4 class="line3 center standard-h4title"><span>Follow Us</span></h4>
	    <?php
	      if ($lC_Template->getBranding('social_facebook_page') != '') {
	        echo '<a href="' . $lC_Template->getBranding('social_facebook_page') . '" target="_blank"><img alt="Facebook" src="' . DIR_WS_IMAGES . 'icons/facebook.png" /></a>';
	      } else {
	    ?>
			<a href="https://www.facebook.com/Algozone"><img alt="Facebook" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>fb_icon.png" /></a>   
	    <?php
	      }
	      if ($lC_Template->getBranding('social_tweeter') != '') {
	        echo '<a href="' . $lC_Template->getBranding('social_twitter') . '" target="_blank"><img alt="Twitter" src="' . DIR_WS_IMAGES . 'icons/twitter_bird.png" /></a>';
	      } else {
	    ?>
			<a href="https://twitter.com/algozone"><img alt="Twitter" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>tw_icon.png" /></a> 
	    <?php
	      }
	      if ($lC_Template->getBranding('social_pinterest') != '') {
	        echo '<a href="' . $lC_Template->getBranding('social_pinterest') . '" target="_blank"><img alt="Pinterest" src="' . DIR_WS_IMAGES . 'icons/pinterest.png" /></a>';
	      } else {
	    ?>
	      <a href="http://www.flickr.com/"><img alt="flickr" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>flkr_icon.png" /></a>
	    <?php
	      }	      
	      if ($lC_Template->getBranding('social_google_plus') != '') {
	        echo '<a href="' . $lC_Template->getBranding('social_google_plus') . '" target="_blank"><img alt="Google+" src="' . DIR_WS_IMAGES . 'icons/google_plus.png" /></a>';
	      } else {
	    ?>
	      <a href=""><img alt="RSS feed" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>rss_icon.png" /></a>
	    <?php
	      }	
	      if ($lC_Template->getBranding('social_youtube') != '') {
	        echo '<a href="' . $lC_Template->getBranding('social_youtube') . '" target="_blank"><img alt="Youtube" src="' . DIR_WS_IMAGES . 'icons/youtube.png" /></a>';
	      } else {
	    ?>
			<a href=""><img alt="Chat" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>ch_icon.png" /></a> 
	    <?php
	      }
	      if ($lC_Template->getBranding('social_linkedin') != '') {
	        echo '<a href="' . $lC_Template->getBranding('social_linkedin') . '" target="_blank"><img alt="LinkedIn" src="' . DIR_WS_IMAGES . 'icons/linkedin.png" /></a>';
	      } else {
	    ?>
			<a href=""><img alt="" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>st_icon.png" /></a>
	    <?php
	      }
	    ?>
      <!-- QR Code -->
      <?php echo $lC_Template->getQRCode(); ?>
      
	</div>      

  </div>
</div>  
</div>

<div id="footer">
<div class="container">
  <div class="row">

    <div class="col-sm-12 col-lg-12">
      <div class="pull-left">&copy; <?php echo date("Y") . ' ' . STORE_NAME; ?></div>
      <div class="pull-right">Loaded Commerce © Template Designed by <a href="http://www.algozone.com" target="_blank" title="osCommerce Template Designed by AlgoZone.com">AlgoZone.com</a> </div>
    </div>    

  </div>
</div>  
</div>

<!--footer.php end-->