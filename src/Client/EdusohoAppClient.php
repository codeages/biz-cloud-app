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

    public function __construct(Container $biz)
    {

        $this->biz = $biz;

        $options = $biz['biz_cloud_app.options'];
        $this->accessKey = empty($options['accessKey']) ? 'Anonymous' : $options['accessKey'];
        $this->secretKey = empty($options['secretKey']) ? '' : $options['secretKey'];
        $this->host = $options['host'];
        $this->debug = empty($options['debug']) ? false : true;
    }

    public function getApps()
    {
        $args = array();
        return $this->callRemoteApi('GET', 'GetAppCenter', $args);
    }

    public function checkUpgradePackages($apps)
    {
        $extInfos = array('_t' => (string) time());
        $args = array('apps' => $apps, 'extInfo' => $extInfos);
        return $this->callRemoteApi('POST', 'CheckUpgradePackages', $args);
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
}
