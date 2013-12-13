<?php

/*
  $Id: buttons.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

function button_save_close($save, $close = false, $save_close = true){
	global $lC_Language, $lC_Template;

	?>
	<div class="with-padding align-right">
		<p class="button-height">

		<span class="button-group">

			<button type="button" <?php echo $save; ?> class="button big icon-tick green-gradient"><?php echo $lC_Language->get('button_save'); ?></button>

			<?php if($save_close){ ?>
			<button type="submit" name="save_close" value="true" <?php echo $save; ?>  class="button big icon-squared-cross green-gradient"><?php echo $lC_Language->get('button_save_close'); ?></button>
			<?php } ?>

		</span>

		<?php if($close){ ?>
		<span class="button-group">

			<a href="<?php echo $close; ?>" class="button big icon-cross-round red-gradient"></a>

		</span>
		<?php } ?>

    </p>
 </div>
 <?php if($save_close){ ?>
			<?php //echo lc_draw_hidden_field('save_close', '', 'id="save_close"'); ?>
			<?php } ?>
 <?php
}

?>