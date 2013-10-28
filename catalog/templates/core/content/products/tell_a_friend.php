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
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Language->get('products_tell_a_friend_title');?></h1>
    <div class="row">
      <div class="col-sm-4 col-lg-4 large-padding-left">
        <div class="thumbnail align-center large-padding-top">
          <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="img-responsive content-tell-a-friend-image-src"', 'small')); ?>
          <div class="caption">
            <h3 style="line-height:1.1;">
              <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()); ?>
            </h3>
            <p class=""><?php echo (strlen($lC_Product->getDescription()) > 60 ) ? substr(lc_clean_html($lC_Product->getDescription()), 0, 57) . '...' : lc_clean_html($lC_Product->getDescription()); ?></p>
            <div class="row">
              <div class="col-sm-6 col-lg-6">
                <p class="lead"><?php echo $lC_Product->getPriceFormated(); ?></p>
              </div>
              <div class="col-sm-6 col-lg-6 no-margin-left">
                <button class="btn btn-success btn-block" onclick="window.location.href='<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add'); ?>'" type="button"><?php echo $lC_Language->get('button_buy_now'); ?></button>
              </div>
            </div>
          </div>
        </div>    
      </div>
      <div class="col-sm-8 col-lg-8">
        <?php 
        if ( $lC_MessageStack->size('tell_a_friend') > 0 ) echo '<div class="message-stack-container alert alert-danger">' . $lC_MessageStack->get('tell_a_friend') . '</div>' . "\n"; 
        if (isset($_GET['success']) && $_GET['success'] != NULL) echo '<div class="message-success-container alert alert-success"><img class="margin-right" src="images/icons/success.gif">' . preg_replace('/[^a-zA-Z0-9]\'\.\,/', '', $_GET['success']) . '</div>' . "\n"; 
        ?>
        <form role="form" class="" name="tell_a_friend" id="tell_a_friend" action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'tell_a_friend&' . $lC_Product->getKeyword() . '&action=process'); ?>" method="post">
          <h4 class="no-margin-top"><?php echo $lC_Language->get('customer_details_title'); ?></h4>
          <div class="form-group"><label class="sr-only"></label><input class="form-control" type="text" name="from_name" value="" placeholder="<?php echo $lC_Language->get('field_tell_a_friend_customer_name'); ?>"></div>
          <div class="form-group"><label class="sr-only"></label><input class="form-control" type="text" name="from_email_address" value="" placeholder="<?php echo $lC_Language->get('field_tell_a_friend_customer_email_address'); ?>"></div>
          <h4><?php echo $lC_Language->get('friend_details_title'); ?></h4>
          <div class="form-group"><label class="sr-only"></label><input class="form-control" type="text" name="to_name" value="" placeholder="<?php echo $lC_Language->get('field_tell_a_friend_friends_name'); ?>"></div>
          <div class="form-group"><label class="sr-only"></label><input class="form-control" type="text" name="to_email_address" value="" placeholder="<?php echo $lC_Language->get('field_tell_a_friend_friends_email_address'); ?>"></div>
          <h4><?php echo $lC_Language->get('tell_a_friend_message'); ?></h4>
          <div class="form-group"><label class="sr-only"></label><textarea class="form-control" name="message" rows="8" cols="40"></textarea></div>   
        </form>
      </div>
    </div>
  </div>                                                                                                                                                
  <div class="button-set clearfix large-margin-bottom large-margin-left large-margin-right">
    <button class="pull-right btn btn-lg btn-primary" onclick="$('#tell_a_friend').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
    <button class="pull-left btn btn-lg btn-default" onclick="window.location.href='<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()); ?>'" type="button"><?php echo $lC_Language->get('button_back'); ?></button>
  </div>    
</div>
<!--content/products/tell_a_friend.php end-->