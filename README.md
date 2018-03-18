# 基于Swoole扩展的HTTP异步客户端

[![Latest Version](https://img.shields.io/badge/release-v1.0.0-green.svg?maxAge=2592000)](https://github.com/fastgoo/AsyncHttpClient/releases)
[![Php Version](https://img.shields.io/badge/php-%3E=7.0-brightgreen.svg?maxAge=2592000)](https://secure.php.net/)
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=1.9+-brightgreen.svg?maxAge=2592000)](https://github.com/swoole/swoole-src)

# 简介
基于 Swoole 异步客户端的扩展包，可以像使用 GuzzleHttp 简单优雅的使用swoole的异步客户端，无需关注底层实现以及处理逻辑。可实现同时发起N个HTTP请求不会被阻塞。经测试循环并发100个请求，全部返回结果只需要3-4秒的时间。

- 基于 Swoole 扩展
- 支持HTTPS 与 HTTP 2种协议
- 使用 HTTPS 必须在编译swoole时启用--enable-openssl
- 解决高并发请求（可做接口压测）
- 异步 HTTP 请求，非阻塞


# 参考文档
[**中文文档**](https://wiki.swoole.com/wiki/page/p-http_client.html)


# 环境要求

1. PHP 7.0 +
2. [Swoole 1.9](https://github.com/swoole/swoole-src/releases) +
3. [Composer](https://getcomposer.org/)

# Composer 安装

* `composer require fastgoo/async-http-client`

