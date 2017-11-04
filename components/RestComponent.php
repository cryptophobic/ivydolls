<?php

namespace app\components;

use Yii;
use yii\base\Component;


/**
 * REST Wrapper
 *
 * Sending REST requests and handling responses
 */
class RestComponent extends Component
{
    private $_server;

    private $_lasHttpCode = 0;

    private $_lastResponse = null;

    private $_lastError = "";

    public function SetServer($server)
    {
        $this->_server = $server;
    }

    public function getLastResponse()
    {
        return $this->_lastResponse;
    }


    public function getLastError()
    {
        return $this->_lastError;
    }

    public function getLastHttpCode()
    {
        return $this->_lasHttpCode;
    }

    /**
     * Call REST API
     * Method: POST, PUT, GET etc
     * Data: array("param" => "value") ==> index.php?param=value
     * @param $method
     * @param bool $data
     * @return bool
     */
    public function CallApi($method, $data = false)
    {
        if (!$this->_server) {
            $this->_server = Yii::$app->params['RestServer'];
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,10);
        $url = $this->_server;

        switch (strtoupper($method)) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');

                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "PATCH":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');

                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case "GET":
                if ($data)
                    $url = sprintf("%s?%s", $this->_server, http_build_query($data));
                break;
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);

        /*$model = new RestLogger("parol");
        $model->add($method.'('.self::$_server.') => '.print_r(json_decode($data, true),true) . "RESPONSE=>".print_r(json_decode($res,true),true)."\n");*/

        if (!curl_errno($curl)) {
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $this->_lasHttpCode = $http_code;
            $this->_lastResponse = $res;
            $this->_lastError = "";
            return true;

        } else {
            $this->_lastError = curl_error($curl);
            $this->_lasHttpCode = -1;
            $this->_lastResponse = null;
            Yii::error('Curl error: '.$this->_lastError);
            return false;
        }
    }


}
