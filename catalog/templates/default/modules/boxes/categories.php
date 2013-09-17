<?php
/**  
*  $Id: categories.php v1.0 2013-01-01 datazen $
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
$_SESSION['setCategoriesMaximumLevel'] = 2; // set max levels = 2
$cPathArr = explode('_', $_GET['cPath']);
$cPathTop = $cPathArr[0];
$cLoc = end(explode("/", $_SERVER['REQUEST_URI']));
?>
<!--modules/boxes/categories.php start-->
<h1><?php echo $lC_Box->getTitle(); ?></h1>
<?php echo $lC_Box->getContent(); ?>
<script>
$(document).ready(function() {
  $('.box-categories-ul-top').addClass('category departments');
  $('.box-categories-ul').addClass('side_sub_menu');
  $('.box-categories-ul li').addClass('menu_cont');
  var cPathTop = 'cPath=<?php echo $cPathTop; ?>';
  var cPath = 'cPath=<?php echo $_GET['cPath']; ?>';
  $('.box-categories-ul-top li a').each(function() {
    // expand the top level
    if (cPathTop == this.href.substr(this.href.indexOf('cPath='))) {
      $(this).addClass('active current');
      $(this).next('ul').attr('style', 'display:block');
    }
    if (cPath == this.href.substr(this.href.indexOf('cPath='))) {
      $(this).addClass('current');    
    }
    var cLoc = this.href.split('/').pop();
    if (cLoc == '<?php echo end(explode("/", $_SERVER['REQUEST_URI'])); ?>') {
      $(this).addClass('active current');
    }
  });
});      
</script>  
<!--modules/boxes/categories.php end-->