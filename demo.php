<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/2/11
 * Time: 下午4:58
 */
include './vendor/autoload.php';

$client = new \AsyncClient\Client("https://open.fastgoo.net",-1);
/*$client->post('/base.api/email/send', [
    'address' => '773729704@qq.com',
    'subject' => '666',
    'body' => '666',
], function (\Swoole\Http\Client $client) {
    var_dump($client->body);
});*/

$client->setFiles(['post'=>__DIR__.'/123.png'])->post('/base.api/file/upload', [
    'address' => '773729704@qq.com',
    'subject' => '666',
    'body' => '666',
], function (\Swoole\Http\Client $client) {
    var_dump($client->body);
});

$client->post('/base.api/file/upload', [
    'address' => '773729704@qq.com',
    'subject' => '666',
    'body' => '666',
], function (\Swoole\Http\Client $client) {
    var_dump($client->body);
});
