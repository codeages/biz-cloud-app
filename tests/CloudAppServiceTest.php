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

        $this->biz['biz_cloud_app.options'] = require 'config.php'; 
    }

    public function testFindCloudApps()
    {
        $cloudApp = $this->mockCloudApp();
        $this->getCloudAppDao()->create($cloudApp);
        $cloudApps = $this->getCloudAppService()->findCloudApps();
        $this->assertNotEmpty($cloudApps);
    }

    public function testFindInstalledApps()
    {
        $cloudApp = $this->mockCloudApp();
        $this->getCloudAppDao()->create($cloudApp);
        $installedApps = $this->getCloudAppService()->findInstalledApps();
        $this->assertNotEmpty($installedApps);
    }

    public function testCheckUpgradeApps()
    {
        $cloudApp = $this->mockCloudApp();
        $this->getCloudAppDao()->create($cloudApp);

        $installedApps = $this->getCloudAppService()->checkUpgradeApps();
        $this->assertNotEmpty($installedApps);
    }

    public function testInstallApp()
    {
        $this->getCloudAppService()->installApp('Discount');
        $count = $this->getQueueDao()->count(array());
        $this->assertNotEmpty($count);
    }

    public function testUpgradeApp()
    {
        $cloudApp = $this->mockCloudApp();
        $this->getCloudAppDao()->create($cloudApp);

        $this->getCloudAppService()->upgradeApp('Discount');
        $count = $this->getQueueDao()->count(array());
        $this->assertNotEmpty($count);
    }

    protected function mockCloudApp()
    {
        $cloudApp = array(
            'name' => '打折活动',
            'code' => 'Discount',
            'type' => 'app',
            'version' => '1.2.4',
            'from_version' => '1.2.3',
        );
        return $cloudApp;
    }

    protected function getCloudAppService()
    {
        return $this->biz->service('CloudApp:CloudAppService');
    }

    protected function getQueueDao()
    {
        return $this->biz->dao('Queue:JobDao');
    }

    protected function getCloudAppDao()
    {
        return $this->biz->dao('CloudApp:CloudAppDao');
    }
}
