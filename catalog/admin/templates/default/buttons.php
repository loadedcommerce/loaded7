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

function button_save_close($save = false, $close = true, $save_close = false){
	global $lC_Language, $lC_Template;

	?>
	<?php if($save_close || $save){ ?>
	<span class="button-group">

		<?php if($save){ ?>
		<button type="button" <?php echo $save; ?> class="button big icon-tick green-gradient"><?php echo $lC_Language->get('button_save'); ?></button>
		<?php } ?>
		
		<?php if($save_close){ ?>
		<button type="submit" name="save_close" value="true" <?php echo $save; ?>  class="button big icon-squared-cross green-gradient"><?php echo $lC_Language->get('button_save_close'); ?></button>
		<?php } ?>

	</span>
	<?php } ?>
	
	<?php if($close){ ?>
	<span class="button-group">

		<a href="<?php echo $close; ?>" class="button big icon-cross-round red-gradient"></a>

	</span>
	<?php } ?>
 <?php
}

?>