<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: header.php v1.0 2013-08-08 datazen $
*/ 
?>
<!--header.php start-->
<div class="navbar navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_home'); ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo $lC_Language->get('text_sign_in'); ?></a></li>
          <?php 
          if ($lC_Customer->isLoggedOn()) { 
            echo '<li><a href="' . lc_href_link(FILENAME_ACCOUNT, 'logoff', 'SSL') , '">' . $lC_Language->get('text_sign_out') . '</a></li>';
          } 
          ?>
          <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
          <li><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>"><?php echo $lC_Language->get('text_checkout'); ?></a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $lC_Language->get('text_dropdown'); ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <?php echo $lC_Template->getTopCategoriesSelection(); ?>
            </ul>
          </li>
        </ul>
        <div class="pull-right margin-top">
          <span class="language-menu">
            <?php echo $lC_Template->getLanguageSelection(); ?>
          </span>
        </div>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>
<div class="page-header">
  <div class="container">
    <div class="row-fluid">
      <div class="span3">
        <h1 class="brand"><a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo.png" /></a></h1>
      </div>
      <div class="span9">
        <div class="row-fluid">
          <div class="span8 text-center">
            <div style="padding-top:20px;">
              <form class="form-search" name="search" id="search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get">
                <div class="input-append">
                  <input type="text" class="span12 search-query" name="keywords" id="keywords"><?php echo lc_draw_hidden_session_id_field(); ?>
                  <button type="submit" class="btn">Search</button>
                </div>
              </form>
            </div>
          </div>
          <div class="span4">
            <p class="text-right" style="padding-top:20px;">
              <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'cart', 'NONSSL'); ?>">View Cart</a> | Total: <?php echo $lC_Currencies->format($lC_ShoppingCart->getSubTotal()); ?> (<?php echo $lC_ShoppingCart->numberOfItems(); ?>)
            </p>
          </div>
        </div>
      </div>       
      
    </div>
  </div>
  <div class="container">
    <?php
    if ($lC_Services->isStarted('breadcrumb')) {
      echo '<ul class="breadcrumb">' . $lC_Breadcrumb->getPathList() . '</ul>' . "\n";
    }
    ?>  
  </div>
  <script>
  $(document).ready(function() {
    $(".breadcrumb li").each(function(){
      if ($(this).is(':last-child')) {
        $(this).addClass('active');
      } else {
        $(this).append('<span class="divider">&rarr;</span>');  
      }
    });    
  });
  </script>
</div>
<!--header.php end-->