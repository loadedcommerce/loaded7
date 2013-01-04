<?php
/*
  $Id: info.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
function refreshVariants() {
  var price = null;
  var availability = null;
  var model = null;

  for (c in combos) {
    
    id = null;

    variants_loop:
    for (group_id in combos[c]['values']) {
      for (value_id in combos[c]['values'][group_id]) {
        if (document.getElementById('variants_' + group_id) != undefined) {
          if (document.getElementById('variants_' + group_id).type == 'select-one') {
            if (value_id == document.getElementById('variants_' + group_id).value) {
              id = c;
            } else {
              id = null;

              break variants_loop;
            }
          }
        } else if (document.getElementById('variants_' + group_id + '_1') != undefined) {
          j = 0;

          while (true) {
            j++;

            if (document.getElementById('variants_' + group_id + '_' + j).type == 'radio') {
              if (document.getElementById('variants_' + group_id + '_' + j).checked) {
                if (value_id == document.getElementById('variants_' + group_id + '_' + j).value) {
                  id = c;
                } else {
                  id = null;

                  break variants_loop;
                }
              }
            }

            if (document.getElementById('variants_' + group_id + '_' + (j+1)) == undefined) {
              break;
            }
          }
        }
      }
    }

    if (id != null) {
      break;
    }
  }

  if (id != null) {
    price = combos[id]['price'];
    availability = productInfoAvailability;
    model = combos[id]['model'];
  } else {
    price = originalPrice;
    availability = productInfoNotAvailable;
    model = '';
  }

  document.getElementById('productInfoPrice').innerHTML = '<big>' + price + '</big>';
  document.getElementById('productInfoAvailability').innerHTML = availability;
  document.getElementById('productInfoModel').innerHTML = model;
}
</script>
<style>
input {
    /* round the corners */
    border-radius: 4px;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;    
}

input:focus { 
    outline:none;
    border: 1px solid #c541fc; 
    /* create a BIG glow */
    box-shadow: 0px 0px 14px #c541fc; 
    -moz-box-shadow: 0px 0px 14px #c541fc;
    -webkit-box-shadow: 0px 0px 14px #c541fc;  
}​
</style>
<!--PRODUCT INFO SECTION STARTS-->
  <!--PRODUCT DETAIL STARTS-->
  <div id="product_detail">
    <?php if ($lC_Product->hasImage()) { ?> 
    <!--PRODUCT LEFT STARTS-->
    <div class="product_leftcol"> 
      <a id="pimage" style="text-decoration:none;" href="<?php echo (file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($lC_Product->getImage(), 'large'))) ? lc_href_link(DIR_WS_CATALOG . $lC_Image->getAddress($lC_Product->getImage(), 'large')) : lc_href_link(DIR_WS_IMAGES . 'no_image.png'); ?>" title="<?php echo $lC_Product->getTitle(); ?>" class="thickbox"><img src="<?php echo $lC_Image->getAddress($lC_Product->getImage(), 'large'); ?>" title="<?php echo $lC_Product->getTitle(); ?>" alt="<?php echo $lC_Product->getTitle(); ?>" id="image" style="margin-bottom: 3px;" /></a><br />
      <span class="pr_info">
        <a href="<?php echo (file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($lC_Product->getImage(), 'popup'))) ? lc_href_link(DIR_WS_CATALOG . $lC_Image->getAddress($lC_Product->getImage(), 'popup')) : lc_href_link(DIR_WS_IMAGES . 'no_image.png'); ?>" class="thickbox"><?php echo $lC_Language->get('enlarge_image'); ?></a>
      </span>

      <ul class="pr_gallery">
        <?php
        // has images other than default?
        if (sizeof($lC_Product->getImages()) > 1) {                                     
          foreach ( $lC_Product->getImages() as $key => $value ) {
            if ($value['default_flag'] == true) continue;
            ?>
            <li><a href="<?php echo (file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($value['image'], 'popup'))) ? lc_href_link(DIR_WS_CATALOG . $lC_Image->getAddress($value['image'], 'popup')) : lc_href_link(DIR_WS_IMAGES . 'no_image.png'); ?>" title="<?php echo $lC_Product->getTitle(); ?>" class="thickbox"><img src="<?php echo $lC_Image->getAddress($value['image'], 'extra'); ?>" title="<?php echo $lC_Product->getTitle(); ?>" alt="<?php echo $lC_Product->getTitle(); ?>" id="image" style="margin-bottom: 3px;" /></a></li>
            <?php  
          }
        }
        ?>
      </ul>
    </div>
    <!--PRODUCT LEFT ENDS-->
    <?php } ?> 
    <!--PRODUCT RIGHT STARTS-->
    <form name="cart_quantity" id="cart_quantity" action="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&action=cart_add'); ?>" method="post">
    <div class="product_rightcol">
      <?php
      if ($lC_Product->getAttribute('manufacturers') != null || $lC_Product->hasModel()) {
        ?>
        <small class="pr_type">
          <span id="productInfoManufacturers"><?php echo $lC_Product->getAttribute('manufacturers'); ?></span>
          <span id="productInfoModel" style="float:right;"><?php echo $lC_Product->getModel(); ?></span>
        </small>
        <h1 style="margin-top:10px;"><?php echo $lC_Template->getPageTitle(); ?></h1>
        <?php
      } else {
        ?>
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <?php   
      } 
      $availability = ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->isInStock($lC_Product->getID()) === false) ) ? '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>' : $lC_Product->getAttribute('shipping_availability');
      ?>
      <p class="short_dc"> <?php echo ($lC_Product->getDescription() != null) ? $lC_Product->getDescription() : $lC_Language->get('no_description_available'); ?></p>

      
      <div class="pr_price">
        <span id="productInfoPrice"><?php echo $lC_Product->getPriceFormated(true); ?></span>
        <span id="productInfoAvailability"><?php echo $availability ?></span> (<?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'shipping'), $lC_Language->get('more_information'), 'target="_blank"'); ?>)
      </div>
      
      <div id="reviewsContainer" style="margin-bottom:10px;">
        <label for="productReviewsValue" style="text-transform:uppercase;"><?php echo $lC_Language->get('average_rating'); ?></label>
        <span class="productReviewsValue" id="productReviewsValue"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $lC_Product->getData('reviews_average_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $lC_Product->getData('reviews_average_rating'))); ?></span>
        <?php       
        $Qreviews = lC_Reviews::getListing($lC_Product->getID());
        if ($lC_Reviews->getTotal($lC_Product->getID()) > 0) {
          ?>
          <span><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews&' . $lC_Product->getKeyword()); ?>" target="_blank" class="noDecoration">(<?php echo $lC_Language->get('more_information'); ?>)</a></span>
          <?php
        } else {            
          ?>
          <span><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()); ?>" target="_blank" class="noDecoration">(<?php echo $lC_Language->get('text_write_review_first'); ?></a>)</span>
          <?php
        }
        ?>      
      </div>
      
      <!--PRODUCT VARIANTS STARTS-->
      <div style="clear:both;">    
        <?php
          if ( $lC_Product->hasVariants() ) {
        ?>
        <div class="variant_info" id="variantsBlock">
          <?php
            foreach ( $lC_Product->getVariants() as $group_id => $value ) {
              echo lC_Variants::parse($value['module'], $value);
            }
            echo lC_Variants::defineJavascript($lC_Product->getVariants(false));
          ?>
        </div>
        <?php
          }
        ?>

        <div class="qty_info">
          <div class="quantity">
            <label><?php echo $lC_Language->get('text_add_to_cart_quantity'); ?></label>
            <?php echo lc_draw_input_field('quantity'); ?>
          </div>
        </div>

      </div>
      <!--PRODUCT VARIANTS ENDS-->      
      
      <!--ADD TO CART STARTS-->
      <div class="add_to_buttons">
        <a onclick="$('#cart_quantity').submit();" id="add_to_cart" class="button"><button class="add_cart"><?php echo $lC_Language->get('button_add_to_cart'); ?></button></a>
        <span>or</span>
          <ul>
            <li><a href="javascript://" onclick="alert('<?php echo $lC_Language->get('feature_not_available'); ?>'); return false;"><?php echo $lC_Language->get('add_to_wishlist'); ?></a></li>
          </ul> 
      </div>
      <!--ADD TO CART END ENDS-->            
    </div>
    <!--PRODUCT RIGHT ENDS-->
    <?php
    if ( $lC_Product->hasVariants() ) {
      ?>
      <script>
      var originalPrice = '<?php echo $lC_Product->getPriceFormated(true); ?>';
      var productInfoNotAvailable = '<span id="productVariantCombinationNotAvailable"><?php echo $lC_Language->get('variant_combo_not_available'); ?></span>';
      var productInfoAvailability = '<?php if ( $lC_Product->hasAttribute('shipping_availability') ) { echo addslashes($lC_Product->getAttribute('shipping_availability')); } else { echo $lC_Language->get('product_variant_in_stock'); } ?>';
      refreshVariants();
      </script>
      <?php 
    } 
    ?>    
    </form>
  </div>
  <!--PRODUCT DETAIL ENDS-->
<!--PRODUCT INFO SECTION ENDS--> 