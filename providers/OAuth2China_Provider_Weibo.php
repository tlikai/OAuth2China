<?php
/**
 * OAuth2China for Yii
 *
 * Sina weibo OAuth2.0
 *
 * @link      http://github.com/tlikai/OAuth2China
 * @author    likai<youyuge@gmail.com>
 * @license   http://www.youyuge.com/license New BSD License
 */

class OAuth2China_Provider_Weibo extends OAuth2China_Provider_Abstract
{
    protected $options = array(
        'authorizeUrl' => 'https://api.weibo.com/oauth2/authorize',
        'accessTokenUrl' => 'https://api.weibo.com/oauth2/access_token',
    );

    protected $accessTokenParamsSendBy = self::GET;

    public function resolveAccessToken($token)
    {
        if(isset($token->error))
            throw new CException($token->error);

        $token = array(
            'provider_uid' => $token->uid,
            'access_token' => $token->access_token,
            'refresh_token' => '',
            'expires_in' => $token->expires_in,
        );

        return $token;
    }
}
