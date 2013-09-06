<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: account_newsletters.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/account/account_newsletters.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <form role="form" class="form-inline" name="account_newsletter" id="account_newsletter" action="#" method="post">
      <div class="well">
        <div class="checkbox">
          <label class=""><?php echo lc_draw_checkbox_field('newsletter_general', '1', $Qnewsletter->value('customers_newsletter'), null, null, null); ?>&nbsp;<?php echo $lC_Language->get('newsletter_general'); ?></label>
        </div>
        <p class="margin-top normal"><?php echo $lC_Language->get('newsletter_general_description'); ?></p>
      </div>
    </form>
    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" onclick="$('#account_newsletter').submit();" type="button"><?php echo $lC_Language->get('button_delete'); ?></button>
      <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-left btn btn-lg btn-default" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
    </div>     
  </div>
</div>  
<!--content/account/account_newsletters.php end-->