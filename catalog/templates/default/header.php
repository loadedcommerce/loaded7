<?php
/**  
  $Id: header.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--header.php start-->
<div class="header-container">
  <header class="wrapper clearfix">
    <div class="top_bar clear">
      <div class="mobile-menu" style="display:none;">
        <img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>mobile-menu.png" id="mobile-menu-button" />
      </div>
      <div class="language_switch"> 
        <?php echo $lC_Template->getLanguageSelection(); ?>
      </div>
      <ul id="topLinks" class="top_links">
        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo $lC_Language->get('text_sign_in'); ?></a></li>
        <?php 
        if ($lC_Customer->isLoggedOn()) { 
          echo '<li><a href="' . lc_href_link(FILENAME_ACCOUNT, 'logoff', 'SSL') , '">' . $lC_Language->get('text_sign_out') . '</a></li>';
        } 
        ?>
        <li class="top_links hide-on-mobile"><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'SSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
        <li class="highlight"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>"><?php echo $lC_Language->get('text_checkout'); ?></a></li>
      </ul>
    </div>
    <div id="mobile-menu" style="display:none;">
      <ul class="table_view cells">
        <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, null, 'NONSSL'); ?>"><?php echo $lC_Language->get('text_home'); ?></a></li>
        <?php echo $lC_Template->getTopCategoriesSelection(); ?>
      </ul>
    </div>
    <h1 class="logo"><a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo.png" /></a></h1>
    <?php
    if (!empty($content_left)) {
      ?>
      <button class="button brown_btn browse-catalog" style="display:none;" type="button" id="browse-catalog">Browse Catalog</button>
      <div id="browse-catalog-div" style="display:none;">
        <div id="left_side_nav" class="sideNavBox colLeft" style="display:block;">
          <?php echo $content_left; ?>
        </div>
      </div>
      <?php
    }
    ?>
    <div id="currencySelect"> 
      <?php echo $lC_Template->getCurrencySelectionForm(); ?>
    </div>    
    <div id="mini-cart-container" class="minicart">
      <?php echo $lC_Template->getMiniCartSelection(); ?>
    </div>
    <form id="liveSearchForm" class="header_search clear-both" method="post" action="search">
      <div id="liveSearchContainer" class="form-search">
        <input type="text" class="liveSearchInput" id="search" name="q" value="<?php echo $lC_Language->get('button_search'); ?>..." onfocus="$(this).val('').addClass('liveSearchText');" onblur="$(this).val(' <?php echo $lC_Language->get('button_search'); ?> ...').removeClass('liveSearchText');" autocomplete="off" placeholder="Search">
        <button type="submit" title="Search" disabled="disabled"></button>
      </div>
    </form> 
  </header>
</div> 
<div class="navigation_container">
  <nav>
    <ul id="primaryNav" class="primary_nav">
      <li><a id="navHome" href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_home'); ?></a></li>
      <?php echo $lC_Template->getTopCategoriesSelection(); ?>
    </ul>
  </nav>
</div>
<!--header.php end-->