<?php
/**  
*  $Id: images.php v1.0 2013-01-01 datazen $
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
$large_image = $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'id="productImageLarge"', 'large');
?>
<!--content/products/images.php start-->
<div class="full_page">
  <div class="short-code-column">
    <!-- h1><?php echo $lC_Template->getPageTitle(); ?></h1 --> 
    <?php
    if ($lC_Product->numberOfImages() > 1) {
      ?>
      <div id="productImageThumbnails" class="content" style="position: absolute; top: 10px; overflow: auto; width: <?php echo ($lC_Image->getWidth('thumbnails') * 2) + 15; ?>px;">
        <?php
        foreach ($lC_Product->getImages() as $images) {
          if ( isset($_GET['image']) && ($_GET['image'] == $images['id']) ) {
            $large_image = $lC_Image->show($images['image'], $lC_Product->getTitle(), 'id="productImageLarge"', 'large');
          }
          echo '<span style="width: ' . $lC_Image->getWidth($lC_Image->getCode(DEFAULT_IMAGE_GROUP_ID)) . 'px; padding: 2px; float: left; text-align: center;">
               ' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'images&' . $lC_Product->getKeyword() . '&image=' . $images['id']), $lC_Image->show($images['image'], $lC_Product->getTitle(), 'height="' . $lC_Image->getHeight($lC_Image->getCode(DEFAULT_IMAGE_GROUP_ID)) . '" style="max-width: ' . $lC_Image->getWidth($lC_Image->getCode(DEFAULT_IMAGE_GROUP_ID)) . 'px;"'), 'onclick="loadImage(\'' . $lC_Image->getAddress($images['image'], 'large') . '\'); return false;"') . 
               '</span>';
        }
        ?>
      </div>
      <?php
      }
    ?>
    <div id="productImageLargeBlock" style="position: absolute; left: <?php echo ($lC_Product->numberOfImages() > 1) ? ($lC_Image->getWidth($lC_Image->getCode(DEFAULT_IMAGE_GROUP_ID)) * 2) + 60 : 10; ?>px; top: 10px; text-align: center; width: <?php echo $lC_Image->getWidth('large'); ?>px;">
      <?php echo $large_image; ?>
    </div>  
  </div>
</div>
<script>
function loadImage(imageUrl) {
  $("#productImageLarge").fadeOut('fast', function() {
    $("#productImageLarge").attr('src', imageUrl);
    $("#productImageLarge").fadeIn("slow");
  });
}
</script>
<!--content/products/images.php end-->