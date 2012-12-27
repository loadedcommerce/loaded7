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
      <!--<ul class="pr_gallery">
        <li><a href="#"><img src="images/products/originals/pr_gal1.jpg"></a></li>
        <li><a href="#"><img src="images/products/originals/pr_gal2.jpg"></a></li>
        <li><a href="#"><img src="images/products/originals/pr_gal3.jpg"></a></li>
      </ul>-->
    </div>
    <!--PRODUCT LEFT ENDS-->
    <?php } ?> 
    <!--PRODUCT RIGHT STARTS-->
    <form name="cart_quantity" id="cart_quantity" action="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&action=cart_add'); ?>" method="post">
    <div class="product_rightcol">
      <!--/////////////////////////////////////////////-->
      <!-- Need to save for Product Type or Product Attribute Type or Meta Keywords etc etc (maestro) --> 
      <small class="pr_type">Lingerie</small>
      <!--/////////////////////////////////////////////-->
      <!--<span class="icon"><?php //echo lc_icon('icon_products.png', $lC_Template->getPageTitle()); ?></span>-->
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      
      <div class="pr_price"><big><?php echo $lC_Product->getPriceFormated(true); ?></big>(&#43;<?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'shipping'), 'shipping'); ?>)</div>
      <div style="float:left; text-align:right; margin-right:10px; text-transform:uppercase; line-height:18px;">
        <?php
          $availability = ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->isInStock($lC_Product->getID()) === false) ) ? '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>' : $lC_Product->getAttribute('shipping_availability');
        ?>
        <div><span id="productInfoAvailability"><?php echo $lC_Language->get('availability'); ?>:</span></div>
        <div><span id="productInfoModel"><?php echo $lC_Language->get('model'); ?>:</span></div> 
        <div><span id="productInfoBrand"><?php echo $lC_Language->get('brand'); ?>:</span></div> 
        <div><span id="productInfoImage"><?php echo $lC_Language->get('average_rating'); ?>:</span></div>
        <?php  
          if ( $lC_Product->hasVariants() ) {
        ?>
          <div class="productInfoKey" style="margin-top:6px;"><?php echo $lC_Language->get('product_attributes'); ?></div>
        <?php
          }
        ?>
      </div>
      <div style="float:left; margin-bottom:10px; line-height:18px;">  
        <div><span id="productInfoAvailability"><?php echo $availability ?></span></div>
        <div><span id="productInfoModel"><?php echo ($lC_Product->hasModel()) ? $lC_Product->getModel() : '&nbsp;'; ?></span></div> 
        <div><span id="productInfoManufacturers"><?php echo ($lC_Product->getAttribute('manufacturers') != null) ? $lC_Product->getAttribute('manufacturers') : '&nbsp;'; ?></span></div> 
        <div><span id="productInfoImage"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $lC_Product->getData('reviews_average_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $lC_Product->getData('reviews_average_rating'))); ?></span></div>  
      </div>
      <!--PRODUCT VARIANTS STARTS-->
      <div>    
        <?php
          if ( $lC_Product->hasVariants() ) {
        ?>
        <div id="variantsBlock">
          <div id="variantsBlockData">
          <?php
            foreach ( $lC_Product->getVariants() as $group_id => $value ) {
              echo lC_Variants::parse($value['module'], $value);
            }
            echo lC_Variants::defineJavascript($lC_Product->getVariants(false));
          ?>
          </div>
        </div>
        <script language="javascript" type="text/javascript">
          var originalPrice = '<?php echo $lC_Product->getPriceFormated(true); ?>';
          var productInfoNotAvailable = '<span id="productVariantCombinationNotAvailable">Not available in this combination. Please select another combination for your order.</span>';
          var productInfoAvailability = '<?php if ( $lC_Product->hasAttribute('shipping_availability') ) { echo addslashes($lC_Product->getAttribute('shipping_availability')); } else { echo 'In Stock'; } ?>';
          refreshVariants();
        </script>
        <?php
          }
        ?>
        <!-- STILL HARD CODED AWAITING VARIANTS CODE-->
        <div class="size_info">
          <div class="size_sel">
            <label>Available Size :</label>
            <select>
              <option>SELECT</option>
              <option>32</option>
            </select>
          </div>
          <div class="color_sel">
            <label>Color :</label>
            <select>
              <option>BEIGE</option>
              <option>CORAL</option>
            </select>
          </div>
        </div>
        <div class="qty_info">
          <div class="quantity">
            <label><?php echo $lC_Language->get('text_add_to_cart_quantity'); ?></label>
            <?php echo lc_draw_input_field('quantity', 1, 'style="font-size: 11px; text-align:right; height:20px; width:80px !important; color:#818181;"'); ?>
          </div>
        </div>
      </div>
      <!--PRODUCT VARIANTS ENDS-->      
      <!--ADD TO CART STARTS-->
      <div class="add_to_buttons">
        <a onclick="$('#cart_quantity').submit();" id="add_to_cart" class="button"><button class="add_cart">Add to Cart</button></a>
      </div>
      <!--ADD TO CART END ENDS-->            
    </div>
    <!--PRODUCT RIGHT ENDS-->
    </form>
  </div>
  <!--TABS STARTS-->
  <div class="simpleTabs">
    <!--TABS NAV STARTS-->
    <ul class="simpleTabsNavigation">
      <li id="descriptionTabNav"><a href="#"><?php echo $lC_Language->get('description'); ?></a></li>
      <li id="imagesTabNav"><a href="#"><?php echo $lC_Language->get('additional_images'); ?></a></li>
      <li id="reviewsTabNav"><a href="#"><?php echo $lC_Language->get('reviews_heading'); ?> (<?php echo $lC_Reviews->getTotal($lC_Product->getID()); ?>)</a></li>
      <li id="moreTabNav"><a href="#"><?php echo $lC_Language->get('more_information'); ?></a></li> 
    </ul>
    <!--TABS NAV ENDS-->
    <!--DESCRIPTION TAB STARTS-->
    <div class="simpleTabsContent">
      <div class="tab_description"><?php echo ($lC_Product->getDescription() != null) ? $lC_Product->getDescription() : $lC_Language->get('no_description_available'); ?></div>
    </div>
    <!--DESCRIPTION TAB END-->
    <!--IMAGE TAB STARTS-->
    <div class="simpleTabsContent">
      <div class="tab_image">
        <div style="max-height:<?php echo ($lC_Image->getHeight('product_info')+10); ?>px; width:546px;">
          <div style="overflow: auto; white-space: nowrap">             
            <?php
              // has images other than default?
              if (sizeof($lC_Product->getImages()) > 1) {                                     
                foreach ( $lC_Product->getImages() as $key => $value ) {
                  if ($value['default_flag'] == true) continue;
            ?>
                <a href="<?php echo (file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($value['image'], 'popup'))) ? lc_href_link(DIR_WS_CATALOG . $lC_Image->getAddress($value['image'], 'popup')) : lc_href_link(DIR_WS_IMAGES . 'no_image.png'); ?>" title="<?php echo $lC_Product->getTitle(); ?>" class="thickbox"><img src="<?php echo $lC_Image->getAddress($value['image'], 'product_info'); ?>" title="<?php echo $lC_Product->getTitle(); ?>" alt="<?php echo $lC_Product->getTitle(); ?>" id="image" style="margin-bottom: 3px;" /></a>
            <?php  
                }
              } else {
            ?>
              <p><?php echo $lC_Language->get('no_additional_images_available'); ?></p>
            <?php
              }
            ?>
          </div>
        </div> 
      </div>
    </div>
    <!--IMAGE TAB ENDS-->
    <!--REVIEWS TAB STARTS-->
    <div class="simpleTabsContent">
      <div class="tab_reviews">
      <?php     
        $counter = 0;
        $Qreviews = lC_Reviews::getListing($lC_Product->getID());
        if ($lC_Reviews->getTotal($lC_Product->getID()) > 0) {
          while ($Qreviews->next()) {
            $counter++;
            if ($counter > 1) {
      ?>
        <hr style="height: 1px; width: 100%; text-align: left; margin-left: 0px" />
      <?php
            }
      ?>
        <p><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $Qreviews->valueInt('reviews_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))) . '&nbsp;' . sprintf($lC_Language->get('reviewed_by'), $Qreviews->valueProtected('customers_name')) . '; ' . lC_DateTime::getLong($Qreviews->value('date_added')); ?></p>
        <p><?php echo nl2br(wordwrap($Qreviews->valueProtected('reviews_text'), 60, '&shy;')); ?></p>
        <p><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()); ?>" class="noDecoration"><?php echo $lC_Language->get('text_write_review'); ?></a></p>
      <?php
          }
        } else {            
      ?>
        <p><?php echo $lC_Language->get('no_reviews_available'); ?></p>
        <p><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()); ?>" class="noDecoration"><?php echo $lC_Language->get('text_write_review_first'); ?></p>
      <?php
        }
      ?>
      </div>
    </div>
    <!--REVIEWS TAB ENDS-->
    <!--MORE INFO TAB STARTS-->
    <div class="simpleTabsContent">
      <div class="tab_moreinfo">
        <p><?php echo sprintf($lC_Language->get('product_added'), lC_DateTime::getShort($lC_Product->getDateAdded()) ); ?></p>
        <?php
          if ($lC_Product->hasURL()) {
        ?>
        <p><?php echo sprintf($lC_Language->get('go_to_external_products_webpage'), lc_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($lC_Product->getURL()), 'NONSSL', null, false)); ?></p>
        <?php
          }               
        ?>
      </div>
    </div>
    <!--MORE INFO TAB ENDS-->
  </div>
  <!--TABS ENDS-->
  <!--PRODUCT DETAIL ENDS-->

<!--PRODUCT INFO SECTION ENDS-->  