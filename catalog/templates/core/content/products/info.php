<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: info.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/products/info.php start-->
<div class="row">
  <div class="col-sm-4 col-lg-4 clearfix">
    <div class="large-margin-top no-margin-bottom text-center">
      <a data-toggle="modal" href="#popup-image-modal" title="<?php echo $lC_Product->getTitle(); ?>"><img class="img-responsive" src="<?php echo $lC_Image->getAddress($lC_Product->getImage(), 'large'); ?>" title="<?php echo $lC_Product->getTitle(); ?>" alt="<?php echo $lC_Product->getTitle(); ?>" /></a>
    </div>  
    <!-- Button trigger modal -->
    <p class="text-center no-margin-top no-margin-bottom"><a data-toggle="modal" href="#popup-image-modal" class="btn normal"><?php echo $lC_Language->get('enlarge_image'); ?></a></p>
    <!-- Modal -->
    <div class="modal fade" id="popup-image-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo $lC_Product->getTitle(); ?></h4>
          </div>
          <div class="modal-body">
            <img class="img-responsive" alt="<?php echo $lC_Product->getTitle(); ?>" src="<?php echo (file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($lC_Product->getImage(), 'originals'))) ? lc_href_link($lC_Image->getAddress($lC_Product->getImage(), 'originals')) : lc_href_link(DIR_WS_IMAGES . 'no_image.png'); ?>">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lC_Language->get('button_close'); ?></button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->    
    <?php
    if (sizeof($lC_Product->getImages()) > 1) {
      $_product_additionalimages = $lC_Product->getAdditionalImagesHtml(); 
      echo '<div class="thumbnail img-responsive"><ul class="pr_gallery">' . $_product_additionalimages['images'] . '</ul></div>' . "\n";
      echo $_product_additionalimages['model']. "\n";
    }
    ?>
  </div>
  <?php
  if ( $lC_Product->hasSubProducts($lC_Product->getID()) === false) {
    ?>  
    <form role="form" class="form-horizontal" name="cart_quantity" id="cart_quantity" action="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&action=cart_add&info=1'); ?>" method="post">
    <?php
  }
  ?>
  <div class="col-sm-8 col-lg-8 clearfix">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php
    $availability = ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->isInStock($lC_Product->getID()) === false) ) ? '<span class="product-out-of-stock red">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>' : $lC_Product->getAttribute('shipping_availability');
    if ($lC_Product->getAttribute('manufacturers') != null || $lC_Product->hasModel()) {
      echo '<div class="content-products-info-manuf-model">' . "\n" . 
           (($lC_Product->getAttribute('manufacturers') != null) ? '<span class="content-products-info-manuf small-margin-right">' . $lC_Product->getAttribute('manufacturers') . ':</span>' . "\n" : null) .
           '  <span class="content-products-info-model">' . $lC_Product->getModel() . '</span>' . "\n" . 
           '</div>' . "\n";
    }
    ?>
    <hr class="small-margin-top small-margin-bottom">
    <p class="content-products-info-desc"><?php echo ($lC_Product->getDescription() != null) ? $lC_Product->getDescription() : $lC_Language->get('no_description_available'); ?></p>
    <?php
    if ( $lC_Product->hasSubProducts($lC_Product->getID()) ) {
      ?>
      <div id="content-products-info-subproducts-container" class="large-margin-top">
        <?php
        echo $lC_Product->parseSubProducts($lC_Product->getSubProducts($lC_Product->getID()));
        ?>
      </div>
      <?php
    }
    ?>      
    <div class="well large-margin-top margin-bottom">
      <?php 
      if ( $lC_Product->hasSubProducts($lC_Product->getID()) === false) {
        ?>
        <div class="content-products-info-price-container clearfix">
          <span class="content-products-info-price pull-left lt-blue"><?php echo $lC_Product->getPriceFormated(true); ?></span>
          <span class="content-products-info-avail with-padding-no-top-bottom"><?php echo $availability ?></span>
        </div>
        <?php
      }
      ?>
      
      <div class="content-products-info-reviews-container large-margin-bottom">
        <label class="content-products-info-reviews-rating-label with-padding-no-top-bottom"><?php echo $lC_Language->get('average_rating'); ?></label>
        <span class="content-products-info-reviews-rating margin-right"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $lC_Product->getData('reviews_average_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $lC_Product->getData('reviews_average_rating'))); ?></span>
        <?php       
        $Qreviews = lC_Reviews::getListing($lC_Product->getID());
        if ($lC_Reviews->getTotal($lC_Product->getID()) > 0) {
          echo '<span><a href="' . lc_href_link(FILENAME_PRODUCTS, 'reviews&' . $lC_Product->getKeyword()) . '">(' . $lC_Language->get('more_information') . ')</a></span>' . "\n";
        } else {            
          echo '<span><a href="' . lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()) . '">(' . $lC_Language->get('text_write_review_first') . '</a>)</span>' . "\n";
        }
        ?>      
      </div>  
      <?php
      if ( $lC_Product->hasSimpleOptions() ) {
        ?>
        <div id="content-products-info-simple-options-container margin-top">
          <?php
          $module = '';
          foreach ( $lC_Product->getSimpleOptions() as $group_id => $value ) {
            if (is_array($value) && !empty($value)) {
              foreach($value as $key => $data) {
                if (isset($data['module']) && $data['module'] != '') {
                  $module = $data['module'];
                }
              }
            }
            echo lC_Variants::parseSimpleOptions($module, $value);
          }
          ?>
        </div>
        <?php
      }
      if ( $lC_Product->hasVariants() ) {
        ?>
        <div id="content-products-info-variants-container">
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
    </div>
  </div>
  <?php 
  if ( $lC_Product->hasSubProducts($lC_Product->getID()) === false) {
    ?>    
    <div id="qpb-message"></div>
    <div class="display-inline">
      <div class="col-sm-8 col-lg-8 align-right mid-margin-top">
        <div class="form-group">
          <label class="content-products-info-qty-label"><?php echo $lC_Language->get('text_add_to_cart_quantity'); ?></label>
          <i class="fa fa-plus-square-o fa-lg" style="position:absolute; right:-1px; top:3px; opacity:.3; cursor:pointer;" onclick="setQty('up');"></i>
          <input type="text" id="quantity" name="quantity" onfocus="this.select();" onchange="refreshPrice();" class="form-control content-products-info-qty-input mid-margin-right" value="1">
          <i class="fa fa-minus-square-o fa-lg" style="position:absolute; right:-1px; top:19px; opacity:.3; cursor:pointer;" onclick="setQty('dn');"></i>
        </div>
      </div>
      <div class="col-sm-4 col-lg-4">
        <p class="margin-top"><button onclick="$('#cart_quantity').submit();" id="btn-buy-now" class="btn btn-block btn-lg btn-success"><?php echo $lC_Language->get('button_buy_now'); ?></button></p>
      </div>
    </div> 
    <?php
  }
  if ( $lC_Product->hasSubProducts($lC_Product->getID()) === false) {
    ?>     
    </form>
    <?php
  }
  ?>
</div>
<script>
$(document).ready(function() {
  var buyNowDisabled = '<?php echo (STOCK_CHECK == '1' && DISABLE_ADD_TO_CART == '1' && $lC_ShoppingCart->isInStock($lC_Product->getID()) === false) ? '1' : '0';  ?>';
  if (buyNowDisabled == '1') {
    $('#main-content-container').addClass('large-margin-top-neg');
  }

  refreshPrice();
});

function setQty(mode) {
  var val = $('#quantity').val();
  if (mode == 'dn') val = parseInt(val) - 1;
  if (mode == 'up') val = parseInt(val) + 1;
  if (parseInt(val) < 1) val = 1;
  $('#quantity').val(val);
  refreshPrice();
}

function refreshPrice() {
  // disable checkout button until ajax finishes loading
  var href = $('#btn-buy-now').attr('onclick');
  $('#btn-buy-now').attr('onclick', '');    
    
  var group = '<?php echo DEFAULT_CUSTOMERS_GROUP_ID; ?>';
  var id = '<?php echo $lC_Product->getID(); ?>';
  var module = '<?php echo $lC_Template->getModule(); ?>';
  var isPro = '<?php echo (utility::isPro() == true) ? 1 : 0 ?>';
  var nvp = $('#cart_quantity').serialize();
  var jsonLink = '<?php echo lc_href_link('rpc.php', 'products&action=getPriceInfo&id=PID&group=GROUP&NVP', 'AUTO'); ?>';   
  $.getJSON(jsonLink.replace('PID', id).replace('GROUP', group).replace('NVP', nvp).split('amp;').join(''),
    function (data) {
    var currencySymbolLeft = '<?php echo $lC_Currencies->getSymbolLeft(); ?>';
      var basePrice = currencySymbolLeft + data.price; 
      if (data.formatted != null) { 
        $('.content-products-info-price').html(data.formatted);
      } else {
        $('.content-products-info-price').html(basePrice);
      }
      if (data.qpbData != undefined && isPro == 1) {
        $('#qpb-message').html('<div class=""><div class="col-sm-4 col-lg-4"></div><div class="col-sm-8 col-lg-8" style="padding:0 15px;"><div class="alert alert-warning small-margin-bottom"><span class="text-left"><i class="fa fa-caret-right"></i> Buy ' + data.qpbData.nextBreak + ' for <b>' + currencySymbolLeft + data.qpbData.nextPrice + '</b> each and <b><i>save ' + data.qpbData.youSave + '</span></i></b></span></div></div></div>');
      }
      $('#btn-buy-now').attr('onclick', href);
    }
  );  
}
</script>
<!--content/products/info.php end-->