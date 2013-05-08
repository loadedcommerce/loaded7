<?php
 /**
  $Id: content.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Language, $lC_Template, $Qpi, $lC_ObjectInfo, $products_name, $products_description, $products_keyword, $products_tags; 
?>
<div id="section_general_content" class="with-padding">
  <div class="columns">
    <div class="new-row-mobile four-columns twelve-columns-mobile">
      <!--<div class="twelve-columns hide-below-768" style="height:38px;">
      &nbsp;
      </div>-->
      <div class="twelve-columns">
        <span class="strong"><?php echo $lC_Language->get('text_product_image'); ?></span>
        <span class="info-spot on-left grey float-right">
          <span class="icon-info-round"></span>
          <span class="info-bubble">
            Put the bubble text here
          </span>
        </span>
        <dl class="accordion same-height small-margin-top">
          <dt><?php echo $lC_Language->get('text_product_image_preview'); ?>
            <!--<div class="button-group absolute-right compact mid-margin-right">
            <a href="#" class="button icon-cloud-upload disabled">Upload</a>
            <a href="#" class="button icon-trash with-tooltip disabled" title="Delete"></a>
            </div>-->
          </dt>
          <dd>
            <div class="with-padding">
              <?php if ($Qpi->value('image')) { ?>
                <div class="prod-image"><img src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>" style="max-width:100%;" /></div>
                <?php } else { ?>
                <div class="prod-image"><img src="images/no-prod-image.png" style="max-width: 100%; height: auto;" /><br />No Image</div>
                <?php } ?>
            </div>
          </dd>
        </dl>
      </div>
    </div>
    <div class="new-row-mobile eight-columns twelve-columns-mobile">             
      <div class="columns">
        <div class="twelve-columns no-margin-bottom">
          <span class="strong small-margin-bottom"><?php echo $lC_Language->get('field_name'); ?></span>
          <span class="info-spot on-left grey float-right small-margin-bottom">
            <span class="icon-info-round"></span>
            <span class="info-bubble">
              Put the bubble text here
            </span>
          </span>
        </div>
        <div class="twelve-columns no-margin-bottom">
          <span class="input full-width">  
            <!-- select name="pseudo-input-select" class="select compact expandable-list float-right prod-edit-lang-select">
              <?php //foreach ( $lC_Language->getAll() as $l ) { ?>
                <option id="<?php //echo $l['code']; ?>" value="<?php //echo $l['code']; ?>"><?php //echo $l['name']; ?></option>
                <?php //} ?>
            </select -->
            <input type="text" class="required input-unstyled" style="width:60%;" value="<?php echo (isset($lC_ObjectInfo) && isset($products_name[$l['id']]) ? $products_name[$l['id']] : null); ?>" id="<?php echo 'products_name[' . $l['id'] . ']'; ?>" name="<?php echo 'products_name[' . $l['id'] . ']'; ?>">
          </span>
        </div>
        <div class="twelve-columns no-margin-bottom mid-margin-top">
          <span class="strong small-margin-bottom"><?php echo $lC_Language->get('field_description'); ?></span>
          <span class="info-spot on-left grey float-right small-margin-bottom">
            <span class="icon-info-round"></span>
            <span class="info-bubble">
              Put the bubble text here
            </span>
          </span>
        </div>
        <div class="twelve-columns no-margin-bottom">
          <?php echo lc_draw_textarea_field('products_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_description[$l['id']]) ? $products_description[$l['id']] : null), null, 10, 'class="required input full-width autoexpanding clEditorProductDescription"'); ?>
          <span class="float-right small-margin-top"><?php echo '<a href="javascript:toggleEditor();">' . $lC_Language->get('text_toggle_html_editor') . '</a>'; ?></span>
        </div>
        <div class="twelve-columns no-margin-bottom mid-margin-top">
          <span class="full-width">
            <span class="strong small-margin-bottom"><?php echo $lC_Language->get('field_keyword'); ?></span>
            <span class="info-spot on-left grey float-right small-margin-bottom">
              <span class="icon-info-round"></span>
              <span class="info-bubble">
                Put the bubble text here
              </span>
            </span>
          </span>
          <script type="text/javascript">
            $(document).ready(function() {
                var pid = '<?php echo $_GET[$lC_Template->getModule()]; ?>';
                var jsonVKUrl = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=validateKeyword&pid=PID'); ?>';
                $("#product").validate({

                    invalidHandler: function() {
                      $("#checkAllTabs").html('<?php echo $lC_Language->get('ms_error_check_all_lang_tabs'); ?>').fadeIn('fast').delay(2000).fadeOut('slow');
                    },
                    rules: {
                      <?php
                        foreach ( $lC_Language->getAll() as $l ) {
                        ?>
                        "products_keyword[<?php echo $l['id']; ?>]": {
                          required: true,
                          remote: jsonVKUrl.replace('PID', pid),

                        },
                        <?php
                        }
                      ?>
                    },
                    messages: {
                      <?php
                        foreach ( $lC_Language->getAll() as $l ) {
                        ?>
                        "products_keyword[<?php echo $l['id']; ?>]": "<?php echo $lC_Language->get('ms_error_product_keyword_exists'); ?>",
                        <?php
                        }
                      ?>
                    }
                });
                <?php
                  if ( isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ) {
                  ?>
                  $("#has_variants").attr('checked', true);
                  <?php
                  }
                ?>
                //$( "button, input:submit, a", ".ui-dialog-buttonset" ).button();
            });
          </script>
          <div class="full-width clear-right mid-margin-bottom">
            <?php echo lc_draw_input_field('products_keyword[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_keyword[$l['id']]) ? $products_keyword[$l['id']] : null), 'class="input full-width" id="keyword' . $l['id'] . '"'); ?>
          </div>
        </div>
        <div class="twelve-columns no-margin-bottom mid-margin-top">
          <span class="full-width">
            <span class="strong small-margin-bottom"><?php echo $lC_Language->get('field_tags'); ?></span>
            <span class="info-spot on-left grey float-right small-margin-bottom">
              <span class="icon-info-round"></span>
              <span class="info-bubble">
                Put the bubble text here
              </span>
            </span>
          </span>
          <div class="full-width clear-right mid-margin-bottom">
            <?php echo lc_draw_input_field('products_tags[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_tags[$l['id']]) ? $products_tags[$l['id']] : null), 'class="input full-width" id="keyword' . $l['id'] . '"'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="field-drop-product button-height black-inputs extreme-margin-bottom">
    <div class="columns">
      <div class="new-row-mobile four-columns twelve-columns-mobile">
      </div>
      <div class="new-row-mobile eight-columns twelve-columns-mobile">
        <div style="width:100%;">
          <div style="float:left;" class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet baseprice-status">
            <span class="full-width">
              <span class="strong"><?php echo $lC_Language->get('field_base_price'); ?></span>
              <span class="info-spot on-left grey float-right mid-margin-top">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
            </span>
            <?php echo lc_draw_input_field('products_price', (isset($lC_ObjectInfo) ? lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES) : null), 'class="input full-width" id="products_price0" onkeyup="updateGross(\'products_price0\')"'); ?>
          </div>
          <div style="float:left;width:2%;">&nbsp;</div>
          <div style="float:left;" class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet baseprice-status">
            <span class="full-width">
              <span class="strong"><?php echo $lC_Language->get('field_status'); ?></span>
              <span class="info-spot on-left grey float-right mid-margin-top">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
            </span><br />
            <span class="button-group">
              <label for="ps_radio_1" class="button blue-active">
                <input type="radio" name="products_status" id="ps_radio_1" value="active"<?php echo ((isset($lC_ObjectInfo) && $lC_ObjectInfo->getInt('products_status') == 1) ? ' checked' : ''); ?> />
                <?php echo $lC_Language->get('field_status_active'); ?>
              </label>
              <label for="ps_radio_2" class="button red-active">
                <input type="radio" name="products_status" id="ps_radio_2" value="inactive"<?php echo ((isset($lC_ObjectInfo) && $lC_ObjectInfo->getInt('products_status') == -1) ? ' checked' : ''); ?> />
                <?php echo $lC_Language->get('field_status_inactive'); ?>
              </label>
              <label for="ps_radio_3" class="button orange-active disabled">
                <input type="radio" name="products_status" id="ps_radio_3" value="recurring"<?php echo ((isset($lC_ObjectInfo) && $lC_ObjectInfo->getInt('products_status') == 0) ? ' checked' : ''); ?> />
                <?php echo $lC_Language->get('field_status_coming'); ?>
              </label>
            </span>
          </div>                  
        </div>                  
      </div>
    </div>
  </div>
  <div class="columns large-margin-top">
    <div class="four-columns twelve-columns-mobile large-margin-bottom">
      <center><img src="images/prodchart.png" /></center>
    </div>
    <div class="four-columns twelve-columns-mobile"> 
      <span class="full-width">
        <span class="strong"><?php echo $lC_Language->get('field_model'); ?></span>
        <span class="info-spot on-left grey float-right">
          <span class="icon-info-round"></span>
          <span class="info-bubble">
            Put the bubble text here
          </span>
        </span>
      </span>
      <p style="background-color:#cccccc;" class="with-small-padding small-margin-top"><b><?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1)) ? $lC_Language->get('text_complex_variants') : (($lC_ObjectInfo->get('products_model') != '') ? $lC_ObjectInfo->get('products_model') : $lC_Language->get('text_no_model')); ?></b></p>
      <!-- span class="full-width">
        <span class="strong"><?php //echo $lC_Language->get('field_date_available'); ?></span>
        <span class="info-spot on-left grey float-right">
          <span class="icon-info-round"></span>
          <span class="info-bubble">
            Put the bubble text here
          </span>
        </span>
      </span>
      <p style="background-color:#cccccc;" class="with-small-padding small-margin-top"><b>Random Date Here</b></p -->              
    </div>
    <div class="four-columns twelve-columns-mobile">
      <span class="full-width">
        <span class="strong"><?php echo $lC_Language->get('field_weight'); ?></span>
        <span class="info-spot on-left grey float-right">
          <span class="icon-info-round"></span>
          <span class="info-bubble">
            Put the bubble text here
          </span>
        </span>
      </span>
      <p style="background-color:#cccccc;" class="with-small-padding small-margin-top"><b><?php echo $lC_ObjectInfo->get('products_weight'); ?></b></p>              
    </div>
  </div>
</div>    