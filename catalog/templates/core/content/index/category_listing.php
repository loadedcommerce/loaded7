<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: category_listing.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/index/category_listing.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
    if (lC_Template_output::getCategoryDescription() != '') {
      echo lC_Template_output::getCategoryDescription(); 
    }
    ?>
    <div class="col-sm-12 col-lg-12 container text-center">
      <?php echo lC_Template_output::getCategoryListing(); ?>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {

  var mediaType = _setMediaType();
  var mainContentClass = $('#main-content-container').attr('class');
  if(mainContentClass == 'col-sm-6 col-lg-6') {
    thisContentClass = 'col-sm-6 col-lg-6';
  } else {
    thisContentClass = 'col-sm-4 col-lg-4';
  }  
  
  var cnt = 0;
  $(".content-categories-container").each(function(){
    
    var imageContent = $(this).find('div.content-categories-image').html();
    var nameContent = $(this).find('div.content-categories-name').html();
    var nameContentText = $(this).find('div.content-categories-name').text();
    
    if (!imageContent) imageContent = '';
    
    var newNameContentText = (nameContentText.length > 18) ? nameContentText.substr(0, 15) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);  
    
    output = '<div class="' + thisContentClass+ '">'+
             '  <div class="thumbnail align-center">' + imageContent +
             '    <div class="caption">' +
             '      <h3 class="no-margin-top no-margin-bottom">' + nameContent + '</h3>' +
             '    </div>' +
             '  </div>' +
             '</div>';
              
    $(this).html(output);  
    cnt++;
  });
  $('.content-categories-image-src').addClass('img-responsive');
  $('.thumbnail').equalHeights();
});
</script>
<!--content/index/category_listing.php end-->