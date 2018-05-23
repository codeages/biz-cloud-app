<?php

namespace Codeages\Biz\UpgradeSdk;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Upgrade\Common\Util\UpgradeUtils;
use Codeages\Upgrade\Common\Exception\AccessException;

class UpgradeSdkProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
    	$biz['autoload.aliases']['UpgradeSdk'] = 'Codeages\Biz\UpgradeSdk';
    }
}