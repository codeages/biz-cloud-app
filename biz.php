<?php

use Codeages\Biz\Framework\Context\Biz;
use Codeages\Biz\Framework\Provider\QueueServiceProvider;

$options = array(
    'db.options' => array(
        'dbname' => getenv('DB_NAME') ?: 'biz-cloud-app',
        'user' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: 3306,
        'driver' => 'pdo_mysql',
        'charset' => 'utf8',
    ),
    'redis.options' => array(
        'host' => getenv('REDIS_HOST'),
    ),
    'debug' => true,
    'log_dir' => __DIR__ . '/var/logs',
    'run_dir' => __DIR__ . '/var/run',
    'lock.flock.directory' => __DIR__ . '/var/run',
);

$biz = new Biz($options);


$biz->register(new \Codeages\Biz\CloudApp\CloudAppServiceProvider());
$biz->register(new QueueServiceProvider());
$biz->register(new \Codeages\Biz\Framework\Provider\DoctrineServiceProvider());


$biz->boot();

return $biz;
