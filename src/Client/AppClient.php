<?php

namespace Codeages\Biz\CloudApp\Client;

interface AppClient
{
    /**
     * 获得所有应用包.
     */
    public function getApps();

    /**
     * 检查更新包.
     *
     * @param $appConditions 格式为 array(
     *  '{appCode}' => '{nextVersion}'   //key为app code, value为下一个版本
     * )
     *
     * @return array(
     *                array(
     *                'id' => '159',
     *                'type' => 'app',
     *                'category' => 'theme',
     *                'name' => '零主题',
     *                'code' => 'Zero',
     *                'userAccess' => 'ok',
     *                'developerName' => '从零开始',
     *                'description' => '从零开始',
     *                'icon' => 'Zero/icon22550c3c.jpg',
     *                'appScreenshot' => '|/files/product/Zero/a.jpg|/files/product/Zero/b.jpg',
     *                'price' => '1899.00',
     *                'isLicensed' => '0',
     *                'onlyForBusiness' => '0',
     *                'publishStatus' => 'published',
     *                'publishedPackageId' => '1279',
     *                'upgradeStatus' => 'none',
     *                'applyPackageId' => '0',
     *                'edusohoEdition' => 'opensource',
     *                'edusohoMinVersion' => '8.2.12',
     *                'edusohoMaxVersion' => 'up',
     *                'latestVersion' => '3.1.7',
     *                'latestTime' => '1522203839',
     *                'developerId' => '4395',
     *                'lastAuditedTime' => '1522203839',
     *                'closedTime' => '0',
     *                'isClosed' => '0',
     *                'isRecommend' => '0',
     *                'createdTime' => '1491918479',
     *                'licensed' => '0',
     *                'status' => 'published',
     *                'latestPackageId' => '1279',
     *                'package' => array(
     *                'id' => '1065',
     *                'productId' => 159',
     *                'status' => 'used',
     *                'fromVersion' => '2.2.2',
     *                'toVersion' => '2.2.3',
     *                'edusohoMinVersion' => '8.0.23',
     *                'edusohoMaxVersion' => 'up',
     *                'fileName' => 'opensource/Zero/Zero_install_2.2.3.zip',
     *                'fileHash' => '',
     *                'description' => 'description',
     *                'isBackupFile' => '0',
     *                'isBackupDB' => '0',
     *                'developerId' => '4395',
     *                'createdTime' => '1501425500',
     *                'releaseType' => 'all',
     *                'backupDB' => '0',
     *                'backupFile' => '0',
     *                'type' => 'install',
     *                )
     *                ), array(
     *                ...
     *                ) ...
     *                )
     */
    public function checkUpgradePackages($appConditions);

    /**
     * 提交应用包升级／安装日志数据.
     */
    public function submitRunLog($log);

    /**
     * 下载应用包.
     */
    public function downloadPackage($packageId);

    /**
     * 检查是否有权限下载应用.
     */
    public function checkDownloadPackage($packageId);

    /**
     * 获得包信息.
     */
    public function getPackage($id);
}
