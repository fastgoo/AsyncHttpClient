<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/2/11
 * Time: 下午3:33
 */

namespace AsyncClient;

use Swoole\Async;

class Core
{
    protected $host;
    protected $ip;
    protected $port = 80;
    protected $request = null;
    protected $headers = [];
    protected $https = false;
    protected $client;

    public function __construct()
    {

    }

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
        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function request(string $method, string $url, array $param, callable $callback)
    {
        $this->request[] = ['method' => $method, 'url' => $url, 'param' => $param, 'callback' => $callback];
    }

    /**
     * 发送
     */
    public function send()
    {
        if ($this->ip) {
            $client = new \Swoole\Http\Client($this->ip, $this->port, $this->https);
            $client->setHeaders($this->headers);
            foreach ($this->request as $value) {
                $client->$value['method']($value['url'], $value['param'], $value['callback']($client));
            }
        } else {
            Async::dnsLookup($this->host, function ($domainName, $ip) {
                $client = new \Swoole\Http\Client($ip, $this->port, $this->https);
                foreach ($this->request as $value) {
                    $client->$value['method']($value['url'], $value['param'], $value['callback']($client));
                }
            });
        }
    }
}