<?php
/**
 * OAuth2China for Yii
 *
 * Douban OAuth2.0
 *
 * @link      http://github.com/tlikai/OAuth2China
 * @author    likai<youyuge@gmail.com>
 * @license   http://www.youyuge.com/license New BSD License
 */

class OAuth2China_Provider_Douban extends OAuth2China_Provider_Abstract
{
    protected $options = array(
        'authorizeUrl' => 'https://www.douban.com/service/auth2/auth',
        'accessTokenUrl' => 'https://www.douban.com/service/auth2/token',
    );

    public function resolveAccessToken($token)
    {
        if(isset($token->code))
            throw new CException($token->msg);

        $token = array(
            'provider_uid' => $token->douban_user_id,
            'access_token' => $token->access_token,
            'refresh_token' => $token->refresh_token,
            'expires_in' => $token->expires_in,
        );

        return $token;
    }
}

