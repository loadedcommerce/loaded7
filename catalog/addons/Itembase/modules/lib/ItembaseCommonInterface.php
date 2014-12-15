<?php

/**
 * itembase plugin core class
 *
 * @author Paweł Kubasiak <pk@itembase.biz>
 * @copyright (c) 2014 Itembase GmbH
 */
interface ItembaseCommonInterface {

    /**
     * Get access token from database
     *
     * @param mixed $shopId
     * @return string
     */
    public function getAccessTokenFromDB($shopId);

    /**
     * Save access token to database
     *
     * @param string $accessToken
     * @param date $expiresIn
     * @param mixed $shopId
     * @return void
     */
    public function setAccessTokenToDB($accessToken, $expiresIn, $shopId); 
    
}
