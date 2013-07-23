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
        <div style="margin:-15px; padding:10px 20px 14px 10px; width:100%; height:32px; margin-top:5px;">
          <a id="qrcode-tooltip">
            <p style="width:32px;float:left;cursor:pointer;">
              <img src="images/icons/qr-icon.png" border="0" class="Click to Generate QR Code" /> 
            </p>
          </a>
          <p style="float:left; padding:10px 0 0px 10px; font-weight:bold;">QR Code for Current URL</p> 
        </div>
       <div class="clear-both"></div>
<!-- QR Code EOf-->
      </div>
    </div>
    <address>
      Copyright &copy; <?php echo @date("Y"); ?> Loaded Commerce <img src="templates/default/images/payment_info.jpg"/>
    </address>
  </footer>
</div>           
<!-- QR Code -->
   <div id="qr-message">
    <a class="close-qr" title="Hide message" onclick="$('#qr-message').hide('500');"><span style="color:#fff;">X</span></a>
    <?php 
    require('./includes/classes/BarcodeQR.php');
    $BarcodeQR = new BarcodeQR();
    $qrcode_url = (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . $_SERVER['REQUEST_URI'];

    if ($_SESSION['lC_Customer_data']['email_address']) {     
      $qrcode_url_add = (stristr($qrcode_url, "?") ? '&' : '?') . $lC_Session->getName() . '=' . $lC_Session->getID() . '&email=' . $_SESSION['lC_Customer_data']['email_address'];
    } 

    $BarcodeQR->url($qrcode_url . $qrcode_url_add);
    if ($lC_Customer->isLoggedOn() === true) {
      $_SESSION['email_passthru'] = $_SESSION['lC_Customer_data']['email_address'];
      $BarcodeQR->draw(230, 'includes/work/qrcode/c' .  $lC_Customer->id . '.png');
      echo '<strong>QR Code</strong><br /><br /><img src="includes/work/qrcode/c' . $lC_Customer->id . '.png" /><br /><br /><strong>Current URL</strong><p>' . $qrcode_url . $qrcode_url_add . '</p>';
    } else {
      $BarcodeQR->draw(230, 'includes/work/qrcode/g' .  $lC_Session->getID() . '.png');
      echo '<strong>QR Code</strong><br /><br /><img src="includes/work/qrcode/g' . $lC_Session->getID() . '.png" /><br /><br /><strong>Current URL</strong><p>' . $qrcode_url . $qrcode_url_add . '</p>';
    }
    ?>
    </div>
    <script>
    $("#qrcode-tooltip").click(function() {
      $("#qr-message").show("500");
    });
    </script>   
    <!-- QR Code EOF -->
<!--footer.php end-->