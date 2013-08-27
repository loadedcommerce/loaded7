<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
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

    <div class="col-sm-3 col-lg-3 large-margin-bottom">
      <h4 class="line3 center standard-h4title"><span>Our Office</span></h4>
      <address class="margin-left">
        <strong><?php echo STORE_NAME; ?></strong><br>
        <?php echo nl2br(STORE_NAME_ADDRESS); ?><br>
      </address>

      <!-- QR Code -->
      <?php echo $lC_Template->getQRCode(); ?>
    </div>               
    <div class="col-sm-12 col-lg-12">
      <div class="pull-left">&copy; <?php echo date("Y") . ' ' . STORE_NAME; ?></div>
      <div class="pull-right"><?php echo $lC_Language->get('footer'); ?></div>
    </div>    
  </div>
  <p>&nbsp;</p>
</div>
<!--footer.php end-->