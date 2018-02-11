<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/2/11
 * Time: 下午4:03
 */

namespace AsyncClient;

class Client
{
    private $core;

    public function __construct(string $url)
    {
        $this->core = new Core();
        $this->core->init($url);
    }

    /**
     * post异步请求
     * @param string $url
     * @param array $param
     * @param callable $callback
     */
    public function post(string $url, array $param, callable $callback)
    {
        $this->core->request('post', $url, $param, $callback);
    }

    /**
     * get异步请求
     * @param string $url
     * @param array $param
     * @param callable $callback
     */
    public function get(string $url, array $param, callable $callback)
    {
        $this->core->request('get', $url, $param, $callback);
    }

    /**
     * 设置请求头
     * @param array $params
     */
    public function setHeaders(array $params)
    {
        $this->core->setHeaders($params);
    }

    /**
     * 开始请求
     */
    public function request()
    {
        $this->core->send();
    }
}