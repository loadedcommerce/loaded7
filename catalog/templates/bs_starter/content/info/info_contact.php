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
<div class="row-fluid">
  <div class="span12">
    <h3><?php echo $lC_Language->get('contact_store_address_title');?></h3>
    <?php 
    if ( $lC_MessageStack->size('contact') > 0 ) echo '<div class="message-stack-container alert alert-error">' . $lC_MessageStack->get('contact') . '</div>' . "\n"; 
    if (isset($_GET['contact']) && $_GET['contact'] == 'success') echo '<div class="message-success-container alert alert-success">' . $lC_Language->get('contact_email_sent_successfully') . '</div>' . "\n"; 
    ?>
    <form id="contact" name="contact" class="row-fluid" action="<?php echo lc_href_link(FILENAME_INFO, 'contact=process', 'SSL'); ?>" method="post" enctype="multipart/form-data">
      <label><?php echo $lC_Language->get('contact_name_title'); ?></label><input class="span12" type="text" name="name" value=""><br>
      <label><?php echo $lC_Language->get('contact_email_address_title'); ?></label><input class="span12" type="text" name="email" value=""><br>
      <label><?php echo $lC_Language->get('contact_inquiry_title'); ?></label><textarea class="span12" name="inquiry" rows="5" cols="25"></textarea><br>    
    </form>
  </div>                                                                                                                                                
  <div class="button-set">
    <button class="pull-right btn btn-large btn-success" onclick="$('#contact').submit();" type="button"><?php echo $lC_Language->get('button_send_message'); ?></button>
    <a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="pull-left btn btn-large btn-info" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
  </div>    
</div>
<!--content/info/info_contact.php end-->