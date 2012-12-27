<?php
/*
  $Id: access.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$gID = (isset($_GET['gid']) && !empty($_GET['gid'])) ? preg_replace('/[^0-9\s]/', '', trim($_GET['gid'])) : 0;
?>
<style>
.slider.full-width { width: 72%; margin-top: 8px; }
.mark-label { color:#a5a5a5 }
.details-title-text { color:#4c4c4c; font-weight:bold; }
</style>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin large-margin-bottom">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <div class="with-padding">
    <?php
    $modulesArr = array();
    $accessArr = array();
    $sliderOptions    = '{"size":false,"tooltip":false,"innerMarks":25,"step":25,"knob":true,"topMarks":[{"value":0,"label":"' . $lC_Language->get('text_none') . '"},{"value":25,"label":"' . $lC_Language->get('text_view') . '"},{"value":50,"label":"' . $lC_Language->get('text_insert') . '"},{"value":75,"label":"' . $lC_Language->get('text_update') . '"},{"value":100,"label":"' . $lC_Language->get('text_delete') . '"}],"insetExtremes":true,"barClasses":"blue-gradient black","classes":"float-right"}\'';
 //   $genSliderOptions = '{"size":98,"tooltip":false,"innerMarks":25,"step":25,"knob":true,"topMarks":[{"value":0,"label":"' . $lC_Language->get('text_none') . '"},{"value":25,"label":"' . $lC_Language->get('text_view') . '"},{"value":50,"label":"' . $lC_Language->get('text_insert') . '"},{"value":75,"label":"' . $lC_Language->get('text_update') . '"},{"value":100,"label":"' . $lC_Language->get('text_delete') . '"}],"insetExtremes":true,"barClasses":"blue-gradient black"}\'';
    $subSliderOptions = '{"size":false,"topLabel":false,"topMarks":false,"tooltip":false,"innerMarks":25,"step":25,"knob":true,"topMarks":[{"value":0,"label":"' . $lC_Language->get('text_none') . '"},{"value":25,"label":"' . $lC_Language->get('text_view') . '"},{"value":50,"label":"' . $lC_Language->get('text_insert') . '"},{"value":75,"label":"' . $lC_Language->get('text_update') . '"},{"value":100,"label":"' . $lC_Language->get('text_delete') . '"}],"insetExtremes":true,"barClasses":"orange-gradient","classes":"float-right"}\'';
    ?>
    <div id="newGroup">
      <div id="newGroupForm">
        <?php
        if ($gID != 0) { // edit
          echo '<form name="aEdit" id="aEdit" autocomplete="off" action="' . lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&set=groups&process=edit&gid=' . $gID) . '" method="post">';
        } else { // insert
          echo '<form name="aNew" id="aNew" autocomplete="off" action="' . lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&set=groups&process=add') . '" method="post">';
        }
        ?>
        <div class="columns">
          <div class="clear-both new-row six-columns twelve-columns-tablet">
            <p class="button-height inline-label">
              <label for="group_name" class="label"><?php echo $lC_Language->get('field_group_name'); ?></label>
              <input type="text" name="group_name" id="group_name" class="input required">
            </p>
          </div>
          <div class="new-row six-columns twelve-columns-tablet small-margin-top no-margin-bottom">
            <details class="details " id="quick-set-container">
              <summary title="<?php echo $lC_Language->get('text_click_to_expand'); ?>">
                <label id="lbl-quick-set">
                  <span class="details-title-text with-tooltip" title="<?php echo $lC_Language->get('tooltip_quick_set_slider'); ?>" data-tooltip-options='{"classes":["orange-gradient full-width"],"position":"top"}'><?php echo $lC_Language->get('field_quick_set'); ?></span>
                  <input type="text" id="generalSelect" class="access-levels-slider" data-slider-options='<?php echo $sliderOptions; ?>'>
                </label>
              </summary>
            </details>
          </div>
          <div class="new-row twelve-columns small-margin-bottom"><p><hr></p></div>
          <?php
          $cnt = 0;
          foreach(lC_Administrators_Admin::getAccessModules() as $key => $group) {
            $modulesArr[] = urlencode($key);
            if (strtolower($key) == 'access_group_hidden_title') continue;
            $newRow = ($odd == $cnt%2) ? 'new-row ' : NULL;
            ?>
            <div class="<?php echo $newRow; ?>six-columns twelve-columns-tablet">
              <details class="details">
                <summary class="details-summary">
                  <label id="lbl-<?php echo strtolower($key); ?>">
                    <span class="details-title-text"><?php echo $key; ?></span>
                    <input onclick="updateSectionSliders('<?php echo strtolower($key); ?>');" type="text" name="<?php echo strtolower($key); ?>" id="<?php echo strtolower($key); ?>" class="access-levels-slider access-section" data-slider-options='<?php echo $sliderOptions; ?>'>
                  </label>
                </summary>
                <div class="with-padding div-details">
                  <div class="columns">
                    <?php
                    foreach ($group as $gkey => $value) {
                      $ref = strtolower($key) . '-' . $value['id'];
                      $ref = str_replace("'", "", $ref);
                      $accessArr[] = urlencode($ref);
                      echo '<div class="twelve-columns"><label>
                          <span class="details-text"><small>' . $value['text'] . '</small></span>
                          <input name="' . $ref . '" id="' . $ref . '" type="text" class="access-levels-slider" data-slider-options=\'' . $subSliderOptions . '\'>
                          </label></div>';
                    }
                    ?>
                  </div>
                </div>
              </details>
            </div>
            <?php
            $cnt++;
          }
          ?>
          <div class="clear-both"></div>
          <div id="floating-button-container" class="six-columns twelve-columns-tablet">
            <div id="floating-menu-div-listing">
              <div id="buttons-container" style="position: relative;" class="clear-both">
                <div style="float:right;">
                  <p class="button-height" align="right">
                    <a class="button"  href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&set=access' . (($gID != 0) ? '&gid=' . $gID : '')); ?>">
                      <span class="button-icon blue-gradient">
                        <span class="icon-undo"></span>
                      </span><?php echo $lC_Language->get('button_reset'); ?>
                    </a>&nbsp;
                    <?php
                      if ($gID != 0) {
                        echo '<a class="button" href="javascript://" onclick="$(\'#aEdit\').submit();">';
                      } else {
                        echo '<a class="button" href="javascript://" onclick="$(\'#aNew\').submit();">';
                      }
                      ?>
                      <span class="button-icon green-gradient">
                        <span class="icon-download"></span>
                      </span><?php echo $lC_Language->get('button_save'); ?>
                    </a>&nbsp;
                    <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&set=groups'); ?>">
                      <span class="button-icon red-gradient">
                        <span class="icon-cross"></span>
                      </span><?php echo $lC_Language->get('button_cancel'); ?>
                    </a>&nbsp;
                  </p>
                </div>
                <div id="floating-button-container-title" class="hidden">
                  <p class="white big-text small-margin-top"><?php echo $lC_Template->getPageTitle(); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- End main content -->