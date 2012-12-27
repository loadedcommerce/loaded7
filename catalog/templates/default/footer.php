<?php
  /*
  $Id: footer.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--Footer Starts-->
<div class="footer-container">
  <footer class="wrapper">
    <!--Newsletter_subscribe Starts-->
    <div class="subscribe_block">
      <div class="find_us">
        <h3>Find us on</h3>
        <a class="twitter" href="http://twitter.com/loadedcommerce"></a> <a class="facebook" href="http://facebook.com/loadedcommerce"></a> <a class="rss" href="http://blog.loaded7.com/feed/"></a> 
      </div>
      <div class="subscribe_nl">
        <h3>Subscribe to our Newsletter</h3>
        <small>Instant wardrobe updates, new arrivals, fashion news, don't miss a beat - sign up to our newsletter now.</small>
        <form id="newsletter" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create', 'SSL'); ?>" method="post">
          <input type="text" class="input-text" value="Enter your email" title="Enter your email" id="newsletter" name="email" onFocus="if (this.value == 'Enter your email') { this.value = ''; }">
          <button class="button" title="" type="submit"></button>
        </form>
      </div>
    </div>
    <!--Newsletter_subscribe Ends-->
    <ul class="footer_links">
      <li><span>New Arrivals</span>
        <ul>
        <?php
          include_once('includes/classes/products.php');
          $lC_Products = new lC_Products();
          $Qlisting = $lC_Products->execute();
          while ($Qlisting->next()) {
            $lC_Product = new lC_Product($Qlisting->valueInt('products_id'));
            echo '<li>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()) . '</li>';
          }
        ?>
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
      </div>
    </div>
    <address>
      Copyright &copy; 2012 Leisure. All Rights Reserved. <img src="templates/default/images/payment_info.jpg"/>
    </address>
  </footer>
</div>
<!--Footer Ends-->