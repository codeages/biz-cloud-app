<?php

use Codeages\Biz\Framework\Context\Biz;

$configInfo = require __DIR__.'/app/config.php';

$biz = new Biz();
$biz->register(new \Codeages\UpgradeSdk\UpgradeSdkProvider());
$biz->register(new \Codeages\Biz\Framework\Provider\DoctrineServiceProvider());


$biz->boot();

return $biz;
