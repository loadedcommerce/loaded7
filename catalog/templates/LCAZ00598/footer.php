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



<div id="sub-footer">
  <div class="container" style="width:100%;">
    <div class="container_controller">
      <div class="row">
        <div class="col-sm-3 col-lg-3 firstchild">
          <h4 class="line3 center standard-h4title"><span>About Us</span></h4>
          <div>
            Lorem Ipsum dolor simply dummys text  in printing and typesetting for
            industry. Lorem Ipsum has been the
            industry's standard dummy text ever
            since the 1500s, when an unknown.
          </div>
          <address class="margin-left">
            <span class="iconcp"></span>
            <span class="address_part"> <?php echo nl2br(STORE_NAME_ADDRESS); ?></span>
          </address>
          <?php
            if ($lC_Template->getBranding('sales_email') != '' || $lC_Template->getBranding('sales_phone') != '') {
              echo '<div>';
              if ($lC_Template->getBranding('sales_email') != '') {
                echo $lC_Template->getBranding('sales_email');
              }
              if ($lC_Template->getBranding('sales_phone') != '') {
                echo ' | ' . $lC_Template->getBranding('sales_phone');
              }
              echo '</div>';
            }
          ?>
        </div>

        <div class="col-sm-3 col-lg-3 createaccount">
          <h4 class="line3 center standard-h4title"><span>Create Account</span></h4>

          <div class="signuppart">
            Sign up for free gifts and items <br />
            Join us to avail more discounts.
          </div>
          <form id="newsletter" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create', 'SSL'); ?>" method="post">
            <input type="text" class="input-text" value="" placeholder="<?php echo $lC_Language->get('text_enter_your_email'); ?>" id="newsletter" name="email">
            <button class="button" title="" type="submit"></button>
          </form>
        </div> 

        <div class="col-sm-3 col-lg-3">
          <h4 class="line3 center standard-h4title"><span>Information</span></h4>
          <ul>
            <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'SSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
            <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'shipping', 'AUTO'); ?>"><?php echo $lC_Language->get('text_shipping_returns'); ?></a></li>
            <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'privacy', 'AUTO'); ?>"><?php echo $lC_Language->get('text_privacy'); ?></a></li>                                              
            <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'conditions', 'AUTO'); ?>"><?php echo $lC_Language->get('text_terms_conditions'); ?></a></li>
            <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'sitemap', 'AUTO'); ?>"><?php echo $lC_Language->get('text_sitemap'); ?></a></li>   

          </ul>     
        </div>

        <div class="col-sm-3 col-lg-3 sicon">
          <h4 class="line3 center standard-h4title"><span>Stay Connected</span></h4>
          <ul>
            <li><a onclick="window.open('http://www.facebook.com/algozone');"><span class="az_social_icon icon_facebook">Facebook</span></a> </li>
            <li><a onclick="window.open('http://www.twitter.com/Algozone');"><span class="az_social_icon icon_twitter">Twitter</span></a> </li>
            <li><a onclick="window.open('http://www.youtube.com/user/AlgoZoneCom');"><span class="az_social_icon icon_youtube">Youtube</span></a></li>                                              
            <li><a onclick="window.open('http://www.twitter.com/Algozone');"><span class="az_social_icon icon_blogger">Blogger</span></a></li>
            <li><a onclick="window.open('http://www.twitter.com/Algozone');"><span class="az_social_icon icon_linkdn">Linkedin</span></a></li> 
            <li><a onclick="window.open('http://www.twitter.com/Algozone');"><span class="az_social_icon icon_myspace">Myspace</span></a></li>                                                               
          </ul>    
        </div>               

        <!-- QR Code -->
        <div id="qr-code-container" class="pull-right margin-right margin-bottom">        
          <a id="qrcode-tooltip">
            <span style="cursor:pointer;">
              <img src="images/icons/qr-icon.png" alt="<?php echo $lC_Language->get('text_click_and_scan');?>" style="vertical-align:middle; padding-right:6px;" /><span class="small-margin-left"><?php echo $lC_Language->get('text_click_and_scan');?></span>
            </span>
          </a>
        </div>
        <div id="ShowQRCode"></div> 
      </div>
    </div>  
  </div> 
</div>

<div id="footer">
  <div class="container" style="width:100%;">
    <div class="container_controller">
      <div class="row">
        <div class="col-sm-12 col-lg-12">
          <div class="pull-left">&copy; Copyright <?php echo date("Y"); ?>. Designed by <a href="http://www.algozone.com">Algozone.com.</a> <br />
            All Rights Reserved. <a href=""><?php echo STORE_NAME; ?></a>
          </div>
          <div class="pull-right"> <a class="payment_by" href="#"> </a> </div>
        </div>    
      </div>
    </div>
  </div>  
</div>
<!--footer.php end-->