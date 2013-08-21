<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: info_contact.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/info/info_contact.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <div class="row large-margin-top">
      <div class="col-sm-4 col-lg-4">
        <div class="well">
          <address class="no-margin-bottom">
          <strong><?php echo STORE_NAME; ?></strong><br>
            <?php echo nl2br(STORE_NAME_ADDRESS); ?>
          </address>          
        </div>
      </div>
      <div class="col-sm-8 col-lg-8">
        <?php 
        if ( $lC_MessageStack->size('contact') > 0 ) echo '<div class="message-stack-container alert alert-danger">' . $lC_MessageStack->get('contact') . '</div>' . "\n";  
        if (isset($_GET['success']) && $_GET['success'] != NULL) echo '<div class="message-success-container alert alert-success"><img class="margin-right" src="images/icons/success.gif">' . preg_replace('/[^a-zA-Z0-9]\'\.\,/', '', $_GET['success']) . '</div>' . "\n"; 
        ?>
        <form role="form" id="contact" name="contact" class="row-fluid" action="<?php echo lc_href_link(FILENAME_INFO, 'contact=process', 'SSL'); ?>" method="post" enctype="multipart/form-data">
          <div class="form-group"><label class="sr-only"></label><input class="form-control" type="text" name="name" value="" placeholder="<?php echo $lC_Language->get('contact_name_title'); ?>"></div>
          <div class="form-group"><label class="sr-only"></label><input class="form-control" type="text" name="email" value="" placeholder="<?php echo $lC_Language->get('contact_email_address_title'); ?>"></div>
          <div class="form-group"><label class="sr-only"></label><textarea class="form-control" name="inquiry" rows="5" cols="25" placeholder="<?php echo $lC_Language->get('contact_inquiry_title'); ?>"></textarea></div>    
        </form>
      </div>
    </div>
  </div>                                                                                                                                                
  <div class="btn-set small-margin-top clearfix">
    <button class="pull-right btn btn-lg btn-primary" onclick="$('#contact').submit();" type="button"><?php echo $lC_Language->get('button_send_message'); ?></button>
    <a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="pull-left btn btn-lg btn-default" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
  </div>    
</div>
<!--content/info/info_contact.php end-->