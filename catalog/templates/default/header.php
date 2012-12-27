<?php
  /*
  $Id: header.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--Header Starts-->
<div class="header-container">
  <header class="wrapper clearfix">
    <div class="top_bar clear">
      <!--Language Switcher Starts-->
      <div class="language_switch"> 
      <?php 
        foreach ($lC_Language->getAll() as $value) {
          echo ' ' . lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('language', 'currency')) . '&language=' . $value['code'], 'AUTO'), $lC_Language->showImage($value['code'])) . ' ';
        }
      ?>
      </div>
      <!--Language Switcher Ends-->
      <!--Top Links Starts-->
      <ul id="topLinks" class="top_links">
        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo $lC_Language->get('text_sign_in'); ?></a></li>
        <?php if ($lC_Customer->isLoggedOn()) { ?>
        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'logoff', 'SSL'); ?>"><?php echo $lC_Language->get('text_sign_out'); ?></a></li>
        <?php } ?>
        <li class="top_links hide-on-mobile"><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'SSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
        <li class="highlight"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, null, 'SSL'); ?>"><?php echo $lC_Language->get('text_checkout'); ?></a></li>
      </ul>
      <!--Top Links Ends-->
    </div>
    <!--Logo Starts-->
    <h1 class="logo"><a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo.png" /></a></h1>
    <!--Logo Ends-->
    
    <!--Mobile Menu Starts-->
    <div class="mobile-menu" style="display:none;">
      <img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>mobile-menu.png" />
    </div>
    <!--Mobile Menu Ends-->
    
    <!--Browse Catalog Starts-->
    <div class="browse-catalog" style="display:none;">
      <select>
        <option>Browse Catalog</option>
        <option>Women</option>
        <option>Men</option>
        <option>Kids</option>
        <option>Accessories</option>
      </select>    
    </div>
    <!--Browse Catalog Ends-->

    <!--Search Starts-->
    <form class="header_search" method="post" action="search">
      <div id="liveSearchContainer" class="form-search">
        <input type="text" class="liveSearchInput" id="search" name="q" value="<?php echo $lC_Language->get('button_search'); ?>..." onfocus="$(this).val('').addClass('liveSearchText');" onblur="$(this).val(' <?php echo $lC_Language->get('button_search'); ?> ...').removeClass('liveSearchText');" autocomplete="off" placeholder="Search">
        <button type="submit" title="Search" disabled="disabled"></button>
      </div>
    </form>
    <!--Search Ends-->
  </header>
  <!--Header Ends-->
</div>
<div class="navigation_container">
  <!--Navigation Starts-->
  <nav>
    <ul id="primaryNav" class="primary_nav">
      <li><a id="navHome" href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_home'); ?></a></li>
      <li><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'specials', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_specials'); ?></a></li>
      <li><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_new_products'); ?></a></li>
    </ul>
    
    <script>
    $(document).ready(function() {
      var loc = '<?php echo end(explode("/", $_SERVER['REQUEST_URI'])); ?>';
      if (loc == '') {
        $('#navHome').addClass('current');  
      } else {
        $('#primaryNav li a').each(function() {
          if (this.href.indexOf(loc) != -1) {
            $(this).addClass('current');
          }
        });
      }
    });      
    </script>    
    
    <div class="minicart">
      <?php
        //print_r($lC_ShoppingCart->getProducts());
        if ($lC_ShoppingCart->hasContents()) {
          echo '<a href="#" class="minicart_link">
                  <span class="item"><b>' . $lC_ShoppingCart->numberOfItems() . '</b> ' . ($lC_ShoppingCart->numberOfItems() > 1 ? strtoupper($lC_Language->get('text_cart_items')) : strtoupper($lC_Language->get('text_cart_item'))) . ' /</span> <span class="price"><b>' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</b></span>
                </a>
                <div class="cart_drop">
                  <span class="darw"></span>
                  <ul>';

          foreach ($lC_ShoppingCart->getProducts() as $products) {
            echo '<li>' . $lC_Image->show($products['image'], $products['name'], null, 'mini') . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), '(' . $products['quantity'] . ') x ' . $products['name']) . ' <span class="price">' . $lC_Currencies->format($products['price']) . '</span></li>';
          }           
          
          echo '</ul>
                <div class="cart_bottom">
                  <div class="subtotal_menu">
                    <small>' . $lC_Language->get('box_shopping_cart_subtotal') . '</small>
                    <big>' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</big>
                  </div>
                  <a href="' . lc_href_link(FILENAME_CHECKOUT, null, 'SSL') . '">' . $lC_Language->get('text_checkout') . '</a>
                </div>
              </div>';
        } else {
          echo $lC_Language->get('box_shopping_cart_empty');
        }
      ?>
    </div>
    
    <div id="currencySelect">      
      <form id="currencies" name="currencies" action="<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), null, 'AUTO', false); ?>" method="get">
        <select name="currency" id="currency" onchange="this.form.submit();">
          <?php 
            $currency_data = array();
            foreach ($lC_Currencies->currencies as $key => $value) {
              $currency_data[] = array('id' => $key, 'text' => $value['title']);
            }
            foreach ($currency_data as $currencies) {
              echo '<option value="' . $currencies['id'] . '"' . ($_SESSION['currency'] == $currencies['id'] ? 'selected="selected"' : null) . '>' . $currencies['text'] . '</option>';
            }
          ?>
        </select>
        <?php echo lc_draw_hidden_session_id_field(); ?>
      </form>
    </div>
    
  </nav>
  <!--Navigation Ends-->
</div>
<!--Header Ends-->