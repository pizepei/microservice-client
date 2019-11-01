<?php
/**
 * 微服务客户端
 */
namespace pizepei\microserviceClient;

use pizepei\encryption\aes\Prpcrypt;

class MicroClient
{
    /**
     * 客户端对象
     * @var array
     */
    protected static $clientObj = [];
    /**
     * 配置
     * @var array
     */
    private $config = [];
    /**
     * 缓存
     * @var \Redis
     */
    private $redis = [];
    /**
     * 对应每一个app应用实例化一个对象
     * MicroClient constructor.
     * @param \Redis $redis
     * @param array $config
     */
    public function __construct(\Redis $redis, array $config)
    {
        # 基础配置
        $this->config = $config;
        # 缓存配置
        $this->redis = $redis;
    }
    /**
     * @Author 皮泽培
     * @Created 2019/10/21 11:06
     * @param \Redis $redis
     * @param array $config
     * @return MicroClient
     * @throws \Exception
     * @title  路由标题
     * @explain 路由功能说明
     */
    public static function init(\Redis $redis, array $config)
    {
        if ($config ==[] || !isset($config['appid'])){
            throw new  \Exception('config error');
        }
        # 判断对应应用的客户端是否已经实例化
        if (!isset(static::$clientObj[$config['appid']])){
            static::$clientObj[$config['appid']] = new static($redis,$config);
        }
        # 返回实例化对象
        return static::$clientObj[$config['appid']];
    }

    /**
     * 请求接口
     * @param $param
     * @return array
     * @throws \Exception
     */
    public function send($param)
    {
        # url
        $url = $this->config['url'].$this->config['api'].$this->config['appid'].'.json';
        # 确定数据
        $Prpcrypt = new  Prpcrypt($this->config['encodingAesKey']);

        $data = $Prpcrypt->yieldCiphertext(Helper()->json_encode($param),$this->config['appid'],$this->config['token']);
        $res = Helper()->httpRequest($url,Helper()->json_encode($data));
        if ($res['code'] !== 200){throw new \Exception('httpRequest error  '.$res['code']);}
        $body = Helper()->json_decode($res['body']);
        if (empty($body)){throw new \Exception('body empty '.$res['body']);}
        # 处理一些特殊的错误码
        return $body;
    }


}