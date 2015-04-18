<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: ck_upload.php v1.0 2013-08-08 datazen $
*/
include_once('../../../includes/config.php');

// set the type of request (https or http)
$request_type = getRequestType();
if ($request_type == 'https') {
  define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
} else {
  define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
}

if(isset($_GET['CKEditor']) && !empty($_GET['CKEditor']) ){
  $CKEditor = $_GET['CKEditor'];
} else {
  $CKEditor = '';
}

// Required: Function number as indicated by CKEditor.
if(isset($_GET['CKEditorFuncNum']) && !empty($_GET['CKEditorFuncNum']) ){
  $funcNum = $_GET['CKEditorFuncNum'];
} else {
  $funcNum = '0';
}

// Optional: To provide localized messages
$langCode = $_GET['langCode'] ;

if(empty($CKEditor)){
  die('Some thing went wrong! Can not proceed.');
}

// ------------------------
// Data processing
// ------------------------

// The returned url of the uploaded file
$image_url = '' ;
// message to show to the user
$message = '';

// the uploaded file sent as 'upload' in CKEditor
  $file_exts = array('jpg', 'bmp', 'jpeg', 'gif', 'png');
  $upload_exts = end(explode('.', $_FILES['upload']['name']));
  if ((($_FILES['upload']['type'] == 'image/jpg')
   || ($_FILES['upload']['type'] == 'image/bmp')
   || ($_FILES['upload']['type'] == 'image/jpeg')
   || ($_FILES['upload']['type'] == 'image/gif')
   || ($_FILES['upload']['type'] == 'image/png'))
   && in_array($upload_exts, $file_exts)){
     if ($_FILES['upload']['error'] > 0){
       $message =  'Error: ' . $_FILES['upload']['error'];
       }else{
         if (file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . $_FILES['upload']['name'])){
           $message =  $_FILES['upload']['name'] . ' already exists. ';
         } else {
           move_uploaded_file($_FILES['upload']['tmp_name'], DIR_FS_CATALOG . DIR_WS_IMAGES . $_FILES['upload']['name']);
           $image_url = DIR_WS_CATALOG . DIR_WS_IMAGES . $_FILES['upload']['name'];
         }
       }
   } else {
     $message =  'Invalid file';
   }

   // We are in an iframe, so we must talk to the object in window.parent
echo "<script type='text/javascript'> window.parent.CKEDITOR.tools.callFunction($funcNum, '$image_url', '$message')</script>";
?>