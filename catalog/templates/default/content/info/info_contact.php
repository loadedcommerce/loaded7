<?php
/*
  $Id: info_contact.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('contact') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('contact', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--INFO CONTACT SECTION STARTS-->
  <div id="infoContact" class="full_page">
    <!--INFO CONTACT DETAILS STARTS-->
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>        
 		<div class="content">
      <div id="infoContactInner">
        <form name="contact" action="<?php echo lc_href_link(FILENAME_INFO, 'contact=process'); ?>" method="post" enctype="multipart/form-data" id="contact">
        <?php 
          if (isset($_GET['contact']) && ($_GET['contact'] == 'success')) {
            echo '<!--INFO CONTACT SUCCESS STARTS-->';
            echo $lC_Language->get('contact_email_sent_successfully');
            echo '<!--INFO CONTACT SUCCESS ENDS-->';
          } else {
        ?>
        <div id="infoContactForm">
          <div class="short-code-column">
            <!--INFO CONTACT LABELS STARTS-->
            <?php echo $lC_Language->get('contact_store_address_title') . ':<br />' . nl2br(STORE_NAME_ADDRESS); ?><br /><br />
            <label for="name"><?php echo $lC_Language->get('contact_name_title') . '</label> ' . lc_draw_input_field('name', null, ''); ?><br />
            <label for="email"><?php echo  $lC_Language->get('contact_email_address_title') . '</label> ' . lc_draw_input_field('email', null, ''); ?><br />
            <label for="inquiry"><?php echo $lC_Language->get('contact_inquiry_title') . '</label> ' . lc_draw_textarea_field('inquiry', null, 40, 8, 'style="width:50%;"'); ?><br />
           <!--INFO CONTACT LABELS ENDS-->
          </div> 
        </div>
        <?php
          }
        ?>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <!--INFO CONTACT ACTIONS STARTS-->
      <div id="infoContactActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        <span class="buttonRight"><a onclick="$('#contact').submit();"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
      <!--INFO CONTACT ACTIONS ENDS-->
      </form>
    </div>
    <!--INFO CONTACT DETAILS ENDS-->
  </div>
<!--INFO CONTACT SECTION ENDS-->