<?php
  /*
  $Id: login.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */
  if ($lC_MessageStack->size('login') > 0) {
    echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('login', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
  }
?>
<!--LOGIN SECTION STARTS-->
<div class="full_page">
  <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  <!--CHECKOUT STEPS STARTS-->
  <div class="checkout_steps">
    <div id="checkout-step-login">
      <form id="login" name="login" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'login=process', 'SSL'); ?>" method="post">
        <div class="short-code-column one-half">
          <h3><?php echo $lC_Language->get('login_new_customer_heading'); ?></h3>
          <p><?php echo $lC_Language->get('login_new_customer_text'); ?></p>
          <div class="buttons-set">
            <a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create', 'SSL'); ?>">
              <button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
            </a>
          </div>
        </div>
        <div class="short-code-column one-half column-last">
          <fieldset>
            <h3><?php echo $lC_Language->get('login_returning_customer_heading'); ?></h3>
            <p><?php echo $lC_Language->get('login_returning_customer_text'); ?></p>
            <ul class="form-list">
              <li>
                <label class="required" for="email_address"><em>*</em><?php echo $lC_Language->get('field_customer_email_address'); ?></label>
                <div class="input-box">
                  <input type="text" name="email_address" id="email_address" class="input-text">
                </div>
              </li>
              <li>
                <label class="required" for="password"><em>*</em><?php echo $lC_Language->get('field_customer_password'); ?></label>
                <div class="input-box">
                  <input type="password" name="password" id="password" class="input-text">
                </div>
              </li>
            </ul>
            <div style="clear: both;">&nbsp;</div>
            <div class="buttons-set">
              <span class="buttonLeft"><a onclick="$('#login').submit();" class="noDecoration"><button class="button brown_btn" type="submit"><?php echo $lC_Language->get('button_sign_in'); ?></button></a></span>
              <span class="buttonRight"><?php echo sprintf($lC_Language->get('login_returning_customer_password_forgotten'), lc_href_link(FILENAME_ACCOUNT, 'password_forgotten', 'SSL')); ?></span>
            </div>
          </fieldset>
        </div>
      </form>
    </div>
  </div>
  </div>
<!--LOGIN SECTION ENDS-->