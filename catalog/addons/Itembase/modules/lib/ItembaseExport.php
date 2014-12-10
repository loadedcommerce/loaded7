<?php
include_once(rtrim(dirname(__FILE__), '/') . '/../ItembaseCommon.php');
class ItembaseExport extends ItembaseCommon {

    private $hash          = null;
    private $apiMethod     = null;
    private $executionTime = 600;
    private $serverApiMethod;
    private $exportType     = null;
    private $dataVersion = null;

    public $recordOffset   = 0;
    public $recordMax      = 1000;
    public $lastDate       = null;



    public function __construct($apiKey, $secretKey, $debug, $shopId = null, $exportProducts = false, $exportOrders = false)
    {
        $this->validateParameters($apiKey, $secretKey);

        $this->hash  = $_GET['ib_hash'];
        $this->apiMethod = $_GET['ib_method'];

        switch ($this->apiMethod) {
            case 'getProducts' :
                if (!filter_var($exportProducts, FILTER_VALIDATE_BOOLEAN)) {
                    $this->notifyError('Export products option disable');
                }
                $this->exportType =  'products';
                $this->serverApiMethod = 'products';
                $this->dataVersion = ITEMBASE_PRODUCT_DF_VERSION;
                break;
            case 'getOrders' :
                if (!filter_var($exportOrders, FILTER_VALIDATE_BOOLEAN)) {
                    $this->notifyError('Export orders option disable');
                }

                $this->exportType =  'orders';
                $this->serverApiMethod = 'transactions';
                $this->dataVersion = ITEMBASE_TRANSACTION_DF_VERSION;
                break;
            case 'check' :
                die((string) $this->executionTime);
            default:
                $this->notifyError('Wrong method name');
        }

        if (isset($_GET['ib_offset'])) {
            $this->recordOffset = (int) $_GET['ib_offset'];
        }

        if (isset($_GET['ib_max'])) {
            $this->recordMax = (int) $_GET['ib_max'];
        }

        if (isset($_GET['ib_last_date'])) {
            $this->lastDate = (int) $_GET['ib_last_date'];
        }

        $debug = (isset($_GET['ib_debug'])) ? $_GET['ib_debug'] : 0;
        $this->executionTime = $this->setServerMaxExecutionTime();

        parent::__construct($apiKey, $secretKey, $debug, $shopId);
    }

    private function validateParameters($apiKey, $secretKey)
    {
        if (!isset($_GET['ib_hash'])) {
            $this->notifyError('Missing ib_hash param');
        }
        if (!isset($_GET['ib_method'])) {
            $this->notifyError('Missing ib_method param');
        }
        if (empty($secretKey)) {
            $this->notifyError('Itembase secret key not set');
        }
        if (empty($apiKey)) {
            $this->notifyError('Itembase api key not set');
        }
        if (!$this->checkHash($apiKey, $secretKey, $_GET['ib_hash'])) {
            $this->notifyError('different keys');
        }
    }

    public function getExportType()
    {
        return $this->exportType;
    }

    public function execute($data)
    {
        $countData = count($data);
        if ($countData > 0) {
            $this->sendData($data);
        }
        $this->notifySuccess($countData, 'export');
    }

    private function sendData($data)
    {
        $data = $this->removeEmptyValues($data);
        self::utf8EncodeRecursive($data);
        $jsonData = self::jsonEncode($data);

        if (extension_loaded('curl')) {
            $ibCurl = curl_init();
            curl_setopt(
                $ibCurl,
                CURLOPT_URL,
                ITEMBASE_SERVER_API . '/' . ITEMBASE_API_VERSION . '/' . $this->serverApiMethod . '?df=' . $this->dataVersion . '&token=' . $this->getAccessToken()
            );
            curl_setopt($ibCurl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ibCurl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ibCurl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ibCurl, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ibCurl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $ibCurl,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($jsonData)
                )
            );
            $result = curl_exec($ibCurl);
            $httpCode = curl_getinfo($ibCurl, CURLINFO_HTTP_CODE);

            if ($httpCode !== 202) {
                $this->notifyError($result, 'Api[' . $httpCode. ']');
            }

            if ($result === false) {
                $this->notifyError(curl_error($ibCurl), 'Curl');
            }
            curl_close($ibCurl);
        } else {
            $this->notifyError('Curl not enabled', 'Curl');
        }
    }

    private function checkHash($apiKey, $secretKey, $hash)
    {
        if (sha1($apiKey . $secretKey . gmdate('Ymd')) == $hash) {
            return true;
        }

        return false;
    }

    private function notifyError($message, $key = 'general')
    {
        die($this->notify('error', $message, $key));
    }

    private function notifySuccess($message, $key = 'general')
    {
        die($this->notify('success', $message, $key));
    }

    private function notify($type, $message, $key)
    {
        return self::jsonEncode(array($type => array($key => $message)));
    }

    private function setServerMaxExecutionTime()
    {
        ini_set('max_execution_time', $this->executionTime);
        
        return ini_get('max_execution_time');
    }

}
