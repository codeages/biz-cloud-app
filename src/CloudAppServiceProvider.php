<?php

namespace Codeages\Biz\CloudApp;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Upgrade\Common\Util\UpgradeUtils;
use Codeages\Upgrade\Common\Exception\AccessException;

class CloudAppServiceProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
    	$biz['autoload.aliases']['CloudApp'] = 'Codeages\Biz\CloudApp';

        $biz['biz_cloud_app.options'] = array(
        	'accessKey' => '',
        	'securitKey' => ''
        );
    }
}