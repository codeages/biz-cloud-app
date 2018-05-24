<?php

namespace Codeages\Biz\CloudApp\Service\Impl;


use Codeages\Biz\CloudApp\Service\CloudAppService;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\Framework\Service\BaseService;
use Codeages\Biz\CloudApp\Client\EdusohoAppClient;

class CloudAppServiceImpl extends BaseService implements CloudAppService
{
	protected $client;

	public function findCloudApps($conditions = array())
	{
		$cloudApps = $this->getCloudAppClient()->getApps();
		return $cloudApps;
	}

	public function getAppByCode($code)
	{

	}

	protected function getCloudAppClient()
	{
		if (empty($this->client)) {
			$options = $this->biz['biz_cloud_app.options'];
			$this->client = new EdusohoAppClient($this->biz, $options);
		}
		return $this->client;
	}
}