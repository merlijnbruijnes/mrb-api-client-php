<?php

class MrbApiClient
{
    const OAUTH2_TOKEN_URL = API_BASE_PATH . '/oauth/v2/token';
    const API_URL = API_BASE_PATH . '/api';

    private $config;
    protected $access_token;
    protected $refresh_token;

    public function __construct($config = array())
    {
        if (isset($_COOKIE['MRBAPITOKEN'])) {
            $cookie = json_decode($_COOKIE['MRBAPITOKEN'], true);

            if ($cookie['token_expires_at'] >= time()) {
                $result['access_token'] = $cookie['access_token'];
                $result['refresh_token'] = $cookie['refresh_token'];
            } else {
                $result = $this->_refreshToken($config);
            }
        } else {
            $result = $this->_newToken($config);
        }

        if (isset($result['error'])) {
            throw new \Exception(json_encode($result));
        }

        $this->access_token = $result['access_token'];
        $this->refresh_token = $result['refresh_token'];
    }

    public function delete($endpoint, $params = [])
    {
        return $this->_call(
            $this->_getUrl($endpoint, $params),
            'DELETE',
            $params
        );
    }

    public function get($endpoint, $params = [])
    {
        return $this->_call(
            $this->_getUrl($endpoint, $params),
            'GET',
            $params
        );
    }

    private function _getUrl($endpoint, $params)
    {
        $url = self::API_URL . $endpoint;

        if (isset($params['id'])) {
            $url = $url . '/' . $params['id'];
        }

        return $url;
    }

    private function _newToken($config)
    {
        $this->config = array_merge(
            [
                'grant_type' => 'password'
            ],
            $config
        );

        $result = json_decode(
            $this->_call(self::OAUTH2_TOKEN_URL, "POST", [], $this->config),
            true
        );

        if (isset($result['error'])) {
            return $result;
        }

        $cookieExpiration = (time() + 3600 * 24 * 7);

        $cookieValue = [
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
            'token_expires_at' => (time() + $result['expires_in']),
            'cookie_expires_at' => $cookieExpiration
        ];

        setcookie('MRBAPITOKEN', json_encode($cookieValue), $cookieExpiration, '/');

        return $result;
    }

    public function post($endpoint, $params = [])
    {
        return $this->_call(
            $this->_getUrl($endpoint, $params),
            'POST',
            [],
            $params
        );
    }

    public function put($endpoint, $params = [])
    {
        return $this->_call(
            $this->_getUrl($endpoint, $params),
            'PUT',
            [],
            $params
        );
    }

    private function _refreshToken($config)
    {
        $cookie = json_decode($_COOKIE['MRBAPITOKEN'], true);
        $this->config = array_merge(
            [
                'refresh_token' => $cookie['refresh_token'],
                'grant_type' => 'refresh_token'
            ],
            $config
        );

        $result = json_decode(
            $this->_call(self::OAUTH2_TOKEN_URL, "POST", [], $this->config),
            true
        );

        if (isset($result['error'])) {
            return $result;
        }

        $cookieValue = [
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
            'token_expires_at' => (time() + $result['expires_in']),
            'cookie_expires_at' => $cookie['cookie_expires_at']
        ];

        setcookie('MRBAPITOKEN', json_encode($cookieValue), $cookie['cookie_expires_at'], '/');

        return $result;
    }

    public function _call($url, $method, $getParams = array(), $postParams = array())
    {
        ob_start();
        $curl_request = curl_init();

        curl_setopt($curl_request, CURLOPT_HEADER, 0); // don't include the header info in the output
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1); // don't display the output on the screen
        $url = $url . "?" . http_build_query($getParams);

        if ($this->access_token) {
            $header[] = 'Authorization: Bearer ' . $this->access_token;
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, $header);
        }

        switch (strtoupper($method)) {
            case "POST":
                curl_setopt($curl_request, CURLOPT_URL, $url);
                curl_setopt($curl_request, CURLOPT_POST, 'POST');
                curl_setopt($curl_request, CURLOPT_POSTFIELDS, http_build_query($postParams));
                break;
            case "GET":
                curl_setopt($curl_request, CURLOPT_URL, $url);
                break;
            case "PUT":
                curl_setopt($curl_request, CURLOPT_URL, $url);
                curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl_request, CURLOPT_POSTFIELDS, http_build_query($postParams));
                break;
            case "DELETE":
                curl_setopt($curl_request, CURLOPT_URL, $url);
                curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl_request, CURLOPT_POSTFIELDS, http_build_query($postParams));
                break;
            default:
                curl_setopt($curl_request, CURLOPT_URL, $url);
                break;
        }

        $result = curl_exec($curl_request);

        if ($result === false) {
            $result = curl_error($curl_request);
        }

        curl_close($curl_request);
        ob_end_flush();

        return $result;
    }
}
