<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: mainpage_categories.php v1.0 2013-08-08 Kiran $
*/
?>
<!--modules/content/mainpage_categories.php start-->
  <div class="content-categories-div col-sm-12 col-lg-12">
    <div class="row">
      <h3 class="no-margin-top"><?php echo $lC_Box->getTitle(); ?></h3>
      <?php echo $lC_Box->getContent(); ?>
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
    
    output = '<div class="' + thisContentClass+ ' with-padding-no-top-bottom">'+
             '  <div class="thumbnail align-center">' + imageContent +
             '    <div class="caption">' +
             '      <h3 style="line-height:1.1;">' + nameContent + '</h3>' +
             '    </div>' +
             '  </div>' +
             '</div>';
              
    $(this).html(output);  
    cnt++;
  });
  $('.content-categories-image-src').addClass('img-responsive');
});
</script>
<!--modules/content/mainpage_categories.php end-->