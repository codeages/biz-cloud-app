<?php

namespace Codeages\Biz\CloudApp;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Codeages\Biz\CloudApp\Client\EdusohoAppClient;

class CloudAppServiceProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
    	$biz['autoload.aliases']['CloudApp'] = 'Codeages\Biz\CloudApp';

    	$biz['console.commands'][] = function () use ($biz) {
            return new \Codeages\Biz\CloudApp\Command\TableCommand($biz);
        };

        $biz['biz_cloud_app.options'] = array(
        	'accessKey' => '',
        	'securitKey' => ''
        );

    	$biz['biz_cloud_app.app_client'] = function () use ($biz) {
            return new EdusohoAppClient($biz);
        };
    }
}