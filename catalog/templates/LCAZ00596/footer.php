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



<div id="sub-footer" class="mid-margin-top">
<div class="container">
 <div class="container_controller">
  <div class="row">
  
    <div class="col-sm-2 col-lg-2 firstchild">
      <h4 class="line3 center standard-h4title"><span>Information</span></h4>
       <ul>
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'privacy', 'AUTO'); ?>"><?php echo $lC_Language->get('text_privacy'); ?></a></li>     
          
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'shipping', 'AUTO'); ?>"><?php echo 'Returns and exchanges'; ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'SSL'); ?>"><?php echo 'About Us';?></a></li>
                                                   
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'conditions', 'AUTO'); ?>"><?php echo 'FAQ'; ?></a></li>
                                                       
        </ul>     
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
  
    <div class="col-xs-10 col-sm-3 col-lg-3">
       <h4 class="line3 center standard-h4title"><span>Contact Us</span></h4>       
      <address class="margin-left">
        <span class="iconcp"></span>
        <span class="address_part"> <?php echo nl2br(STORE_NAME_ADDRESS); ?></span>
      </address>
    </div>

    <div class="col-sm-2 col-lg-2">
      <h4 class="line3 center standard-h4title"><span>Your Account</span></h4>
       <ul>
        <li>          
          <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'); ?>"><?php echo 'Login' ?></a></li>                                              
          <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create', 'SSL'); ?>"><?php echo 'Register' ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password_forgotten', 'SSL'); ?>"><?php echo 'Forgot your password?' ?></a></li>   
        </ul>     
    </div>

    <div class="col-sm-2 col-lg-2 ">
      <h4 class="line3 center standard-h4title"><span>Follow Us</span></h4>
      <div> 15,237 people Online</div>
      
      <div class="thumbButtons">  
             <div class="az_social_icon icon_facebook col-xs-4 col-sm-4 col-lg-4"><a onclick="window.open('http://www.facebook.com/algozone');"></a></div> 
             <div class="az_social_icon icon_twitter  col-xs-4 col-sm-4 col-lg-4"><a onclick="window.open('http://www.twitter.com/Algozone');"></a></div>     
             <div class="az_social_icon icon_flickr  col-xs-4 col-sm-4 col-lg-4"><a onclick="window.open('http://www.youtube.com/user/AlgoZoneCom');"></a></div> 
      </div> 
      <div class="thumbButtons">  
             <div class="az_social_icon icon_linkedin col-xs-4 col-sm-4 col-lg-4"><a onclick="window.open('http://www.twitter.com/Algozone');" ></a></div> 
             <div class="az_social_icon icon_rss  col-xs-4 col-sm-4 col-lg-4"><a onclick="window.open('http://www.twitter.com/Algozone');"></a></div>     
             <div class="az_social_icon icon_myspace  col-xs-4 col-sm-4 col-lg-4"><a onclick="window.open('http://www.twitter.com/Algozone');"></a></div> 
      </div>   
            
    </div>               

  </div>
</div>  
</div>
</div>

<div id="footer">
<div class="container">
<div class="container_controller">
  <div class="row">

    <div class="col-sm-12 col-lg-12">
      <div class="pull-left">&copy; Copyright <?php echo date("Y"); ?>.  Designed by <a  onclick="window.open('http://www.algozone.com');" >Algozone.com.</a><br />
      All Rights Reserved. <a href=""><?php echo STORE_NAME; ?></a>
      </div>
      
      
      <div class="pull-right"> <a class="payment_by" href="#"> </a> </div>
    </div>    

  </div>
   </div>
</div>  
</div>

<!--footer.php end-->