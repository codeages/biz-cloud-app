<?php

namespace Tests;

use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\User\Service\SecureLevel\HighLevel;

class CloudAppServiceTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $currentUser = array(
            'id' => 1
        );
        $this->biz['user'] = $currentUser;

        $this->biz['biz_cloud_app.options'] = array(
    		'accessKey' => 'V09v97N0cwtVidwAnpZCylBdwUm2O77J',
            'secretKey' => '7gegn2hAyKvRX2rlrqXZMzdiuAliHs1P',
            'apiUrl' => null,
            'debug' => true,
            'host' => 'www.esdev.com',
            'mainAppCode' => 'MAIN',
    	);
    }

    public function testFindCloudApps()
    {
        $cloudApps = $this->getCloudAppService()->findCloudApps();
        var_dump($cloudApps);
    }

    protected function getCloudAppService()
    {
        return $this->biz->service('CloudApp:CloudAppService');
    }
}
