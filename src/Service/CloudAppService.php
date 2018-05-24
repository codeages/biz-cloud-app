<?php

namespace Codeages\Biz\CloudApp\Service;

interface CloudAppService
{
	public function findCloudApps($conditions = array());

    public function getAppByCode($code);
}
