<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: upcoming_prodicts.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/content/upcoming_products.php start-->
<div class="row-fluid articles-grid">
  <h2 class="sub_title"><?php echo $lC_Box->getTitle(); ?></h2>
  <?php echo $lC_Box->getContent(); ?>
</div>
<script>
$(document).ready(function() {
  var mediaType = _setMediaType();
  var mainContentClass = $('#main-content-container').attr('class');
  if(mainContentClass == 'span6') {
    thisContentClass = 'span4';
  } else {
    thisContentClass = 'span3';
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
    
    output = '<div class="' + thisContentClass + ' with-padding">'+
             '  <div class="thumbnail align-center">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 class="content-upcoming-products-text-name">' + nameContent + '</h3>' +
             '      <div class="row-fluid">';
    if (mediaType == 'desktop') {
      output += '        <div class="span6">' +
                '          <p class="lead content-upcoming-products-text-price">' + priceContent + '</p>' +
                '        </div>';
    }
    output += '        <div class="' + ((mediaType != 'desktop') ? 'span12' : 'span6') + ' content-upcoming-products-text-expected">' + textExpected + '<br />' + dateContent +
              '        </div>' +
              '      </div>' +
              '    </div>' +
              '  </div>' +
              '</div>';
              
    $(this).html(output);  
  });
});
</script>
<!--modules/content/upcoming_products.php end-->