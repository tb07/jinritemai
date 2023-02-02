<?php

/*
 * Author: cc
 *  Created by PhpStorm.
 *  Copyright (c)  cc Inc. All rights reserved.
 */

namespace Imactool\Jinritemai\Auth;

use Imactool\Jinritemai\Core\BaseService;
use Imactool\Jinritemai\Core\CacheAdapter;

class Auth extends BaseService
{
    /**
     * 店铺授权 URL.
     *
     * @return string
     */
    public function generateAuthUrl(string $state)
    {
        $url   = 'https://fuwu.jinritemai.com/authorize';
        $query = [
            'service_id' => $this->appRunConfig['service_id'],
            'state'      => $state,
        ];

        return $url . '?' . http_build_query($query);
    }

    /**
     * 请求获取 access_token.
     *
     * @param $code
     *
     * @return mixed
     */
    public function requestAccessToken($code)
    {
        $params  = [
            'code'       => $code,
            'grant_type' => 'authorization_code',
        ];
        $query   = $this->generateParams('token/create', $params, false);
        $options = [
            'headers' => [],
            'query'   => $query,
        ];
        $result  = $this->httpClient()->request('get', 'token/create', $options);
        if (!empty($response['code']) && $response['code'] == 10000 && !empty($response['code']['data']['access_token'])) {

        }
        return $result;
    }

    /**
     * 刷新 access_token
     * 如果refresh_token也过期了，则只能让商家点击“使用”按钮，会打开应用使用地址，
     * 地址参数里会带上新的授权code。然后用新的code，重新调接口，获取新的access_token。
     *
     * @see https://op.jinritemai.com/help/faq/43/206
     *
     * @param $refresh_token
     *
     * @return mixed
     */
    public function refreshAccessToken($refresh_token)
    {
        $params  = [
            'refresh_token' => $refresh_token,
            'grant_type'    => 'refresh_token',
        ];
        $query   = $this->generateParams('token/refresh', $params, false);
        $options = [
            'headers' => [],
            'query'   => $query,
        ];
        $result  = $this->httpClient()->request('get', 'token/refresh', $options);

        return $result;
    }

    /**
     * 自用型 - 获取access_token.
     *
     * @see  https://op.jinritemai.com/docs/guide-docs/9/21
     * 对于自用型的应用来说，初始化就需要直接 获取 token
     */
    public function getShopAccessToken($shop_id)
    {
        $params = [
            'app_id'     => $this->appRunConfig['app_key'],
            'app_secret' => $this->appRunConfig['app_secret'],
            'code'       => '',
            'grant_type' => 'authorization_self',
            'shop_id'    => $shop_id,
        ];

        $options = [
            'headers' => [],
            'query'   => $params,
        ];
        $result  = $this->httpClient()->request('get', 'oauth2/access_token', $options);

        if (0 !== $result['err_no']) {
            return $result;
        }

        return $result;
    }
}
