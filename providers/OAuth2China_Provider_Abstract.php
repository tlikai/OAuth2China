<?php
/**
 * OAuth2China for Yii
 *
 * OAuth customer base class
 *
 * @link      http://github.com/tlikai/OAuth2China
 * @author    likai<youyuge@gmail.com>
 * @license   http://www.youyuge.com/license New BSD License
 */

abstract class OAuth2China_Provider_Abstract
{
    const GET = 0;
    const POST = 1;

    /**
     * OAuth2.0 client id
     * @var string
     */
    protected $id;

    /**
     * OAuth2.0 client secret
     * @var string
     */
    protected $secret;

    /**
     * OAuth2.0 redirect uri
     * @var string
     */
    protected $redirectUri;

    /**
     * OAuth2.0 scope
     * @var string
     */
    protected $scope;

    /**
     * OAuth2.0 response type
     * @var string
     */
    protected $responseType = 'code';

    /**
     * OAuth2.0 response type
     * @var string
     */
    protected $grantType = 'authorization_code';

    /**
     * OAuth2.0 state
     * @var string
     */
    protected $state = null;

    /**
     * OAuth2.0 server urls
     * @var array
     */
    protected $options = array(
        'authorizeUrl' => '',
        'accessTokenUrl' => '',
    );

    /**
     * Get access token request method
     *
     * Compatible sina oauth2.0
     */
    protected $accessTokenParamsSendBy = self::POST;

    /**
     * Access token
     * @var array
     */
    public $token;


    /**
     * Initialize OAuth2China provider
     *
     * @param array $config
     */
    public function __construct($config)
    {
        foreach($config as $key => $val)
            $this->$key = $val;
    }

    /**
     * Redirect to authorize page
     */
    public function redirect()
    {
        header('Location: ' . $this->getAuthorizeUrl());
    }

    /**
     * Return authorize url
     *
     * @return string
     */
    public function getAuthorizeUrl()
    {
        return $this->options['authorizeUrl'] . '?' . http_build_query(array(
            'client_id' => $this->id,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->state,
            'scope' => $this->scope,
            'response_type' => $this->responseType,
        ));
    }

    /**
     * Get access token
     *
     * @param string $code
     *
     * @return array
     */
    public function getAccessToken($code)
    {
        $params = array(
            'client_id' => $this->id,
            'client_secret' => $this->secret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => $this->grantType,
            'code' => $code,
        );

        if($this->accessTokenParamsSendBy == self::POST)
            $token = $this->sendRequest($this->options['accessTokenUrl'], $params, self::POST);
        else
            $token = $this->sendRequest($this->options['accessTokenUrl'] . '?' . http_build_query($params), array(), self::POST);

        $this->token = $this->resolveAccessToken($token);

        return $this->token;
    }

    /**
     * Resolve difference of provider return access token
     *
     * @param array $token
     */
    abstract protected function resolveAccessToken($token);

    /**
     * Send http request
     *
     * @param string $url
     * @param array $data request data
     * @param boolean $post http request method
     *
     * @return array
     */
    protected function sendRequest($url, $data = array(), $method = self::GET)
    {
        $ch = curl_init();

        if($method == self::POST)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        else
            $url .= (strpos($url, '?') !== false ? '&' : '?') . http_build_query($data);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        $response = curl_exec($ch);
        $headers = curl_getinfo($ch);
        if(curl_errno($ch) > 0)
            throw new CException(curl_error($ch));
        curl_close($ch);

        return $this->resolveResponse($response);
    }

    /**
     * Resolve response
     *
     * @param string $response
     *
     * @return array
     */
    protected function resolveResponse($response)
    {
        return json_decode($response);
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($State)
    {
        $this->state= $state;
    }

    public function getResponseType()
    {
        return $this->responseType;
    }

    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;
    }

    public function getGrantType()
    {
        return $this->grantType;
    }

    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
    }
}
