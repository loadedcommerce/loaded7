<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: bs_starter.php v1.0 2013-08-08 datazen $
*/
require($lC_Vqmod->modCheck('templates/bs_starter/classes/bs_starter.php'));
$code = (isset($_SESSION['template']['code']) && $_SESSION['template']['code'] != NULL) ? $_SESSION['template']['code'] : 'bs_starter';
if (!defined('DIR_WS_TEMPLATE')) define('DIR_WS_TEMPLATE', DIR_WS_CATALOG . 'templates/' . $code . '/');
if (!defined('DIR_FS_TEMPLATE')) define('DIR_FS_TEMPLATE', DIR_FS_CATALOG . 'templates/' . $code . '/');
if (!defined('DIR_WS_TEMPLATE_IMAGES')) define('DIR_WS_TEMPLATE_IMAGES', DIR_WS_CATALOG . 'templates/' . $code . '/images/');  
?>
<!DOCTYPE html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo STORE_NAME . ($lC_Template->hasPageTitle() ? ': ' . $lC_Template->getPageTitle() : ''); ?></title>
    <base href="<?php echo lc_href_link(null, null, 'AUTO', false); ?>" />
    <meta name="description" content="Loaded Commerce Shopping Cart">
    <meta name="author" content="Loaded Commerce">
    <!-- include open graph rags -->
    <?php echo $lC_Template->getPageOGPTags(); ?>
    <meta name="viewport" content="content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="ext/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="ext/bootstrap/css/bootstrap-responsive.css">
    
    <!-- Google Font -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    
    <!-- Template CSS -->
    <link rel="stylesheet" href="templates/bs_starter/css/font-awesome.min.css">
    <link rel="stylesheet" href="templates/bs_starter/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="ext/jquery/thickbox/thickbox.css" />
    <link rel="stylesheet" href="templates/bs_starter/css/loadmask.css?v=1">     

    <!--[if lt IE 7]><link href="assets/css/font-awesome-ie7.min.css" rel="stylesheet"><![endif]-->
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script><![endif]-->
    
    <!-- Load Page/Group Specific Tags -->
    <?php
      echo $lC_Template->loadCSS($lC_Template->getCode(), $lC_Template->getGroup());
      if ($lC_Template->hasPageTags()) {
        echo $lC_Template->getPageTags();
      }
    ?>
    
    <!-- fav and touch icons -->
    <link rel="shortcut icon" href="templates/bs_starter/icons/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="templates/bs_starter/icons/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="templates/bs_starter/icons/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="templates/bs_starter/icons/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="templates/bs_starter/icons/apple-touch-icon-57-precomposed.png">
    
    <!-- jQuery is always at top -->
    <script src="ext/jquery/jquery-1.9.1.min.js"></script>
    <script src="ext/bootstrap/bootstrap.min.js"></script>
  </head>

  <body>
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->  
  
    <div class="wrapper">
    
  <div class="navbar navbar-inverse">
    <div class="navbar-inner">
      <div class="container">
        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        <div class="nav-collapse collapse">
          <ul class="nav">
            <li class="active"><a href="#">BRAND HOME</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="nav-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>

          </ul>
          <div class="pull-right">
            <a class="btn btn-mini" style="margin:10px 5px 5px 10px;" href="#">Sign in</a>
            <a class="btn btn-mini" style="margin:10px 10px 5px 0px;" href="#">Sign up</a>
            <div class="btn-group">
              <button class="btn btn-mini">En</button>
              <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" style="min-width:30px;">
                <!-- dropdown menu links -->
                <li><a href="">Ru</a></li>
                <li><a href="">Fr</a></li>
                <li><a href="">Es</a></li>

              </ul>
            </div>
          </div>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </div>



  <!-- PAGE-HEADER-->
  <div class="page-header">
    <div class="container">
      <div class="row-fluid">
        <div class="span3">
          <h1><a class="brand" href="#">SHOP.COM</a></h1>
        </div>
        <div class="span9">
          <div class="row-fluid">
            <div class="span8 text-center">
              <div style="padding-top:20px;">
                <form class="form-search">
                  <div class="input-append">
                    <input type="text" class="span12 search-query">
                    <button type="submit" class="btn">Search</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="span4">
              <p class="text-right" style="padding-top:20px;">
                <a href="#">Saved items</a> | Your Bag: $0.00 (0)
              </p>
            </div>
          </div>
        </div>      

        <div class="row-fluid">
          <div class="span3">
            <div class="well text-center">
              <a href="http://www.bootstraptor.com">MAN</a> / <a href="http://www.bootstraptor.com">WOMAN</a>
            </div>
          </div>
          <div class="span3 hidden-phone">
            <div class="well text-center">
              <a href="http://www.bootstraptor.com">OFFER HERE</a>
            </div>
          </div>
          <div class="span3 hidden-phone">
            <div class="well text-center">
              <a href="http://www.bootstraptor.com">OFFER HERE</a>
            </div>
          </div>
          <div class="span3 hidden-phone">
            <div class="well text-center">
              <a href="http://www.bootstraptor.com">OFFER HERE</a>
            </div>
          </div>
        </div>
      </div>
      <!-- PAGE-HEADER-->
    </div>
  </div>

  <!-- MAIN CONTAINER-->
  <div class="container">
    <div class="row-fluid">
      <!-- LEFT SIDE CATEGORIES-->   
      <div class="span3">         
        <div class="well" >
          <ul id="cat-navi" class="nav nav-list">
            <li class="nav-header">Shop by Product</li>
            <li class="active"><a href="#">Active category</a></li>
            <li><a href="#">New in: Category</a></li>
            <li><a href="#">New in: Category</a></li>
            <li><a href="#">New in: Category</a></li>
            <li><a href="#">New in: Category</a></li>
            <li><a href="#">New in: Category</a></li>
          </ul>

          <hr>

          <ul id="cat-navi2" class="nav nav-list hidden-phone">
            <li class="nav-header">MOST POPURAL</li>
            <li class="active"><a href="#">Active category</a></li>
            <li><a href="#">Category title</a></li>
            <li><a href="#">Category title</a></li>
            <li><a href="#">Category title</a></li>
            <li><a href="#">Category title</a></li>
            <li><a href="#">Category title</a></li>
            <li><a href="#">Category title</a></li>
            <li><a href="#">Category title</a></li>
            <li><a href="#">Category title</a></li>
            <li><a href="#">Category title</a></li>
            <li><a href="#">Category title</a></li>
          </ul>


        </div><!-- /well--> 

        <!-- WELL OFFER-->
        <div class="well">
          <p class="lead">
            SPECIAL OFFER DISCOUNT -20%
          </p>
        </div>
        <!-- / WELL OFFER-->

        <!-- WELL OFFER-->
        <div class="well hidden-phone">
          <p class="lead">
            SPECIAL OFFER DISCOUNT -20%
          </p>
        </div>
        <!-- / WELL OFFER-->

        <!-- WELL OFFER-->
        <div class="well hidden-phone">
          <p class="lead">
            SPECIAL OFFER DISCOUNT -20%
          </p>
        </div>
        <!-- / WELL OFFER-->

        <!-- WELL OFFER-->
        <div class="well hidden-phone">
          <p class="lead">
            SPECIAL OFFER DISCOUNT -20%
          </p>
        </div>
        <!-- / WELL OFFER-->

      </div>
      <!-- /left SIDE--> 

      <!-- CONTENT SIDE-->
      <div class="span9">

        <!-- SLIDER -->
        <div class="row-fluid">
          <div id="myCarousel" class="carousel slide">
            <ol class="carousel-indicators">
              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
              <li data-target="#myCarousel" data-slide-to="1" class=""></li>
              <li data-target="#myCarousel" data-slide-to="2" class=""></li>
            </ol>
            <div class="carousel-inner">
              <div class="item active">
                <img src="images/thumb.jpg" alt="post image" />
                <div class="carousel-caption">
                  <h4>First Thumbnail label</h4>
                  <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                </div>
              </div>
              <div class="item">
                <img src="images/thumb.jpg" alt="post image" />
                <div class="carousel-caption">
                  <h4>Second Thumbnail label</h4>
                  <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                </div>
              </div>
              <div class="item">
                <img src="images/thumb.jpg" alt="post image" />
                <div class="carousel-caption">
                  <h4>Third Thumbnail label</h4>
                  <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                </div>
              </div>
            </div>
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
          </div>
        </div>
        <!-- / SLIDER -->

        <!-- MARKETING LINE-->
        <div class="row-fluid">
          <div class="well">
            <div class="row-fluid">
              <div class="span8">
                <p class="lead text-center">
                  YOUR RECENT MARKETING SLOGAN OR OFFER!
                </p>
              </div>
              <div class="span4">
                <a class="btn btn-warning btn-large btn-block" href="#">BUY NOW!</a>
              </div>

            </div>
          </div>
        </div>
        <!-- /MARKETING LINE-->

        <!-- FEATURED PRODUCTS -->
        <div class="row-fluid articles-grid">
          <!-- ITEM -->
          <div class="span6">
            <div class="thumbnail">
              <!-- IMAGE CONTAINER-->
              <img src="images/thumb.jpg" alt="post image" />
              <!--END IMAGE CONTAINER-->
              <!-- CAPTION -->
              <div class="caption">
                <h3 class="">Featured product title</h3>
                <p class="">This project presents beautiful style graphic &amp; design. Bootstraptor provides modern features</p>
                <p>
                  <a class="" href="#" title="">Read more &rarr;</a>
                </p>
              </div> 
              <!--END CAPTION -->
            </div>
            <!-- END: THUMBNAIL -->
          </div>
          <!-- END ITEM -->

          <!-- ITEM -->
          <div class="span6">
            <div class="thumbnail">
              <!-- IMAGE CONTAINER-->
              <img src="images/thumb.jpg" alt="post image" />
              <!--END IMAGE CONTAINER-->
              <!-- CAPTION -->
              <div class="caption">
                <h3 class="">Featured product title</h3>
                <p class="">This project presents beautiful style graphic &amp; design. Bootstraptor provides modern features</p>
                <p>
                  <a class="" href="#" title="">Read more &rarr;</a>
                </p>
              </div> 
              <!--END CAPTION -->
            </div>
            <!-- END: THUMBNAIL -->
          </div>
          <!-- END ITEM -->

        </div>
        <!-- / FEATURED PRODUCTS -->

        <!-- MARKETING LINE-->
        <div class="row-fluid">
          <div class="well">
            <div class="row-fluid">
              <div class="span8">
                <p class="lead text-center">
                  YOUR RECENT MARKETING SLOGAN OR OFFER!
                </p>
              </div>
              <div class="span4">
                <a class="btn btn-warning btn-large btn-block" href="#">BUY NOW!</a>
              </div>

            </div>
          </div>
        </div>
        <!-- /MARKETING LINE-->


        <!-- MAIN PRODUCTS GRID-->
        <div class="row-fluid container-folio">

          <!-- PROD GRID 
          ============================================================ -->

          <!-- PROD. ITEM -->
          <div class="span4">
            <div class="thumbnail">
              <!-- IMAGE CONTAINER-->
              <img src="images/thumb.jpg" alt="post image" />
              <!--END IMAGE CONTAINER-->
              <!-- CAPTION -->
              <div class="caption">
                <h3 class="">Product title</h3>
                <p class="">This project presents beautiful style graphic &amp; design. Bootstraptor provides modern features</p>

                <div class="row-fluid">
                  <div class="span6">
                    <p class="lead">$21.000</p>
                  </div>
                  <div class="span6">
                    <a class="btn btn-success btn-block" href="#">Add to cart</a>
                  </div>
                </div>
              </div> 
              <!--END CAPTION -->
            </div>
            <!-- END: THUMBNAIL -->
          </div>
          <!-- PROD. ITEM -->

          <!-- PROD. ITEM -->
          <div class="span4">
            <div class="thumbnail">
              <!-- IMAGE CONTAINER-->
              <img src="images/thumb.jpg" alt="post image" />
              <!--END IMAGE CONTAINER-->
              <!-- CAPTION -->
              <div class="caption">
                <h3 class="">Product title</h3>
                <p class="">This project presents beautiful style graphic &amp; design. Bootstraptor provides modern features</p>

                <div class="row-fluid">
                  <div class="span6">
                    <p class="lead">$21.000</p>
                  </div>
                  <div class="span6">
                    <a class="btn btn-success btn-block" href="#">Add to cart</a>
                  </div>
                </div>
              </div> 
              <!--END CAPTION -->
            </div>
            <!-- END: THUMBNAIL -->
          </div>
          <!-- PROD. ITEM -->

          <!-- PROD. ITEM -->
          <div class="span4">
            <div class="thumbnail">
              <!-- IMAGE CONTAINER-->
              <img src="images/thumb.jpg" alt="post image" />
              <!--END IMAGE CONTAINER-->
              <!-- CAPTION -->
              <div class="caption">
                <h3 class="">Product title</h3>
                <p class="">This project presents beautiful style graphic &amp; design. Bootstraptor provides modern features</p>

                <div class="row-fluid">
                  <div class="span6">
                    <p class="lead">$21.000</p>
                  </div>
                  <div class="span6">
                    <a class="btn btn-success btn-block" href="#">Add to cart</a>
                  </div>
                </div>
              </div> 
              <!--END CAPTION -->
            </div>
            <!-- END: THUMBNAIL -->
          </div>
          <!-- PROD. ITEM -->  

          <!-- PROD. ITEM -->
          <div class="span4">
            <div class="thumbnail">
              <!-- IMAGE CONTAINER-->
              <img src="images/thumb.jpg" alt="post image" />
              <!--END IMAGE CONTAINER-->
              <!-- CAPTION -->
              <div class="caption">
                <h3 class="">Product title</h3>
                <p class="">This project presents beautiful style graphic &amp; design. Bootstraptor provides modern features</p>

                <div class="row-fluid">
                  <div class="span6">
                    <p class="lead">$21.000</p>
                  </div>
                  <div class="span6">
                    <a class="btn btn-success btn-block" href="#">Add to cart</a>
                  </div>
                </div>
              </div> 
              <!--END CAPTION -->
            </div>
            <!-- END: THUMBNAIL -->
          </div>
          <!-- PROD. ITEM -->

          <!-- PROD. ITEM -->
          <div class="span4">
            <div class="thumbnail">
              <!-- IMAGE CONTAINER-->
              <img src="images/thumb.jpg" alt="post image" />
              <!--END IMAGE CONTAINER-->
              <!-- CAPTION -->
              <div class="caption">
                <h3 class="">Product title</h3>
                <p class="">This project presents beautiful style graphic &amp; design. Bootstraptor provides modern features</p>

                <div class="row-fluid">
                  <div class="span6">
                    <p class="lead">$21.000</p>
                  </div>
                  <div class="span6">
                    <a class="btn btn-success btn-block" href="#">Add to cart</a>
                  </div>
                </div>
              </div> 
              <!--END CAPTION -->
            </div>
            <!-- END: THUMBNAIL -->
          </div>
          <!-- PROD. ITEM -->  

          <!-- PROD. ITEM -->
          <div class="span4">
            <div class="thumbnail">
              <!-- IMAGE CONTAINER-->
              <img src="images/thumb.jpg" alt="post image" />
              <!--END IMAGE CONTAINER-->
              <!-- CAPTION -->
              <div class="caption">
                <h3 class="">Product title</h3>
                <p class="">This project presents beautiful style graphic &amp; design. Bootstraptor provides modern features</p>

                <div class="row-fluid">
                  <div class="span6">
                    <p class="lead">$21.000</p>
                  </div>
                  <div class="span6">
                    <a class="btn btn-success btn-block" href="#">Add to cart</a>
                  </div>
                </div>
              </div> 
              <!--END CAPTION -->
            </div>
            <!-- END: THUMBNAIL -->
          </div>
          <!-- PROD. ITEM -->

          <!-- / PROD GRID 
          ======================================= -->



        </div>
        <!-- /INNER ROW-FLUID-->
        <hr>
        <!-- PAGINATION-->
        <div class="pagination pull-right">
          <ul>
            <li><a href="#">Prev</a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">Next</a></li>
          </ul>
        </div>
        <!-- /PAGINATION-->
      </div>
      <!-- /CONTENT SIDE-->

    </div>


    <!-- FOOTER-->
    <footer class="row-fluid">
      <div class="span3">
        <h4 class="line3 center standart-h4title"><span>Navigation</span></h4>
        <ul class="footer-links">
          <li><a href="#">Home</a></li>
          <li><a href="#">project</a></li>
          <li><a href="#">Elements</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">Blog</a></li>
        </ul>
      </div>

      <div class="span3">
        <h4 class="line3 center standart-h4title"><span>Useful Links</span></h4>
        <ul class="footer-links">
          <li><a href="http://www.bootstraptor.com">Bootstraptor.com</a></li>
          <li><a href="http://www.bootstraptor.com">Bootstraptor.com</a></li>
          <li><a href="http://www.bootstraptor.com">Bootstraptor.com</a></li>
          <li><a href="http://www.bootstraptor.com">Bootstraptor.com</a></li>
          <li><a href="http://www.bootstraptor.com">Bootstrap templates</a></li>
        </ul>
      </div> 

      <div class="span3">
        <h4 class="line3 center standart-h4title"><span>Useful Links</span></h4>
        <ul class="footer-links">
          <li><a href="http://www.bootstraptor.com">Bootstraptor.com</a></li>
          <li><a href="http://www.bootstraptor.com">Bootstraptor.com</a></li>
          <li><a href="http://www.bootstraptor.com">Bootstraptor.com</a></li>
          <li><a href="http://www.bootstraptor.com">Bootstraptor.com</a></li>
          <li><a href="http://www.bootstraptor.com">Bootstrap templates</a></li>
        </ul>
      </div>

      <div class="span3">
        <h4 class="line3 center standart-h4title"><span>Our office</span></h4>
        <address>
          <strong>bootstraptor.com, LLC.</strong><br>
          <i class="fa-icon-map-marker"></i> 795 Folsom Ave, Suite 600<br>
          San Francisco, CA 94107<br>
          <i class="fa-icon-phone-sign"></i> + 4 (123) 456-7890

        </address>
      </div>

      <div class="span12">
        <hr>
        <p>© bootstraptor.com Company 2013</p>
      </div>

    </footer>
    <!-- /Footer-->


  </div> <!-- /container -->
  <!--/ CONTENT -->


  <script>
    /***************************************************
    responsive menu
    ***************************************************/

    jQuery(function (jQuery) {
      jQuery("#cat-navi").append("<select/>");
      jQuery("<option />", {
        "selected": "selected",
        "value": "",
        "text": "Choose category"
      }).appendTo("#cat-navi select");
      //new dropdown menu
      jQuery("#cat-navi a").each(function () {
        var el = jQuery(this);
        var perfix = '';
        switch (el.parents().length) {
          case (11):
            perfix = '-';
            break;
          case (13):
            perfix = '--';
            break;
          default:
            perfix = '';
            break;

        }
        jQuery("<option />", {
          "value": el.attr("href"),
          "text": perfix + el.text()
        }).appendTo("#cat-navi select");
      });

      jQuery('#cat-navi select').change(function () {

        window.location.href = this.value;

      });
    });

  </script>    
    
    
    
      <?php
/*      
      //moved here to support mobile browse catalog button
      $content_left = '';
      if ($lC_Template->hasPageBoxModules()) {
        ob_start();
        foreach ($lC_Template->getBoxModules('left') as $box) {
          $lC_Box = new $box();
          $lC_Box->initialize();
          if ($lC_Box->hasContent()) {
            if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
              include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
            } else {
              if (file_exists('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php')) {
                include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
              } else {
                include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
              }
            }
          }
          unset($lC_Box);
        }
        $content_left = ob_get_contents();
        ob_end_clean();
      }
      // page header
      if ($lC_Template->hasPageHeader()) {
        if (file_exists('templates/' . $lC_Template->getCode() . '/header.php')) {
          include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/header.php'));
        }
      }                
      ?>
      <div class="section_container">
        <section>
          <?php
          if ($lC_Services->isStarted('breadcrumb')) {
            ?>
            <!--Breadcrumb starts-->  
            <div id="breadCrumbContainer"> 
              <ul class="breadcrumb"><?php echo $lC_Breadcrumb->getPathList(); ?></ul>
            </div>
            <!--Breadcrumb Ends-->
            <?php
          }
          ?>
          <div class="main_content"> 
            <!--Left Side Nav Starts-->
            <?php
            if (!empty($content_left)) {
              echo '<div id="left_side_nav" class="sideNavBox colLeft">';
              echo $content_left;     
              echo '</div>'; 
            }             
            ?>
            <!--Left Side Nav Ends-->  
            <!--Main Content Starts-->  
            <div class="colMid">
              <?php
                if ($lC_MessageStack->size('header') > 0) {
                  echo '<!--Message Stack Header Starts-->';
                  echo $lC_MessageStack->get('header');
                  echo '<!--Message Stack Header Ends-->';
                }
                if ($lC_Template->hasPageContentModules()) {
                  foreach ($lC_Services->getCallBeforePageContent() as $service) {
                    $$service[0]->$service[1]();
                  }
                  foreach ($lC_Template->getContentModules('before') as $box) {
                    $lC_Box = new $box();
                    $lC_Box->initialize();
                    if ($lC_Box->hasContent()) {
                      if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
                        include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php'));
                      } else {
                        if (file_exists('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php')) {
                          include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php'));
                        } else {
                          include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/modules/content/' . $lC_Box->getCode() . '.php'));
                        }
                      }
                    }
                    unset($lC_Box);
                  }
                }
                if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
                  include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename()));
                } else {
                  if (file_exists('templates/' . $lC_Template->getCode() . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename())) {
                    include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename()));
                  } else {
                    include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename()));
                  }
                }
                if ($lC_Template->hasPageContentModules()) {
                  foreach ($lC_Services->getCallAfterPageContent() as $service) {
                    $$service[0]->$service[1]();
                  }
                  foreach ($lC_Template->getContentModules('after') as $box) {
                    $lC_Box = new $box();
                    $lC_Box->initialize();
                    if ($lC_Box->hasContent()) {
                      if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
                        include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php'));
                      } else {
                        if (file_exists('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php')) {
                          include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php'));
                        } else {
                          include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/modules/content/' . $lC_Box->getCode() . '.php'));
                        }
                      }
                    }
                    unset($lC_Box);
                  }
                } 
                ?>
            </div>
            <!--Main Content Ends-->  
            <!--Right Side Nav Starts-->
            <?php
            $content_right = '';
            if ($lC_Template->hasPageBoxModules()) {
              ob_start();
              foreach ($lC_Template->getBoxModules('right') as $box) {
                $lC_Box = new $box();
                $lC_Box->initialize();
                if ($lC_Box->hasContent()) {
                  if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
                    include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
                  } else {
                    if (file_exists('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php')) {
                      include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
                    } else {
                      include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
                    }
                  }
                }
                unset($lC_Box);
              }
              $content_right = ob_get_contents();
              ob_end_clean();
            }
            if (!empty($content_right)) {
              echo '<div id="right_side_nav" class="sideNavBox colRight">';
              echo $content_right;
              echo '</div>';
            }             
            ?>
            <!--Right Side Nav Ends-->
            <div style="clear: both;"></div>
          </div>
          <?php
            // the default css is set for both columns visible until now
            // if only one of the two columns is visible...
            if ( (!empty($content_left) && empty($content_right)) || (empty($content_left) && !empty($content_right)) ) {
          ?>
          <!-- added to assist in the control of the three column width dynamically based on left and right column content :: maestro -->
          <script>
            $(document).ready(function() {
              $(".main_content").find(".colMid").addClass("mid75");
            });
          </script>
          <?php
            // if both columns are empty...
            } else if (empty($content_left) && empty($content_right)) {
          ?>
          <script>
            $(document).ready(function() {
              $(".main_content").find(".colMid").addClass("mid100");
            });
          </script>
          <?php } ?> 
          <div style="clear: both;"></div>          
        </section>
      </div>
      
      <?php
      // page footer
      if ($lC_Template->hasPageFooter()) {
        if (file_exists('templates/' . $lC_Template->getCode() . '/footer.php')) {
          include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/footer.php'));
        }            
        echo '<br />';
        if ($lC_Services->isStarted('banner') && $lC_Banner->exists('468x60')) {
          echo '<p align="center">' . $lC_Banner->display() . '</p>';
        }     
      }  
*/
      
      if ( $lC_Template->showDebugMessages() && ($lC_MessageStack->size('debug') > 0) ) {
        echo '<div id="debugInfoContainer" style="display:none;" class="short-code msg info"><span></span></div>';
      }         
      ?>       
    </div>  
    <!-- Enable responsive features in IE8 with Respond.js (https://github.com/scottjehl/Respond) -->
    <script src="ext/jquery/respond.js"></script>    
    <!-- Core JS -->
    <script src="ext/jquery/jquery.validate.min.js"></script>
    <script src="ext/jquery/jquery.easing.1.3.js"></script>
    <script src="ext/jquery/jquery.hoverIntent.min.js"></script>
    <script src="ext/jquery/jquery.liveSearch.js"></script>
    <script src="ext/jquery/jquery.jBreadCrumb.1.1.js"></script>
    <script src="ext/jquery/thickbox/thickbox-compressed.js"></script>
    <script src="ext/datepicker/datepicker.js"></script>
    <script src="ext/jquery/jquery.loadmask.js"></script>
    <script src="ext/jquery/jquery.activity-indicator-1.0.0.min.js"></script>
    <!-- Template JS -->
    <script src="templates/bs_starter/javascript/placeholder.js" ></script>
    
    <!-- main js.php for all site pages -->
    <?php $lC_Template->addJavascriptPhpFilename('templates/' . $lC_Template->getCode() . '/javascript/general.js.php'); ?>
    
    <!-- js loc: <?php echo 'templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '.js'; ?> -->
    <!-- js.php loc: <?php echo 'templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '/' . $lC_Template->getModule() . '.js.php'; ?> -->
    <?php
      // add group specific (.js) filenames to the array for hasJavascript function (acount, products, info, search, index etc)
      if (file_exists('templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '.js')) {
        $lC_Template->addJavascriptFilename('templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '.js');
      }
      // add module specific (.js.php) filenames to the array for hasJavascript function
      if (file_exists('templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '/' . $lC_Template->getModule() . '.js.php')) {
        $lC_Template->addJavascriptPhpFilename('templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '/' . $lC_Template->getModule() . '.js.php');
      }
      if ($lC_Template->hasJavascript()) {
        $lC_Template->getJavascript();
      }
    ?>
    <script>
      $(document).ready(function(e) {
        var searchUrl = '<?php echo lc_href_link('rpc.php', 'action=search', 'AUTO'); ?>'  
        $('#liveSearchContainer input[name="q"]').liveSearch({url: searchUrl + '&q='});
      }); 
    </script>   
    
  </body>
</html>