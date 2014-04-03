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
if (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] === TRUE) echo '<div class="alert alert-danger no-margin-bottom no-padding-top no-padding-bottom text-center">' . $lC_Language->get('text_admin_session_active') . '</div>';
?>
<!--header.php start-->
<nav class="navbar navbar-inverse" role="navigation" style="height:50px;">
  <div class="container">
    <div class="row">
    <div class="col-sm-3 col-lg-2">
        <h1 class="logo"><a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><img alt="<?php echo STORE_NAME; ?>" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo.png" /></a></h1>
      </div>
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo strtoupper($lC_Language->get('text_home')); ?></a>
  </div>
  
  <div class=" collapse navbar-collapse navbar-ex1-collapse">
    <div class="row">

    	<div class="col-sm-7 col-md-7 col-lg-7">
	      <ul class="nav navbar-nav">
            <?php echo $lC_Template->getTopCategoriesSelection(); ?>
            
	       <!-- <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'); ?>"><?php echo strtoupper('LOGIN'); ?></a></li>-->
	  
	      </ul>
    	</div>
            
    </div>
  </div>
  <div class="header_icon_cont col-sm-5 col-lg-5 pull-right ">
      <div class="thumbButtons">  
            <div class="pull-right header_icon icon_cart  col-xs-3 col-sm-3 col-lg-3"><a  href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'cart', 'SSL'); ?>"></a></div>     
            <div class="pull-right header_icon icon_search col-xs-3 col-sm-3 col-lg-3"><a href="<?php echo lc_href_link(FILENAME_SEARCH, 'search', 'SSL'); ?>"></a></div>
            
            
          <div class=" pull-right currency_switch col-xs-4 col-sm-4 col-lg-4">
            <div class="currency-box">
             <?php echo $lC_Template->getCurrencySelectionForm(); ?>
            </div> 
          </div>  

          <div class=" pull-right language_switch col-xs-2 col-sm-2 col-lg-2">
                   <div class="col-sm-1 col-lg-1 language-box">
                      <ul class="language-menu">
                        <?php echo $lC_Template->getLanguageSelection(true, false, ''); ?>
                      </ul>
                  </div> 
        
            </div> 
      </div>   
            
  </div>
</div>
  
</nav>


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
      $ret .= '<button data-currency-id="'.$key.'" type="button" class="btn btn-sm '.$selected.'">'.$symbol.'</button>';
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