<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ( is_numeric($_GET[$lC_Template->getModule()]) ) {
  $oInfo = new lC_ObjectInfo(lC_Orders_Admin::getInfo($_GET[$lC_Template->getModule()]));
}
?>
<!-- Main content -->
<section role="main" id="main">
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Language->get('text_edit_order') . ': ' . $_GET[$lC_Template->getModule()]; ?></h1>
    <?php
      if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
        echo $lC_MessageStack->get($lC_Template->getModule());
      }
    ?>
  </hgroup>
  <div class="with-padding-no-top">
    <form name="order" id="order" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('orders_id') : '') . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="order_tabs" class="side-tabs">
        <ul class="tabs">
          <li class="active"><?php echo lc_link_object('#section_orders_summary', $lC_Language->get('section_orders_summary')); ?></li>
          <li><?php echo lc_link_object('#section_orders_products', $lC_Language->get('section_orders_products')); ?></li>
          <li><?php echo lc_link_object('#section_orders_customer', $lC_Language->get('section_orders_customer')); ?></li>
          <li><?php echo lc_link_object('#section_orders_shipping', $lC_Language->get('section_orders_shipping')); ?></li>
          <li><?php echo lc_link_object('#section_orders_messages', $lC_Language->get('section_orders_messages')); ?></li>
          <li><?php echo lc_link_object('#section_orders_fraud', $lC_Language->get('section_orders_fraud')); ?></li>
          <li><?php echo lc_link_object('#section_orders_payments', $lC_Language->get('section_orders_payments')); ?></li>
        </ul>
        <div class="clearfix tabs-content" id="orders_sections">
          <div id="section_orders_summary">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-user icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_customer'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-card icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_payment'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-info-round icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_info'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-dropbox icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_shipping'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
            </div>
            <div class="columns">
              <div class="twelve-columns">
                <div class="field-drop-tabs field-drop-tabs-no-left button-height black-inputs">
                  <div class="columns">
                    <div class="six-columns twelve-columns-mobile new-row-mobile">Left</div>
                    <div class="six-columns twelve-columns-mobile new-row-mobile ">Right</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns with-padding">
              <div class="twelve-columns no-margin-bottom large-pull-top">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-list icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_products_ordered'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
            </div>
            <div class="columns with-padding">
              <div class="twelve-columns large-pull-top">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-read icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_recent_messages'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
            </div>
          </div>
          <div id="section_orders_products">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_customer">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_shipping">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_messages">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_fraud">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_payments">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
        </div>
      </div>
      <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon red-gradient glossy">
                  <span class="icon-cross"></span>
                </span>
                <span class="button-text"><?php echo $lC_Language->get('button_cancel'); ?></span>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="$(\'#order\').submit();'); ?>">
                <span class="button-icon green-gradient glossy">
                  <span class="icon-download"></span>
                </span>
                <span class="button-text"><?php echo $lC_Language->get('button_save'); ?></span> 
              </a>&nbsp;
            </p>
          </div>
          <div id="floating-button-container-title" class="hidden">
            <p class="white big-text small-margin-top"><?php echo $lC_Language->get('text_edit_order') . ': '/* . $lC_ObjectInfo->get('orders_id')*/; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End Main content -->