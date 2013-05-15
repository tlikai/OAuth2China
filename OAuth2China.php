<?php
/**
 * OAuth2China for Yii
 *
 * @link      http://github.com/tlikai/OAuth2China
 * @author    likai<youyuge@gmail.com>
 * @license   http://www.youyuge.com/license New BSD License
 */

Yii::setPathOfAlias('OAuth2China', __DIR__);
Yii::import('OAuth2China.providers.*');

class OAuth2China extends CComponent
{
    /**
     * provider configure
     *
     * @var array
     */
    private $_providers;

    /**
     * supported providers
     *
     * @var array
     */
    public static $supportProviders = array(
        'weibo',
        'douban',
        'renren',
        'qq'
    );

    /**
     * initialize providers
     *
     * @param array
     */
    public function __construct($providers)
    {
        foreach($providers as $name => $provider)
            if(in_array($name, self::$supportProviders))
            {
                if(isset($provider['redirectUri']))
                    $provider['redirectUri'] = Yii::app()->createAbsoluteUrl($provider['redirectUri'], array('provider' => $name));
                else
                {
                    $route = Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;
                    $provider['redirectUri'] = Yii::app()->createAbsoluteUrl($route, array('provider' => $name));
                }
                $this->_providers[$name] = $provider;
            }
    }

    /**
     * get provider by name
     *
     * @public
     * @param string $name
     *
     * @return OAuth2China_Provider
     */
    public function getProvider($name)
    {
        if(isset($this->_providers[$name]))
        {
            $className = 'OAuth2China_Provider_' . ucfirst($name);
            return new $className($this->_providers[$name]);
        }
        throw new CException('OAuth2China: provider does not exist.');
    }

}
