<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/2/11
 * Time: 下午3:33
 */

namespace AsyncClient;

use Swoole\Async;
use Swoole\Http\Client as HttpClient;

class Core
{
    protected $host;
    protected $ip;
    protected $port = 80;
    protected $config;
    protected $headers = [];
    protected $cookies = [];
    protected $files = [];
    protected $https = false;
    public $requestCallFunc = null;
    public $webSocketCallFunc = null;
    public $onMessage = null;

    public function __construct()
    {
        $this->requestCallFunc = $this->requestCallFunc();
        $this->webSocketCallFunc = $this->webSocketCallFunc();
    }

    /**
     * 解析url参数
     * 初始化配置信息
     * @param string $url
     */
    public function init(string $url)
    {
        $parseArr = parse_url($url);
        $this->host = $parseArr['host'];
        if (filter_var($this->host, FILTER_VALIDATE_IP)) {
            $this->ip = $this->host;
        }
        if ($parseArr['scheme'] == 'https') {
            $this->https = true;
            $this->port = 443;
        }
        if (!empty($parseArr['port'])) {
            $this->port = $parseArr['port'];
        }
    }

    /**
     * 设置配置信息
     * @timeout int 请求超时时间
     * @keep_alive bool 开启关闭超时时间
     * @websocket_mask bool 掩码开启关闭
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * 设置请求头
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * 设置请求文件
     * @param array $params
     * @return $this
     */
    public function setFiles(array $params)
    {
        $this->files = $params;
        return $this;
    }

    /**
     * 设置cookies
     * @param array $cookies
     * @return $this
     */
    public function setCookies(array $cookies)
    {
        $this->cookies = $cookies;
        return $this;
    }

    public function requestHttp(string $method, string $url, array $param, callable $callback)
    {
        if ($this->ip) {
            call_user_func($this->requestCallFunc, [
                'ip' => $this->ip,
                'port' => $this->port,
                'host' => $this->host,
                'https' => $this->https,
                'config' => $this->config,
                'headers' => $this->headers,
                'cookies' => $this->cookies,
                'method' => $method,
                'data' => $param,
                'files' => $this->files,
                'url' => $url,
                'callback' => $callback
            ]);
        } else {
            Async::dnsLookup($this->host, function ($host, $ip) use ($method, $param, $url, $callback) {
                $this->host = $host;
                $this->ip = $ip;
                call_user_func($this->requestCallFunc, [
                    'ip' => $this->ip,
                    'port' => $this->port,
                    'host' => $this->host,
                    'https' => $this->https,
                    'config' => $this->config,
                    'headers' => $this->headers,
                    'cookies' => $this->cookies,
                    'method' => $method,
                    'data' => $param,
                    'files' => $this->files,
                    'url' => $url,
                    'callback' => $callback
                ]);
            });
        }
    }

    /**
     * 清除配置信息
     */
    public function clearSet()
    {
        $this->headers = [];
        $this->cookies = [];
        $this->files = [];
    }

    /**
     * 设置异步回调请求方法
     * @return callable
     */
    private function requestCallFunc(): callable
    {
        return function (array $params) {
            $client = new HttpClient($params['ip'], $params['port'], $params['https']);
            /** 设置请求配置 */
            !empty($params['config']) && $client->set($params['config']);
            /** 默认请求头 */
            $headers = [
                'Host' => $params['host'],
                "User-Agent" => 'Chrome/49.0.2587.3',
                'Accept' => 'text/html,application/xhtml+xml,application/xml,application/json',
                'Accept-Encoding' => 'gzip',
            ];
            $params['headers'] = array_merge($params['headers'], $headers);
            $client->setHeaders($params['headers']);
            /** 设置cookie */
            !empty($params['cookies']) && $client->setCookies($params['cookies']);
            /** 设置请求方法 GET POST PUT DELETE OPTIONS .. */
            $client->setMethod($params['methos']);
            /** 设置请求参数 */
            !empty($params['data']) && $client->setData($params['data']);
            /** 设置请求文件 */
            if (!empty($params['files'])) {
                foreach ($params['files'] as $k => $v) {
                    $client->addFile($v, $k);
                }
                $this->files = [];
            }


            /** 开始请求 */
            $callback = $params['callback'];
            $client->execute($params['url'], function (HttpClient $client) use ($callback) {
                $callback($client);
                $client->close();
            });
        };
    }

    /**
     * @return callable
     */
    private function webSocketCallFunc(): callable
    {
        return function (array $params) {
            $client = new HttpClient($params['ip'], $params['port'], $params['https']);
            /** 设置请求配置 */
            !empty($params['config']) && $client->set($params['config']);
            /** 默认请求头 */
            $headers = [
                'Host' => $params['host'],
                "User-Agent" => 'Chrome/49.0.2587.3',
                'Accept' => 'text/html,application/xhtml+xml,application/xml,application/json',
                'Accept-Encoding' => 'gzip',
            ];
            $params['headers'] = array_merge($params['headers'], $headers);
            $client->setHeaders($params['headers']);
            /** 请求方法 */
            $client->on('message', function ($client, $frame) {
                $this->onMessage($client, $frame);
            });
            $client->upgrade('/', function (HttpClient $client) {
                echo $client->body;
                $client->push("hello world");
            });
        };
    }
}