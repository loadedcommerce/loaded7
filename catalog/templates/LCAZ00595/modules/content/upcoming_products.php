<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: upcoming_prodicts.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/content/upcoming_products.php start-->
<div class="content-upcoming-products-div col-sm-12 col-lg-12">
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
    thisContentClass = 'col-sm-4 col-lg-4';
  } else {
    thisContentClass = 'col-sm-3 col-lg-3';
  }
  
  $(".content-upcoming-products-container").each(function(){
    var imageContent = $(this).find('div.content-upcoming-products-image').html();
    var nameContent = $(this).find('div.content-upcoming-products-name').html();;
    var nameContentText = $(this).find('div.content-upcoming-products-name').text();;
    var priceContent = $(this).find('div.content-upcoming-products-price').html();
    var dateContent = $(this).find('div.content-upcoming-products-date').html();
    var textExpected = '<?php echo $lC_Language->get('upcoming_products_text_expected'); ?>';
    
    var newNameContentText = (nameContentText.length > 16) ? nameContentText.substr(0, 13) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);
    
    output = '<div class="' + thisContentClass + ' with-padding-no-top-bottom">'+
             '  <div class="thumbnail align-center large-padding-top">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 class="content-upcoming-products-text-name small-margin-top">' + nameContent + '</h3>' +
             '      <div class="row">';
    if (mediaType == 'desktop') {
      output += '        <div class="col-sm-6 col-lg-6">' +
                '          <p class="lead content-upcoming-products-text-price">' + priceContent + '</p>' +
                '        </div>';
    }
    output += '        <div class="' + ((mediaType != 'desktop') ? 'col-sm-12 col-lg-12' : 'col-sm-6 col-lg-6') + ' content-upcoming-products-text-expected">' + textExpected + '<br />' + dateContent +
              '        </div>' +
              '      </div>' +
              '    </div>' +
              '  </div>' +
              '</div>';
              
    $(this).html(output);  
  });
  $('.content-upcoming-products-image-src').addClass('img-responsive');
});
</script>
<!--modules/content/upcoming_products.php end-->