<?php

namespace Codeages\Biz\CloudApp\Service;

interface CloudAppService
{
	public function findCloudApps($conditions = array());

	public function findInstalledApps($conditions = array());

	public function installApp($code);

	public function uninstallApp($code);

	public function upgradeApp($code);

    public function getAppByCode($code);
}
