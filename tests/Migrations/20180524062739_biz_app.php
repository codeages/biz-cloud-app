<?php

use Phpmig\Migration\Migration;

class BizApp extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];


        $connection->exec("
            CREATE TABLE `biz_cloud_app` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '云应用ID',
              `name` varchar(255) NOT NULL COMMENT '云应用名称',
              `code` varchar(64) NOT NULL COMMENT '云应用编码',
              `type` varchar(64) NOT NULL DEFAULT 'plugin' COMMENT '应用类型(core系统，plugin插件应用, theme主题应用)',
              `protocol` tinyint(3) unsigned NOT NULL DEFAULT '2',
              `version` varchar(32) NOT NULL COMMENT '云应用当前版本',
              `from_version` varchar(32) NOT NULL DEFAULT '0.0.0' COMMENT '云应用更新前版本',
              `created_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装者',
              `created_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
              `updated_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `code` (`code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='已安装的应用';
        ");
    }

    protected function isFieldExist($table, $filedName)
    {
        $biz = $this->getContainer();
        $db = $biz['db'];

        $sql = "DESCRIBE `{$table}` `{$filedName}`;";
        $result = $db->fetchAssoc($sql);

        return empty($result) ? false : true;
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];
        $connection->exec("
            drop table biz_cloud_app;
        ");
    }
}
