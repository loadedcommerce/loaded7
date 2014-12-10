<?php

include_once(rtrim(dirname(__FILE__), '/') . '/ItembaseCommonInterface.php');
include_once(rtrim(dirname(__FILE__), '/') . '/plugindata.php');

/**
 * itembase plugin core class
 *
 * @author Paweł Kubasiak <pk@itembase.biz>
 * @copyright (c) 2014 Itembase GmbH
 */
abstract class ItembaseCommonAbs implements ItembaseCommonInterface
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var integer
     */
    protected $shopId;

    /**
     * @var boolean
     */
    protected $debug;

    /**
     * Create a new instance of ItembaseCommon object.
     *
     * @param string $apiKey
     * @param string $secretKey
     * @param boolean $debug
     * @param mixed $shopId
     * @return void
     */
    public function __construct($apiKey, $secretKey, $debug, $shopId = null)
    {
        $this->debug = (bool) $debug;
        set_error_handler(array(&$this, 'errorHandler'));
        $this->apiKey      = $apiKey;
        $this->secretKey   = $secretKey;
        $this->shopId      = $shopId;
        $this->accessToken = $this->accessTokenProccess();
    }

    /**
     * Authenticate client and get an access token
     *
     * @param string $clientId
     * @param string $clientSecret
     * @return string
     */
    private function authenticateClient($clientId, $clientSecret)
    {
        $data = array(
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'response_type' => 'token',
            'grant_type'    => 'client_credentials'
        );
        $response = self::sendPostData(ITEMBASE_SERVER_OAUTH, $data);
        if (isset($response['data_error'])) {
            $this->errorHandler(0, $response['data_error'], __FILE__, __LINE__ - 1);
        }

        return $response;
    }

    /**
     * Register new shop on itembase
     *
     * @param string $shopCode
     * @param array $data
     * @param bool $regField
     * @return string
     */
    public static function shopRegistration($shopCode, array $data, $regField = false)
    {
        $data['shop_software'] = $shopCode;
        $data['return'] = 'json';
        foreach ($data['shops'] as $key => $shop) {
            if ($regField === false) {
                $shop['register'] = 1;
            }
            $data['shops'][$key] = $shop;
        }
        $response = self::sendPostData(ITEMBASE_SERVER_HOST.'/api/register_retailer', $data);
        if (is_array($response) && isset($response['data_error'])) {

            return $response['data_error'];
        } else {

            return self::jsonDecode($response, true);
        }
    }

    /**
     * Send processed data via curl to itembase Api
     *
     * @param string $url
     * @param array $data
     * @return string
     */
    private static function sendPostData($url, $data)
    {
        $prepareData = http_build_query($data);
        if (extension_loaded('curl')) {
            $header[] = 'Authorization: OAuth Content-Type: application/x-www-form-urlencoded';
            $ibCurl   = curl_init();
            curl_setopt($ibCurl, CURLOPT_HEADER, false);
            curl_setopt($ibCurl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ibCurl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ibCurl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ibCurl, CURLOPT_URL, $url);
            curl_setopt($ibCurl, CURLOPT_POST, true);
            curl_setopt($ibCurl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ibCurl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ibCurl, CURLOPT_POSTFIELDS, $prepareData);
            $response = curl_exec($ibCurl);
            if ($response === false) {
                $response['data_error'] = 'curl error: '.curl_error($ibCurl);
            }
            curl_close($ibCurl);
        } else {
            $opts = array('http' => array('ignore_errors' => true, 'timeout' => 5));
            $context = stream_context_create($opts);
            $response = file_get_contents($url.'?'.$prepareData, false, $context);
            if ($response === false) {
                $response['data_error'] = 'file_get_contents error';
            }
        }

        return $response;
    }

    /**
     * Shows all errors when debug mode is enabled in itembase plugin configuration
     *
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @return bool
     */
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if ($this->debug) {
            echo "<!--ITEMBASE
".print_r(array($errno, $errstr, $errfile, $errline), true)." ITEMBASE-->";
        }

        return true;
    }

    /**
     * Access token validation
     *
     * @return string
     */
    private function accessTokenProccess()
    {
        $accessToken = self::jsonDecode($this->getAccessTokenFromDB($this->shopId), true);
        if (isset($accessToken['expires_in']) && $accessToken['expires_in'] - ITEMBASE_TOKEN_LIFETIME > time()) {
            $responseArray['access_token'] = $accessToken['access_token'];
        } else {
            $responseArray = self::jsonDecode($this->authenticateClient($this->apiKey, $this->secretKey), true);
            if (!isset($responseArray['access_token'])) {
                $this->errorHandler(0, 'no access_token for "' . $this->output($this->apiKey) . '" "' . substr($this->output($this->secretKey), 0, 4) . '..." ' . ITEMBASE_SERVER_OAUTH . ' ' . print_r($responseArray, true), __FILE__, __LINE__ - 2);
                $responseArray['access_token'] = 'null';
            } else {
                $this->setAccessTokenToDB($responseArray['access_token'], $responseArray['expires_in'], $this->shopId);
            }
        }

        return $responseArray['access_token'];
    }

    /**
     * Convert all values from array to UTF8
     *
     * @param array $data
     */
    public static function utf8EncodeRecursive(&$data)
    {
        if (is_array($data) || is_object($data)) {
            settype($data, 'array');
            foreach ($data as $key => &$_val) {
                self::utf8EncodeRecursive($_val);
            }
        } else {
            $data = strip_tags(html_entity_decode(html_entity_decode($data)));
            if (extension_loaded('mbstring')) {
                global $encodings;
                if (!$encodings) {
                    $encodings = array();
                    foreach (explode(',', 'UTF-8,ISO-8859-1,ISO-8859-2,ISO-8859-3,ISO-8859-4,ISO-8859-5,ISO-8859-6,ISO-8859-7,ISO-8859-8,ISO-8859-9,ISO-8859-10,ISO-8859-13,ISO-8859-14,ISO-8859-15,ISO-8859-16,Windows-1252,Windows-1250,Windows-1251,Windows-1254') as $encoding) {
                        if (in_array($encoding, mb_list_encodings())) {
                            $encodings[] = $encoding;
                        }
                    }
                    mb_detect_order(array_merge($encodings, mb_list_encodings()));
                }
                if (($encoding = mb_detect_encoding($data, null, true)) != 'UTF-8') {
                    mb_convert_variables('UTF-8', $encoding, $data);
                }
            } elseif (!preg_match(
                '%^(?:
                [\x09\x0A\x0D\x20-\x7E] # ASCII
                | [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
                | \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
                | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
                | \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
                | \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
                | [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
                | \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
                )*$%xs',
                $data
            )) {
                if (extension_loaded('iconv')) {
                    $data = iconv(iconv_get_encoding('internal_encoding'), 'UTF-8//IGNORE', $data);
                } else {
                    $data = utf8_encode($data);
                }
            }
        }
    }

    /**
     * Prepare data to send to itembase server
     *
     * @param array $dataForItembase
     * @return array
     */
    public function prepareData(array $dataForItembase)
    {
        $payload = false;
        if (isset($dataForItembase['payload'])) {
            $payload = $dataForItembase['payload'];
            unset($dataForItembase['payload']);
        };
        
        $dataForItembase = $this->removeEmptyValues($dataForItembase);
        self::utf8EncodeRecursive($dataForItembase);
        if($payload) {
            $dataForItembase['payload'] = $payload;
        }
        $dataForItembase = array($dataForItembase);
        $returnArray = array(
            'ibData' => addslashes(self::jsonEncode($dataForItembase)),
            'ibPluginVersion' => ITEMBASE_PLUGIN_VERSION,
            'ibApiUrl' => ITEMBASE_SERVER_API . '/' . ITEMBASE_API_VERSION . '/transactions?df=' .  ITEMBASE_TRANSACTION_DF_VERSION . '&token=' . $this->getAccessToken()
        );

        return $returnArray;
    }

    /**
     * Decode JSON string
     * Uses json_decode to decode json string or Services_JSON class if json_decode function not exists
     *
     * @param string $data
     * @param bool $assoc
     * @return array
     */
    public static function jsonDecode($data, $assoc = false)
    {
        if (function_exists('json_decode')) {
            $result = json_decode($data, $assoc);
        } else {
            include_once(rtrim(dirname(__FILE__), '/').'/json.php');
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
            $result = $json->decode($data);
        }

        return $result;
    }

    /**
     * Encode JSON
     * Uses json_encode to encode data or Services_JSON class if json_encode function not exists
     *
     * @param array $data
     * @return string
     */
    public static function jsonEncode($data)
    {
        if (function_exists('json_encode')) {
            $result = json_encode($data);
        } else {
            include_once(rtrim(dirname(__FILE__), '/').'/json.php');
            $json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
            $result = $json->encode($data);
        }

        return $result;
    }

    /**
     * outputs text
     * Override this method if you want change output text in itembase error handler.
     *
     * @param string $text
     * @return string
     */
    protected function output($text)
    {
        return $text;
    }

    /**
     * Get access token
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param float|integer $price
     * @param int $precision
     * @return float
     */
    public static function numberFormat($price, $precision = 2)
    {
        return number_format((float) $price, $precision, '.', '');
    }

    /**
     * remove empty keys from array
     *
     * @param $array
     * @return array
     */
    protected function removeEmptyValues($array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $this->removeEmptyValues($value);
            }
        }

        return array_filter($array, create_function('$a', 'return (($a ===\'\' || is_null($a)) ? false : true);'));
    }
    
    /**
     * count tax rate based on tax value and price
     * 
     * @param float $base_price
     * @param float $tax
     * @return float
     */
    public static function calcTax($base_price, $tax)
    {
        $tax_rate = $base_price > 0 ? ($tax/$base_price) * 100 : 0;
        return self::numberFormat($tax_rate, 1);
    }
}
