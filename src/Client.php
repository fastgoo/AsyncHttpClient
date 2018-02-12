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

    public function __construct(string $url, int $timeout = 5)
    {
        $this->core = new Core();
        $this->core->init($url);
        $this->core->setConfig(['timeout' => $timeout]);
    }


    /**
     * POST请求
     * @param string $url
     * @param array $params
     * @param callable $callback
     * @return $this
     */
    public function post(string $url, array $params, callable $callback)
    {
        $this->core->requestHttp('POST', $url, $params, $callback);
        return $this;
    }

    /**
     * GET请求
     * @param string $url
     * @param array $params
     * @param callable $callback
     * @return $this
     */
    public function get(string $url, array $params, callable $callback)
    {
        $this->core->requestHttp('GET', $url, $params, $callback);
        return $this;
    }

    /**
     * PUT请求
     * @param string $url
     * @param array $params
     * @param callable $callback
     * @return $this
     */
    public function put(string $url, array $params, callable $callback)
    {
        $this->core->requestHttp('PUT', $url, $params, $callback);
        return $this;
    }

    /**
     * DELETE请求
     * @param string $url
     * @param array $params
     * @param callable $callback
     * @return $this
     */
    public function delete(string $url, array $params, callable $callback)
    {
        $this->core->requestHttp('DELETE', $url, $params, $callback);
        return $this;
    }

    /**
     * 设置请求头
     * @param array $params
     * @return $this
     */
    public function setHeaders(array $params)
    {
        $this->core->setHeaders($params);
        return $this;
    }

    /**
     * 设置COOKIES
     * @param array $cookies
     * @return $this
     */
    public function setCookies(array $cookies)
    {
        $this->core->setCookies($cookies);
        return $this;
    }

    /**
     * 设置配置
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->core->setConfig($config);
        return $this;
    }

    /**
     * 设置请求文件（POST）
     * @param array $params
     * @return $this
     */
    public function setFiles(array $params)
    {
        $this->core->setFiles($params);
        return $this;
    }

    /**
     * 初始化配置
     */
    public function clear()
    {
        $this->core->clearSet();
    }
}