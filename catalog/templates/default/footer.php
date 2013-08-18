<?php
/**  
*  $Id: footer.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
?>
<!--footer.php start-->
<div class="footer-container">
  <footer class="wrapper">
    <div class="subscribe_block">
      <div class="find_us">
        <h3>Find us on</h3>
        <a class="twitter" href="http://twitter.com/loadedcommerce"></a> <a class="facebook" href="http://facebook.com/loadedcommerce"></a> <a class="rss" href="http://blog.loaded7.com/feed/"></a> 
      </div>
      <div class="subscribe_nl">
        <h3>Subscribe to our Newsletter</h3>
        <small>Instant wardrobe updates, new arrivals, fashion news, don't miss a beat - sign up to our newsletter now.</small>
        <form id="newsletter" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create', 'SSL'); ?>" method="post">
          <input type="text" class="input-text" value="" placeholder="<?php echo $lC_Language->get('text_enter_your_email'); ?>" id="newsletter" name="email">
          <button class="button" title="" type="submit"></button>
        </form>
      </div>
    </div>
    <ul class="footer_links">
      <li><span>New Arrivals</span>
        <ul>
          <?php echo lC_Default::newArrivalsListing(); ?>
        </ul>
      </li>
      <li class="seperator"><span>Brands we sell</span>
        <ul>
          <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, 'manufacturers=5', 'NONSSL'); ?>">Boutique</a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, 'manufacturers=3', 'NONSSL'); ?>">Citizens of Humanity</a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, 'manufacturers=4', 'NONSSL'); ?>">Crew Clothing</a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, 'manufacturers=7', 'NONSSL'); ?>">Mudd & Water</a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, 'manufacturers=6', 'NONSSL'); ?>">Summer</a></li>
        </ul>
      </li>
      <li><span>Customer Service</span>
        <ul>
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'SSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'shipping', 'AUTO'); ?>"><?php echo $lC_Language->get('text_shipping_returns'); ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'privacy', 'AUTO'); ?>"><?php echo $lC_Language->get('text_privacy'); ?></a></li>                                              
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'conditions', 'AUTO'); ?>"><?php echo $lC_Language->get('text_terms_conditions'); ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'sitemap', 'AUTO'); ?>"><?php echo $lC_Language->get('text_sitemap'); ?></a></li>                                                          
        </ul>                                                                                                  
      </li>
      <li><span>My Account</span>
        <ul>
          <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo $lC_Language->get('text_sign_in'); ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password', 'SSL'); ?>"><?php echo $lC_Language->get('text_my_password'); ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL'); ?>"><?php echo $lC_Language->get('text_my_order_history'); ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'); ?>"><?php echo $lC_Language->get('text_my_address_book'); ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'notifications', 'SSL'); ?>"><?php echo $lC_Language->get('text_my_updates_alerts'); ?></a></li>
        </ul>
      </li>
    </ul>
    <div class="footer_customblock">
      <div class="shipping_info">
        <span>introducing</span><br>
        <big>FREE SHIPPING</big><br>
        <small>for purchases above $200</small>
      </div>
      <div class="contact_info">
        <big>1.823.456.7890</big>
        
        <!-- QR Code -->
        <div id="qr-code-container"><?php echo $lC_Template->getQRCode(); ?></div>
        
      </div>
    </div>
    <address>
      <div class="float-left">&copy; <?php echo @date("Y") . ' ' . STORE_NAME; ?> <img src="templates/default/images/payment_info.jpg"/></div>
      <div class="powered-by float-right"><?php echo $lC_Language->get('footer'); ?></div>
    </address>
  </footer>
</div>           
<!--footer.php end-->