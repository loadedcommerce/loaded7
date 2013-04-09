<?php
/**  
  $Id: cresecure-template.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/ 
?>
<html>
<head>
</head>
<body>
<style>
/*form styles*/
#payformContainer { position:relative; width: 100%; margin-left:15px; }
#payformContainer .form-list { color: #544F4B; font-size: 13px; font-family: Arial,Helvetica,sans-serif; }

#payformContainer ul, #payformContainer li{ border:0; margin:0; padding:0; list-style:none; }

#payformContainer li{ clear:both; list-style:none; padding-bottom:10px; }
#payformContainer li > span > span { font-size:.8em; position:absolute; right:36%; }

#payformContainer input{ float:left; width:63%; }
#payformContainer label{ font-weight: bold; width:30%; float:left; } 
#payformContainer select { height:22px; } 
#payformContainer .v-fix { display: inline-block; }   
#payformContainer .form-list label.required em { position:absolute; display:none; }
#name { margin-bottom: 10px; }  
#PAN { margin-bottom: 10px; }  
#payment-buttons-container .required { display: none; }
#payment-buttons-container { width: 40%; float:right; margin-top:10px; }
#payment-button { color:#fff; font-size:14px; font-weight:bold; padding:8px 14px; background:#873b7a !important; border:0px; line-height:100%; cursor:pointer; vertical-align:middle; }
#cancel { font-size:.8em; font-family:Arial,Helvetica,sans-serif; }

#error_message { margin-bottom:10px; }
#error_message span { font-size:.8em; font-family:Arial,Helvetica,sans-serif; }

@media only screen and (max-width: 320px) {
}
*/
</style>
<div id="payformContainer">[[FORM INSERT]]</div>
</body>
</html>