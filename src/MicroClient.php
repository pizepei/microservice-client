<?php
/**
 * 微服务客户端
 */
namespace pizepei\microserviceClient;


class MicroClient
{
    /**
     * 客户端对象
     * @var array
     */
    protected static $clientObj = [];

    /**
     * 对应每一个app应用实例化一个对象
     * MicroClient constructor.
     * @param \Redis $redis
     * @param array $config
     */
    public function __construct(\Redis $redis, array $config)
    {

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
        if ($config ==[] || !isset($config['appId'])){
            throw new  \Exception('config error');
        }
        # 判断对应应用的客户端是否已经实例化
        if (static::$clientObj[$config['appId']]){
            static::$clientObj[$config['appId']] = new static($redis,$config);
        }
        # 返回实例化对象
        return static::$clientObj[$config['appId']];
    }
}