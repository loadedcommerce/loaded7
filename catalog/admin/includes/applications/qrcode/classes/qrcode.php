<?php
  /**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: branding_manager.php v1.0 2013-08-08 datazen $
  */
  class lC_Qrcode_Admin {
    /*
    * Returns the qrcode image and url
    *
    * @access public
    * @return array
    */
    public static function qrcode() {
      global $lC_Language, $lC_Session, $BarcodeQR;

      $result['html'] = '' . "\n";

      $result['html'] .= '<div id="qr-message" class="message" style="z-index:500">' . "\n";
      $result['html'] .= '<a class="close-qr" title="' . $lC_Language->get('text_hide_message') . '" onclick="$(\'#qr-message\').hide(\'500\');"><span style="color:#fff;">X</span></a>';

      $qrcode_url = $_SERVER['HTTP_REFERER'];
      if(empty($_GET) === false && !array_key_exists($lC_Session->getName(),$_GET)) {     
        $qrcode_url .= '&'.$lC_Session->getName().'='.$lC_Session->getID();
      } else if(!isset($_GET) || empty($_GET)){
        $qrcode_url .= '?'.$lC_Session->getName().'='.$lC_Session->getID();
      }
      $BarcodeQR->url($qrcode_url);
      $BarcodeQR->draw(200, DIR_FS_WORK . 'qrcode/a' . $_SESSION['admin']['id'] . '.png');
      $result['html'] .= '<h5>' . $lC_Language->get('qrcode') . '</h5><img src="../includes/work/qrcode/a' . $_SESSION['admin']['id'] . '.png" /><br /><h6>' . $lC_Language->get('qrcode_current_url') . '</h6><p>' . $qrcode_url . '</p>';
      $result['html'] .= '</div>';
      
      // delete qrcode files which are older then 24hr
      $dir = DIR_FS_WORK . 'qrcode/';
      foreach (glob($dir . "*.png") as $file) {
        /*** if file is 24 hours (86400 seconds) old then delete it ***/
        if (filemtime($file) < time() - 86400) {
          @unlink($file);
        }
      }

      return $result;
    }
  }
?>