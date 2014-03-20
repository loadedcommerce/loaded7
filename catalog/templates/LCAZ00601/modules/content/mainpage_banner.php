<?php
/**
  @package    catalog::templates::content
  @author     AlgoZone, Inc
  @copyright  Copyright 2013 AlgoZone, Inc
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: mainpage_banner.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/content/mainpage_banner.php start-->     
<?php
		$slider_banners = array();
      	$Qbanner = $lC_Database->query('select * from :table_banners where status = 1 and banners_group = :banners_group');
      	$Qbanner->bindTable(':table_banners', TABLE_BANNERS);
      	$Qbanner->bindValue(':banners_group', $lC_Template->getCode() . '_slider');
      	$Qbanner->execute();
		while ( $Qbanner->next() ) {
        	if ( !lc_empty($Qbanner->value('banners_html_text')) ) {
          		$slider_banners[]['banners_html_text'] = $Qbanner->value('banners_html_text');
        	} else {
          		$slider_banners[]['image'] = lc_link_object(lc_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $Qbanner->valueInt('banners_id')), lc_image(DIR_WS_IMAGES . $Qbanner->value('banners_image'), $Qbanner->value('banners_title'), 'class="img-responsive"'), ($Qbanner->valueInt('banners_target')===1)  ?   ' target="_blank" '  :  ' target="_self" '); 
          		$slider_banners[count($banners)-1]['title'] = $Qbanner->value('banners_title');
        	}
		}

		$static_banners = array();
      	$Qbanner = $lC_Database->query('select * from :table_banners where status = 1 and banners_group = :banners_group');
      	$Qbanner->bindTable(':table_banners', TABLE_BANNERS);
      	$Qbanner->bindValue(':banners_group', $lC_Template->getCode() . '_static');
      	$Qbanner->execute();
		while ( $Qbanner->next() ) {
        	if ( !lc_empty($Qbanner->value('banners_html_text')) ) {
          		$static_banners[]['banners_html_text'] = $Qbanner->value('banners_html_text');
        	} else {
          		$static_banners[]['image'] = lc_link_object(
          			lc_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $Qbanner->valueInt('banners_id')), 
          			lc_image(DIR_WS_IMAGES . $Qbanner->value('banners_image'), $Qbanner->value('banners_title'), '', '', 'class="img-responsive"'), 
          			($Qbanner->valueInt('banners_target')===1)  ?   ' target="_blank" '  :  ' target="_self" '
          		); 
          		$static_banners[count($banners)-1]['title'] = $Qbanner->value('banners_title');
        	}
		}
		
?>

<div class="col-sm-12 col-lg-12">
  <div class="row content-mainpage-banner-container margin-bottom clear-both">
	<div id="banner_slides" class="carousel slide">
	  <!-- Indicators -->
	  <ol class="carousel-indicators">
	    <li data-target="#banner_slides" data-slide-to="0" class="active"></li>
	  	<?php 
	  		for( $i = 1; $i < count($slider_banners); $i++) {
	  	?>
	  	<li data-target="#banner_slides" data-slide-to="<?php echo $i; ?>"></li>		
	  	<?php 
	  		}
	  	?>
	  </ol>
	
	  <!-- Wrapper for slides -->
	  <div class="carousel-inner">
	 	<?php 
	 		$i = 0;
	 		foreach($slider_banners as $banner) {
	  	?>
	    <div class="item <?php if ($i == 0) { echo 'active'; } $i++; ?>">
	    <?php 	if ( $banner['image'] ) { ?>
	      	<?php echo $banner['image']; ?>
	      
	  	<?php 
	  			} else {
	  				echo $banner['banners_html_text'];
	  			}
	  	?>
	    </div>	 		
	  	<?php 
	  		}
	  	?>	  
	  </div>
	
	  <!-- Controls -->
	  <a class="left carousel-control" href="#banner_slides" data-slide="prev">
	    <span class="icon-prev"></span>
	  </a>
	  <a class="right carousel-control" href="#banner_slides" data-slide="next">
	    <span class="icon-next"></span>
	  </a>
	</div>
  </div>
</div>

<!--modules/content/mainpage_banner.php end-->
<script>
$(document).ready(function() {     
  $('.content-mainpage-banner-container img').addClass('img-responsive');
});
</script>