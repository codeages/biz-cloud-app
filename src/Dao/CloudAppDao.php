<?php

namespace Codeages\Biz\CloudApp\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface CloudAppDao extends GeneralDaoInterface
{
    public function getByCode($code);

    public function getByType($type);

    public function findAll();

    public function updateByCode($code, $fields);
}
