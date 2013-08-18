<?php
/**  
*  $Id: recently_visited.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
?>
<!--modules/content/recently_visited.php start-->
<div class="col-sm-12 col-lg-12">
  <div class="row margin-bottom">
    <h3 class="no-margin-top"><?php echo $lC_Box->getTitle(); ?></h3>
    <?php echo $lC_Box->getContent(); ?>
  </div>
</div>
<script>
$(document).ready(function() {    
  var mainContentClass = $('#main-content-container').attr('class');
  if(mainContentClass == 'col-sm-6 col-lg-6') {
    thisContentClass = 'col-sm-4 col-lg-4';
  } else {
    thisContentClass = 'col-sm-3 col-lg-3';
  }
  
  $(".content-recently-visited-container").each(function(){
    var imageContent = $(this).find('div.content-recently-visited-image').html();
    var nameContent = $(this).find('div.content-recently-visited-name').html();
    var nameContentText = $(this).find('div.content-recently-visited-name').text();
    var priceContent = $(this).find('div.content-recently-visited-price').html();
    var fromContent = $(this).find('div.content-recently-visited-from').html();
    var textExpected = '<?php echo $lC_Language->get(''); ?>';
    
    var newNameContentText = (nameContentText.length > 16) ? nameContentText.substr(0, 13) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);
    
    output = '<div class="' + thisContentClass + ' with-padding-no-top-bottom">'+
             '  <div class="thumbnail align-center large-padding-top">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 class="content-recently-visited-text-name no-margin-top small-margin-bottom">' + nameContent + '</h3>' +
             '      <p class="text-center small-margin-top small-margin-bottom">' + fromContent + '</p>' +
             '    </div>' +
             '  </div>' +
             '</div>';            
              
    $(this).html(output);  
  });
  $('.content-recently-visited-image-src').addClass('img-responsive');
});
</script>
<!--modules/content/recently_visited.php end-->

