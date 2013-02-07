<?php
/*
  $Id: info_contact.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('contact') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('contact', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/info/info_contact.php start-->
<div id="infoContact" class="full_page">
  <form name="contact" action="<?php echo lc_href_link(FILENAME_INFO, 'contact=process'); ?>" method="post" enctype="multipart/form-data" id="contact">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <div id="errDiv" class="short-code msg error" style="margin-bottom:10px; display:none;"> <span><?php echo $lC_Language->get('form_validation_error'); ?></span> </div>
      <div class="single-bg">
        <div class="embed-form short-code-column margin-bottom">
          <h3><?php echo $lC_Language->get('contact_store_address_title');?></h3>
          <?php echo nl2br(STORE_NAME_ADDRESS); ?>
        </div>
      </div>
      <div class="single-bg">
        <div class="embed-form short-code-column margin-bottom">
        <?php 
        $contact_name = '';
        $contact_email = '';
        $contact_button = '<a onclick="$(\'#contact\').submit();" class="noDecoration"><button class="button brown_btn" type="submit">' . $lC_Language->get('button_continue') . '</button></a>';
        if (isset($_GET['contact']) && ($_GET['contact'] == 'success')) {
            echo $lC_Language->get('contact_email_sent_successfully');
            $contact_button = '<a href="' . lc_href_link(FILENAME_DEFAULT) . '" class="noDecoration"><button class="button brown_btn" type="button">' . $lC_Language->get('button_continue') . '</button></a>';
        } else {
            if (isset($_SESSION['lC_Customer_data'])) {
                $contact_name = $_SESSION['lC_Customer_data']['first_name'] . ' ' . $_SESSION['lC_Customer_data']['last_name'];
                $contact_email = $_SESSION['lC_Customer_data']['email_address'];
            }
        ?>
          <ul id="personal_details">
            <li><?php echo lc_draw_label('', 'name', null, false) . ' ' . lc_draw_input_field('name', $contact_name , 'placeholder="' . $lC_Language->get('contact_name_title') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('contact_name_title') . '\'" holder="' . $lC_Language->get('contact_name_title') . '" class="txt" style="width:99%;"'); ?></li>
            <li><?php echo lc_draw_label('', 'email', null, '', false) . ' ' . lc_draw_input_field('email', $contact_email, 'placeholder="' . $lC_Language->get('contact_email_address_title') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('contact_email_address_title') . '\'" holder="' . $lC_Language->get('contact_email_address_title') . '" class="txt" style="width:99%;"');?></li>
            <li><?php echo lc_draw_label('', 'inquiry', null, false) . ' ' . lc_draw_textarea_field('inquiry', null, 40, 8, 'placeholder="' . $lC_Language->get('contact_inquiry_title') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('contact_inquiry_title') . '\'" holder="' . $lC_Language->get('contact_inquiry_title') . '"  holder="text" class="txt" style="width:99%;"'); ?></li>
            <!--li>Place for Captcha</li -->
          </ul>
        <?php
        }
        ?>
        </div>
      </div>
      <div style="clear:both;"></div>
      <div id="accountEditActions" class="action_buttonbar"  style="margin-top:10px;"><span class="buttonRight"><?php echo $contact_button;?></span></div>
      <div style="clear:both;"></div>
    </div>
  </form>
</div>
<!--content/info/info_contact.php end-->