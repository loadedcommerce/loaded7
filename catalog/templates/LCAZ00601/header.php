<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: header.php v1.0 2013-08-08 datazen $
*/ 
if (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] === TRUE) echo '<div class="alert alert-danger no-margin-bottom no-padding-top no-padding-bottom text-center">' . $lC_Language->get('text_admin_session_active') . '</div>';
?>
<!--header.php start-->

<nav class="navbar navbar-inverse" role="navigation">
  <div class="container">
    <div class="row">
  
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_home'); ?></a>
  </div>
  
  <div class=" collapse navbar-collapse navbar-ex1-collapse">
    <div class="row">

    	<div class="col-sm-5 col-md-5 col-lg-5">
	      <ul class="nav navbar-nav">
	        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo $lC_Language->get('text_sign_in'); ?></a></li>
	        <?php 
	        if ($lC_Customer->isLoggedOn()) { 
	          echo '<li><a href="' . lc_href_link(FILENAME_ACCOUNT, 'logoff', 'SSL') , '">' . $lC_Language->get('text_sign_out') . '</a></li>';
	        } 
	        ?>
	        <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
	        <li><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>"><?php echo $lC_Language->get('text_checkout'); ?></a></li>
	      </ul>
    	</div>

      <?php if ($lC_Template->getBranding('chat_code') != '') { ?>
        <div class="pull-right mid-margin-top large-margin-right">
          <?php echo $lC_Template->getBranding('chat_code'); ?>
        </div>  
      <?php } ?>    
      
        <div class="col-sm-2 col-lg-2">
        	<ul class="language-menu">
          	<?php echo $lC_Template->getLanguageSelection(true, false, ''); ?>
        	</ul>
      	</div>  
          
        <div class="col-sm-2 col-md-2 col-lg-2 currency-box">
		  <form role="form" class="form-inline no-margin-bottom" id="currencies" name="currencies" action="<?php echo lc_href_link(FILENAME_DEFAULT, null, 'NONSSL', false); ?>" method="get">
			<?php echo getCurrencyBlock(); ?>
		  </form>
		<script>
		$(document).ready(function() { 
			$('#currencies .btn').click(function(){
				var id = $(this).attr('data-currency-id');
				$('#currencies').append('<input type="hidden" name="currency" value="'+ id +'">');
				$('#currencies').submit();
			});
		});
		</script>
      	</div> 

       <div class="col-sm-2 col-lg-2 cart-container">
      	<div class="row">
      		<a class="btn btn-large btn-primary carttop" href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'cart', 'NONSSL'); ?>"><img alt="View Cart" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>cart_icon.png" /> (<?php echo $lC_ShoppingCart->numberOfItems(); ?>) items</a>
      	</div>
       </div>     
     
    </div>
  </div>

  </div>
</div>
  
</nav>

<div class="page-header">
  <div class="container">
    <div class="row">
      <div class="col-sm-5 col-lg-4">
        <h1 class="logo">
          <a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>">
          <?php 
            if ($lC_Template->getBranding('site_image') != '') {
              echo '<img alt="' . STORE_NAME . '" src="' . DIR_WS_IMAGES . 'branding/' . $lC_Template->getBranding('site_image') . '" />';
            } else { 
         ?>
         	<a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><img alt="<?php echo STORE_NAME; ?>" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo.png" /></a>
         <?php 
            }
          ?>
          </a>
        </h1>
        <?php if ($lC_Template->getBranding('slogan') != '') { ?>
          <p class="slogan clear-both"><?php echo $lC_Template->getBranding('slogan'); ?></p>
        <?php } ?>        
      </div>
      <div class="col-sm-3 col-lg-4 search-box">
            <form role="form" class="form-inline" name="search" id="search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get">
              <div class="input-group">
                <input type="text" class="form-control search-query" name="keywords" id="keywords" value="" placeholder="<?php echo "search"; ?>" onClick="this.value = '';" onKeyDown="this.style.color = '#000000';"><?php echo lc_draw_hidden_session_id_field(); ?>
              	<span class="input-group-btn">
                	<button type="submit" class="btn btn margin-left"><?php //echo $lC_Language->get('button_search'); ?></button>
        	    </span>
              </div>
            </form>
      	</div> 
        
    </div>
  </div>
</div>
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

<div class="page-menu">
  <div class="container">
  	<div class="col-sm-12 col-lg-12 no-padding">
    <div id="category_nav_panel">
		
		<nav class="subnav" role="navigation">
  		<div class="navbar-header">
    		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".subnav-ex1-collapse">
      		<span class="sr-only">Toggle navigation</span>
      		<span class="icon-bar"></span>
      		<span class="icon-bar"></span>
      		<span class="icon-bar"></span>
    		</button>
  		</div>		
  		<div class="collapse navbar-collapse subnav-ex1-collapse">
          <ul class="nav navbar-nav nav-pills category_nav">
         	<?php echo getCategoriesSelection(); ?>
          </ul>
 		</div>
        
        </nav>

  	</div>
  	</div>
  </div>
</div>

<?php 
	if ( count($lC_Breadcrumb->getArray()) > 1) {
?>
<div class="page-breadcrumbs">
  <div class="container">
    <div class="row">
      <?php
      if ($lC_Services->isStarted('breadcrumb')) {
        echo '<ol class="breadcrumb">' . $lC_Breadcrumb->getPathList() . '</ol>' . "\n";
      }
      ?>  
    </div>
    <script>
    $(document).ready(function() {
      $(".breadcrumb li").each(function(){
        if ($(this).is(':last-child')) {
          $(this).addClass('active');
        }
      });    
    });
    </script>
  </div>
</div>
<?php
	} 
	   
?>  


<?php
 /*
  * Get the 2 level categories for nav
  *
  * @access public
  * @return array
  */
  function getCategoriesSelection() {
      global $lC_CategoryTree, $cPath;

      $lC_CategoryTree->reset();
      // added to control maximum level of categories infobox if desired :: maestro
      if (isset($_SESSION['setCategoriesMaximumLevel']) && $_SESSION['setCategoriesMaximumLevel'] != '') {
        $lC_CategoryTree->setMaximumLevel($_SESSION['setCategoriesMaximumLevel']);
      }
      $lC_CategoryTree->setCategoryPath($cPath, '', '');
      $lC_CategoryTree->setParentGroupStringTop('', '');
      $lC_CategoryTree->setParentGroupString('<div><ul>', '</ul></div>');
      $lC_CategoryTree->setChildStringWithChildren('<li>', '</li>');
      $lC_CategoryTree->setUseAria(true);
            
      $lC_CategoryTree->setShowCategoryProductCount((BOX_CATEGORIES_SHOW_PRODUCT_COUNT == '1') ? true : false);

      return $lC_CategoryTree->getTree();
  }  
  
  
  
  function getCurrencyBlock() {
	global $lC_Session, $lC_Currencies;

	$ret = '';

    foreach ($lC_Currencies->currencies as $key => $value) {
      $selected = '';
      if ($_SESSION['currency'] == $key) {
      	$selected = 'selected';
      }
      $symbol = ($value['symbol_left']) ? $value['symbol_left'] : $value['symbol_right'];
      $ret .= '<button data-currency-id="'.$key.'" type="button" class="btn btn-sm '.$selected.'">'. utf8_decode($symbol) .'</button>';
    }

    if (sizeof($data) > 1) {
      $hidden_get_variables = '';

      foreach ($_GET as $key => $value) {
        if ( ($key != 'currency') && ($key != $lC_Session->getName()) && ($key != 'x') && ($key != 'y') ) {
          $hidden_get_variables .= lc_draw_hidden_field($key, $value);
        }
      }
    }

	return $ret . $hidden_get_variables . lc_draw_hidden_session_id_field();
}
?>


<!--header.php end-->