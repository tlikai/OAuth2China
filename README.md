# OAuth2China

OAuth2China 是一个支持国内多家社交平台OAuth授权的扩展，需要PHP CURL扩展支持

## OAuth2China支持下列平台

* 新浪微博
* QQ空间
* 豆瓣
* 人人

## 使用方法
```
public function actionAuth($provider)
{
    // 导入OAuth2China
    Yii::import('ext.yii-oauth2china.OAuth2China');

    // 配置各平台参数
    $providers = array(
        'weibo' => array(
            'id' => 'App key',
            'secret' => 'App secret',
        ),
        'qq' => array(
            'id' => 'APP ID',
            'secret' => 'APP KEY',
        ),
        'douban' => array(
            'id' => 'API Key',
            'secret' => 'Secret',
        ),
        'renren' => array(
            'id' => 'API key',
            'secret' => 'Secret key',
        ),
    );

    $OAuth2China = new OAuth2China($providers);

    $provider = $OAuth2China->getProvider('qq'); // getProvider方法的参数对应$providers配置中的key

    if(!isset($_GET['code']))
    {
        // 跳转到授权页面
        $provider->redirect();
    }
    else
    {
        // 获取access token
        $token = $provider->getAccessToken($_GET['code']);
        var_dump($token);
    }
}

```
