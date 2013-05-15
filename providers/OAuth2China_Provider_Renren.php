<?php
/**
 * OAuth2China for Yii
 *
 * Renren OAuth2.0
 *
 * @link      http://github.com/tlikai/OAuth2China
 * @author    likai<youyuge@gmail.com>
 * @license   http://www.youyuge.com/license New BSD License
 */

class OAuth2China_Provider_Renren extends OAuth2China_Provider_Abstract
{
    protected $options = array(
        'authorizeUrl' => 'https://graph.renren.com/oauth/authorize',
        'accessTokenUrl' => 'https://graph.renren.com/oauth/token',
    );

    public function resolveAccessToken($token)
    {
        if(isset($token->error))
            throw new CException($token->error);

        $token = array(
            'provider_uid' => '',
            'access_token' => $token->access_token,
            'refresh_token' => $token->refresh_token,
            'expires_in' => $token->expires_in,
        );

        return $token;
    }
}

