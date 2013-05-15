<?php
/**
 * OAuth2China for Yii
 *
 * QQ OAuth2.0
 *
 * @link      http://github.com/tlikai/OAuth2China
 * @author    likai<youyuge@gmail.com>
 * @license   http://www.youyuge.com/license New BSD License
 */

class OAuth2China_Provider_Qq extends OAuth2China_Provider_Abstract
{
    protected $options = array(
        'authorizeUrl' => 'https://graph.qq.com/oauth2.0/authorize',
        'accessTokenUrl' => 'https://graph.qq.com/oauth2.0/token',
    );

    public function resolveAccessToken($token)
    {
        if(is_object($token))
            if(isset($token->msg))
                throw new CException($token->msg);
            if(isset($token->error))
                throw new CException($token->error_description);

        $token = array(
            'provider_uid' => '',
            'access_token' => $token['access_token'],
            'refresh_token' => '',
            'expires_in' => $token['expires_in'],
        );

        return $token;
    }

    protected function resolveResponse($response)
    {
        if(strpos($response, 'callback') !== false)
        {
            preg_match('/callback\(\s+(.+?)\s+\);/', $response, $matches);
            return json_decode($matches[1]);
        }

        $data = array();
        parse_str($response, $data);

        return $data;
    }
}
