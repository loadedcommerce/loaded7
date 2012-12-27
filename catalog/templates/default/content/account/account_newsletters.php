<?php
/*
  $Id: account_newsletters.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--ACCOUNT NEWSLETTERS SECTION STARTS-->
  <div class="full_page">
    <!--ACCOUNT NEWSLETTERS CONTENT STARTS-->
    <div class="content">
      <!-- Need to get with Scott on class code to support newsletter data for customer -->
      <form name="account_newsletter" id="account_newsletter" action="#" method="post">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <!--ACCOUNT NEWSLETTERS SELECTIONS STARTS-->    
        <div id="accoutnNewsletters" class="borderPadMe">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="30"><?php echo lc_draw_checkbox_field('newsletter_general', '1', $Qnewsletter->value('customers_newsletter')); ?></td>
              <td><b><?php echo lc_draw_label($lC_Language->get('newsletter_general'), 'newsletter_general'); ?></b></td>
            </tr>
            <tr>
              <td width="30">&nbsp;</td>
              <td><?php echo $lC_Language->get('newsletter_general_description'); ?></td>
            </tr>
          </table>
        </div>
        <div style="clear:both;">&nbsp;</div>
        <!--ACCOUNT NEWSLETTERS SELECTIONS ENDS-->    
        <!--ACCOUNT NEWSLETTERS ACTIONS STARTS-->    
        <div id="accountNewslettersActions" class="action_buttonbar">
          <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span> 
          <span class="buttonRight"><a onclick="$('#account_newsletter').submit();"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_update'); ?></button></a></span>
        </div> 
        <div style="clear:both;"></div>
        <!--ACCOUNT NEWSLETTERS ACTIONS ENDS-->
      </div>
      </form>
    </div>
    <!--ACCOUNT NEWSLETTERS CONTENT ENDS-->
  </div>
<!--ACCOUNT NEWSLETTERS SECTION ENDS-->