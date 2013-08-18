<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: tell_a_friend.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/info/tell_a_friend.php start-->
<div class="row-fluid">
  <div class="span12">
    <h1><?php echo $lC_Language->get('products_tell_a_friend_title');?></h1>
    <div class="row">
      <div class="span4 large-padding-left">
        <div class="thumbnail align-center large-padding-top">
          <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="content-tell-a-friend-image-src"', 'small')); ?>
          <div class="caption">
            <h3 style="line-height:1.1;">
              <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()); ?>
            </h3>
            <p class=""><?php echo (strlen($lC_Product->getDescription()) > 60 ) ? substr(lc_clean_html($lC_Product->getDescription()), 0, 57) . '...' : lc_clean_html($lC_Product->getDescription()); ?></p>
            <div class="row-fluid">
              <div class="span6">
                <p class="lead"><?php echo $lC_Product->getPriceFormated(); ?></p>
              </div>
              <div class="span6 no-margin-left">
                <a href="<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add'); ?>">
                  <button class="btn btn-success btn-block" type="button">Buy Now</button>
                </a>
              </div>
            </div>
          </div>
        </div>    
      </div>
      <div class="span8">
        <?php 
        if ( $lC_MessageStack->size('tell_a_friend') > 0 ) echo '<div class="message-stack-container alert alert-error">' . $lC_MessageStack->get('tell_a_friend') . '</div>' . "\n"; 
      //  if (isset($_GET['contact']) && $_GET['contact'] == 'success') echo '<div class="message-success-container alert-success">' . $lC_Language->get('contact_email_sent_successfully') . '</div>' . "\n"; 
        ?>
        <form name="tell_a_friend" id="tell_a_friend" action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'tell_a_friend&' . $lC_Product->getKeyword() . '&action=process'); ?>" method="post">
          <h4><?php echo $lC_Language->get('customer_details_title'); ?></h4>
          <label><?php echo $lC_Language->get('field_tell_a_friend_customer_name'); ?></label><input class="span12" type="text" name="from_name" value=""><br>
          <label><?php echo $lC_Language->get('field_tell_a_friend_customer_email_address'); ?></label><input class="span12" type="text" name="from_email_address" value=""><br>
          <h4><?php echo $lC_Language->get('friend_details_title'); ?></h4>
          <label><?php echo $lC_Language->get('field_tell_a_friend_friends_name'); ?></label><input class="span12" type="text" name="to_name" value=""><br>
          <label><?php echo $lC_Language->get('field_tell_a_friend_friends_email_address'); ?></label><input class="span12" type="text" name="to_email_address" value=""><br>
          <h4><?php echo $lC_Language->get('tell_a_friend_message'); ?></h4>
          <label></label><textarea class="span12" name="message" rows="8" cols="40"></textarea><br>    
        </form>
      </div>
    </div>
  </div>                                                                                                                                                
  <div class="button-set">
    <button class="pull-right btn btn-lg btn-success" onclick="$('#tell_a_friend').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
    <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()); ?>"><button class="pull-left btn btn-lg btn-info" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
  </div>    
</div>
<!--content/products/tell_a_friend.php end-->