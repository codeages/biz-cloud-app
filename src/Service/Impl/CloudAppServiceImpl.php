<?php

namespace Codeages\Biz\CloudApp\Service\Impl;


use Codeages\Biz\CloudApp\Service\CloudAppService;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\Framework\Service\BaseService;
use Codeages\Biz\CloudApp\Client\EdusohoAppClient;
use Codeages\Biz\CloudApp\Queue\JobMessage;

class CloudAppServiceImpl extends BaseService implements CloudAppService
{
	public function findCloudApps($conditions = array())
	{
		$cloudApps = $this->getCloudAppClient()->getApps();
		$installedCloudApps = $this->getCloudAppDao()->findAll();
		$installedCloudApps = ArrayToolkit::index($installedCloudApps, 'code');
		foreach ($cloudApps as $key => $cloudApp) {
			if (empty($installedCloudApps[$cloudApp['code']])) {
				$cloudApp['installed_app'] = array();
			} else {
				$cloudApp['installed_app'] = $installedCloudApps[$cloudApp['code']];
			}

			$cloudApps[$key] = $cloudApp;
		}
		return $cloudApps;
	}

	public function findInstalledApps($conditions = array())
	{
		$installedCloudApps = $this->getCloudAppDao()->findAll();
		$cloudApps = $this->getCloudAppClient()->getApps();
		$cloudApps = array_values($cloudApps);
		$cloudApps = ArrayToolkit::index($cloudApps, 'code');
		foreach ($installedCloudApps as $key => $installedCloudApp) {
			if (empty($cloudApps[$installedCloudApp['code']])) {
				$installedCloudApp['cloud_app'] = array();
			} else {
				$installedCloudApp['cloud_app'] = $cloudApps[$installedCloudApp['code']];
			}

			$installedCloudApps[$key] = $installedCloudApp;
		}
		return $installedCloudApps;
	}

	public function checkUpgradeApps($conditions = array())
	{
		$installedCloudApps = $this->getCloudAppDao()->findAll();
		$apps = array();
		foreach ($installedCloudApps as $installedApp) {
            $apps[$installedApp['code']] = $installedApp['version'];
        }

		return $this->getCloudAppClient()->checkUpgradePackages($apps);
	}

	public function installApp($code)
	{
		$installedApp = $this->getAppByCode($code);
		if (!empty($installedApp)) {
			return;
		}

		$cloudApps = $this->getCloudAppClient()->getApps();
		$cloudApps = array_values($cloudApps);
		$cloudApps = ArrayToolkit::index($cloudApps, 'code');
		if (empty($cloudApps[$code])) {
			return;
		}

		$cloudApp = $cloudApps[$code];
		$jobMessage = new JobMessage(array('type' => 'install', 'app' => $cloudApp));
		$this->pushJob($jobMessage);
	}

	public function uninstallApp($code)
	{
		$installedApp = $this->getAppByCode($code);
		if (empty($installedApp)) {
			return;
		}

		$jobMessage = new JobMessage(array('type' => 'uninstall', 'app' => $installedApp));
		$this->pushJob($jobMessage);
	}

	public function upgradeApp($code)
	{
		$installedApp = $this->getAppByCode($code);
		if (empty($installedApp)) {
			return;
		}

		$apps = array($installedApp['code'] => $installedApp['version']);
		$upgradeApps = $this->getCloudAppClient()->checkUpgradePackages($apps);
		if (empty($upgradeApps)) {
			return;
		}
		$upgradeApps = array_values($upgradeApps);
		$upgradeApp = $upgradeApps[0];

		$jobMessage = new JobMessage(array('type' => 'upgrade', 'app' => $upgradeApp));
		$this->pushJob($jobMessage);
	}

	public function getAppByCode($code)
	{
		return $this->getCloudAppDao()->getByCode($code);
	}

	protected function getCloudAppDao()
	{
		return $this->biz->dao('CloudApp:CloudAppDao');
	}

	protected function pushJob($message)
	{
		$this->getQueueService()->pushJob($message, 'database');
	}

	protected function getQueueService()
	{
		return $this->biz->service('Queue:QueueService');
	}

	protected function getCloudAppClient()
	{
		return $this->biz['biz_cloud_app.app_client'];
	}
}