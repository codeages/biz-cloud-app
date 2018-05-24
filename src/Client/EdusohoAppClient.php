<?php

namespace Codeages\Biz\CloudApp\Client;

use Pimple\Container;

class EdusohoAppClient implements AppClient
{
    protected $userAgent = 'Open EduSoho App Client 1.0';

    protected $connectTimeout = 5;

    protected $timeout = 5;

    private $apiUrl = 'http://open.edusoho.com/app_api';

    private $debug = false;

    /**
     * @var string
     */
    private $accessKey;

    /**
     *  Container
     */
    private $biz;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $host;

    /**
     * tmp dir path.
     *
     * @var string
     */
    private $tmpDir;

    public function __construct(Container $biz, array $options)
    {
        $this->biz = $biz;
        $this->accessKey = empty($options['accessKey']) ? 'Anonymous' : $options['accessKey'];
        $this->secretKey = empty($options['secretKey']) ? '' : $options['secretKey'];
        $this->host = $options['host'];

        $this->debug = empty($options['debug']) ? false : true;
        $this->tmpDir = empty($options['tmpDir']) ? sys_get_temp_dir() : $options['tmpDir'];
    }

    public function getApps()
    {
        $args = array();
        //GetAppCenter
        return $this->callRemoteApi('GET', 'GetAppCenter', $args);
    }

    /**
     * @see AppClient::checkUpgradePackages
     */
    public function checkUpgradePackages($appConditions)
    {
        $extInfos = array('_t' => (string) time());
        $args = array('apps' => $appConditions, 'extInfo' => $extInfos);

        $apps = array();
        do {
            $apps = $this->callRemoteApi('POST', 'CheckUpgradePackages', $args);
            static $time = 0;
            if ($apps) {
                break;
            }
            sleep(1);
            $time += 1;
        } while ($time < 3);

        $upgradableApps = array_filter($apps, function ($app) {
            return $this->isAppAccessable($app); //无访问权限的过滤掉
        });

        return $upgradableApps;
    }

    public function submitRunLog($log)
    {
        $args = array('log' => $log);

        return $this->callRemoteApi('POST', 'SubmitRunLog', $args);
    }

    public function downloadPackage($packageId)
    {
        $args = array('packageId' => (string) $packageId);
        list($url, $httpParams) = $this->assembleCallRemoteApiUrlAndParams('DownloadPackage', $args);
        $url = $url.(strpos($url, '?') ? '&' : '?').http_build_query($httpParams);

        return $this->download($url);
    }

    public function checkDownloadPackage($packageId)
    {
        $args = array('packageId' => (string) $packageId);

        return $this->callRemoteApi('GET', 'CheckDownloadPackage', $args);
    }

    public function getPackage($id)
    {
        $args = array('packageId' => (string) $id);

        return $this->callRemoteApi('GET', 'GetPackage', $args);
    }

    protected function callRemoteApi($httpMethod, $action, array $args)
    {
        list($url, $httpParams) = $this->assembleCallRemoteApiUrlAndParams($action, $args);
        $result = $this->sendRequest($httpMethod, $url, $httpParams);

        return json_decode($result, true);
    }

    protected function assembleCallRemoteApiUrlAndParams($action, array $args)
    {
        $url = "{$this->apiUrl}?action={$action}";
        $edusoho = array(
            'edition' => 'opensource',
            'host' => $this->host,
            'version' => '8.2.0',
            'debug' => $this->debug ? '1' : '0',
        );
        $args['_edusoho'] = $edusoho;

        $httpParams = array();
        $httpParams['accessKey'] = $this->accessKey;
        $httpParams['args'] = $args;
        $httpParams['sign'] = hash_hmac('sha1', base64_encode(json_encode($args)), $this->secretKey);

        return array($url, $httpParams);
    }

    protected function download($url)
    {
        $filename = md5($url).'_'.time();
        $filepath = $this->tmpDir.DIRECTORY_SEPARATOR.$filename;

        $fp = fopen($filepath, 'w');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FILE, $fp);
        curl_exec($curl);
        curl_close($curl);

        fclose($fp);

        return $filepath;
    }

    protected function sendRequest($method, $url, $params = array())
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        if ('POST' == strtoupper($method)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            $params = http_build_query($params);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        } else {
            if (!empty($params)) {
                $url = $url.(strpos($url, '?') ? '&' : '?').http_build_query($params);
            }
        }

        curl_setopt($curl, CURLOPT_URL, $url);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
    
    private function isAppAccessable($app)
    {
        return isset($app['userAccess']) && 'ok' == $app['userAccess'];
    }

    private function isAppBuyable($app)
    {
        return isset($app['buyable']) && $app['buyable'];
    }

    private function isAppTriable($app)
    {
        return isset($app['triable']) && $app['triable'];
    }
}
