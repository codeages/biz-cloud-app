<?php

namespace Codeages\Biz\CloudApp\Dao\Impl;

use Codeages\Biz\Framework\Dao\GeneralDaoImpl;
use Codeages\Biz\CloudApp\Dao\CloudAppDao;

class CloudAppDaoImpl extends GeneralDaoImpl implements CloudAppDao
{
    protected $table = 'biz_cloud_app';

    public function getByCode($code)
    {
        return $this->getByFields(array(
            'code' => $code,
        ));
    }

    public function getByType($type)
    {
        return $this->getByFields(array(
            'type' => $type,
        ));
    }

    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db()->fetchAll($sql, array());
    }

    public function updateByCode($code, $fields)
    {
        return $this->update(array('code' => $code), $fields);
    }

    public function declares()
    {
        return array(
            'orderbys' => array(
                'created_time',
            ),
            'timestamps' => array(
                'created_time',
                'updated_time',
            ),
            'conditions' => array(
                'code = :code',
            ),
        );
    }
}
