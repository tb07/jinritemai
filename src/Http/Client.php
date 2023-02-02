<?php

/*
 * Author: cc
 *  Created by PhpStorm.
 *  Copyright (c)  cc Inc. All rights reserved.
 */

namespace Imactool\Jinritemai\Http;

trait Client
{
    public static    $client;
    protected static $appConfig             = [];
    protected        $shop_access_token_key = 'imactool.shop.access_token.'; //店铺 token 缓存 key

    public function httpClient()
    {
        if (!self::$client) {
            self::$client = new Http();
        }

        return self::$client;
    }

    public static function setAppConfig($key, $appConfig)
    {
        self::$appConfig[$key] = $appConfig;
    }

    public static function getAppConfig($key = null)
    {
        if (is_null($key)) {
            return self::$appConfig['config'];
        }

        return self::$appConfig['config'][$key];
    }

    public static function setShopConfig($shopConfig)
    {
        self::$appConfig = array_merge(self::getAppConfig(), $shopConfig);
    }

    public static function getShopConfig($key = null)
    {
        if (is_null($key)) {
            return self::$appConfig['shop'];
        }

        return self::$appConfig['shop'][$key];
    }

    public static function getAllConfig()
    {
        return self::$appConfig;
    }

    public function authorizerTokenKey()
    {
        return $this->shop_access_token_key . self::getShopConfig('shopId');
    }

    /**
     * 发送 get 请求
     *
     * @param string $endpoint
     * @param array $query
     * @param array $headers
     *
     * @return mixed
     */
    public function get($endpoint, $query = [], $headers = [], $returnRaw = false)
    {
        $query = $this->generateParams($endpoint, $query);

        return $this->httpClient()->request('get', $endpoint, [
            'headers' => $headers,
            'query'   => $query,
        ], $returnRaw
        );
    }

    /**
     * 发送 post 请求
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     *
     * @return mixed
     */
    public function post($endpoint, $params = [], $headers = [], $returnRaw = false)
    {
        $params = $this->generateParams($endpoint, $params);

        return $this->httpClient()->request('post', $endpoint, [
            'header'      => $headers,
            'form_params' => $params,
        ], $returnRaw
        );
    }

    /**
     * 用 json 的方式发送 post 请求
     *
     * @param $endpoint
     * @param array $params
     * @param array $headers
     *
     * @return mixed
     */
    public function postJosn($endpoint, $params = [], $headers = [], $returnRaw = false)
    {
        $params = $this->generateParams($endpoint, $params);

        return $this->httpClient()->request('post', $endpoint, [
            'headers' => $headers,
            'json'    => $params,
        ], $returnRaw
        );
    }

    /**
     * 组合公共参数、业务参数.
     *
     * @see https://op.jinritemai.com/docs/guide-docs/10/23
     *
     * @param string $url 支持 /shop/brandList 或者 shop/brandList 格式
     * @param array $params 业务参数
     */
    protected function generateParams(string $url, array $params, $isAccessToken = true)
    {
        $method = ltrim(str_replace('/', '.', $url), '.');
        //公共参数
        $publicParams = [
            'method'      => $method,
            'app_key'     => self::getAppConfig('app_key'),
            'timestamp'   => date('Y-m-d H:i:s'),
            'v'           => '2',
            'sign_method' => 'md5',
        ];
        if ($isAccessToken) {
            $publicParams['access_token'] = self::getShopConfig('accessToken');
        }
        //业务参数
        ksort($params);
        $params_json = \json_encode((object)$params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $string = 'app_key' . $publicParams['app_key'] . 'method' . $method . 'param_json' . $params_json . 'timestamp' . $publicParams['timestamp'] . 'v' . $publicParams['v'];
        $md5Str = self::getAppConfig('app_secret') . $string . self::getAppConfig('app_secret');
        $sign   = md5($md5Str);

        return array_merge($publicParams, [
            'param_json' => $params_json,
            'sign'       => $sign,
        ]);
    }
}
