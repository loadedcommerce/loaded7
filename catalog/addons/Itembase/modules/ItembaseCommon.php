<?php
include_once(rtrim(dirname(__FILE__), '/').'/lib/ItembaseCommonAbs.php');

class ItembaseCommon extends ItembaseCommonAbs {

    public function getAccessTokenFromDB($shopId) {
        return (defined('ADDONS_CONNECTORS_ITEMBASE_TOKEN') && @constant('ADDONS_CONNECTORS_ITEMBASE_TOKEN') !== '') ? ADDONS_CONNECTORS_ITEMBASE_TOKEN : '';
    }
    
    public function setAccessTokenToDB($accessToken, $expiresIn, $shopId) {
        global $lC_Database;
        $token = self::jsonEncode(array('access_token' => $accessToken, 'expires_in' => time() + $expiresIn));

        $lC_Database->simpleQuery("update " . TABLE_CONFIGURATION . " set configuration_value = '". $token ."' where configuration_key = 'ADDONS_CONNECTORS_ITEMBASE_TOKEN'");
        lC_Cache::clear('configuration');
    }
}