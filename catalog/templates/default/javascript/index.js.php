<?php
/*
  $Id: info.js v1.0 2011-11-04  datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
      $(function(){
        $('#listView').click(function(){
            $('#viewList').show();
            $('#viewGrid').hide();
        });
        $('#gridView').click(function(){
            $('#viewGrid').show();
            $('#viewList').hide();
        });
      });
    </script>
