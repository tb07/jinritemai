<?php


namespace Imactool\Jinritemai\Error;

class ErrorCode
{
    const DOUDIANMERRORCODEMAP = [
        10000 => ['desc' => 'success'],
        10001 => ['desc' => '请求部分失败'],
        20000 => ['desc' => '服务不可用', 'sub' => ['dop.service-error' => '内部错误，请反馈客服或答疑群', 'isp.service-error:{底层错误码}' => '底层错误文案']],
        20001 => ['desc' => '内部服务超时'],
        30001 => [
            'desc' => '操作权限不足',
            'sub'  => [
                'isv.app-permissions-insufficient' => '应用无权限调用该接口，请先申请接口权限包',
                'dop.authorization-no-existed'     => '店铺授权已失效，请重新授权',
                'dop.app-forbidden'                => '应用已被系统禁用',
                'dop.authorization-closed'         => '店铺授权已被关闭，请联系商家打开授权开关',
            ],
        ],
        30002 => ['desc' => '请求来源IP不可信，请检查IP白名单'],
        40001 => [
            'desc' => '缺少必选参数（平台校验）',
            'sub'  => [
                'isv.signature-missing'    => '获取签名失败，请根据文档确保参数拼装正确',
                'isv.app-id-missing'       => '认证失败，app_key不存在',
                'isv.access-token-missing' => '认证失败，access_token不能为空',
            ],
        ],
        40002 => ['desc' => '缺少必选参数（业务校验）'],
        40003 => [
            'desc' => '非法的参数（平台校验）',
            'sub'  => [
                'isv.parameter-format-invalid'   => '业务参数json解析失败，所有参数需为string类型',
                'isv.signature-invalid'          => '签名校验失败',
                'isv.access-token-expired'       => 'access_token已过期',
                'isv.access-token-no-existed'    => 'access_token不存在，请使用最新的access_token访问',
                'isv.access-token-invalid'       => 'app_key和access_token不匹配，请仔细检查',
                'isv.app-id-invalid'             => '认证失败，app_key格式不正确，应为19位纯数字',
                'isv.app-id-expired'             => 'app_key已过期失效，请前往抖店开放平台进行升级。升级指南：https://op.jinritemai.com/docs/guide-docs/6/31',
                'isv.authorization-type-invalid' => 'auth2协议验签失败，当前用户无法访问该接口。授权主体不匹配，请仔细检查',
                'isv.idempotent-id-existed'      => '幂等ID重复', 'isv.timestamp-invalid' => '时间戳不合法',
                'isv.timestamp-format-error'     => '时间戳格式错误',
            ],
        ],
        40004 => ['desc' => '非法的参数（业务校验）'],
        50001 => [
            'desc' => '平台处理失败',
            'sub'  => [
                'dop.token-generate-failed: code-expired'      => '生成token失败, 店铺授权已失效，请重新引导商家完成店铺授权',
                'dop.token-generate-failed:app-secret-invalid' => '生成token失败，app_key不存在或者与app_secret不匹配',
                'dop.token-generate-failed:grant-type-invalid' => '生成token失败，grant_type参数取值不正确，请参照文档根据应用类型填写不同的值',
                'dop.token-generate-failed:app-forbidden'      => '生成token失败，开发者应用已经被禁用',
                'dop.token-generate-failed:code-invalid'       => '生成token失败，code参数不正确，请引导商家重新授权获取code',
                'dop.token-generate-failed:no-authorization'   => '生成token失败，店铺授权已失效，请重新引导商家完成店铺授权',
                'dop.token-generate-failed:code-expired'       => '生成token失败，code已经失效（code的有效期为10分钟）',
                'dop.token-generate-failed:token-expired'      => '生成token失败，token已过期',
                'dop.token-generate-failed:token-invalid'      => '生成token失败，token不存在',
            ],
        ],
        50002 => ['desc' => '业务处理失败'],
        60000 => ['desc' => '触发限流，请稍后重试'],
        70000 => ['desc' => '接口服务已下线', 'sub' => ['isp.api-service-off' => 'API不存在或API已下线']],
        80000 => ['desc' => '安全错误'],
        90000 => ['desc' => '其他异常',
                  'sub'  => [
                      'isp.unknown-error' => 'code的授权不存在',
                      'dop.unknown-error' => '未知错误',
                  ]],
    ];


    /**
     * 获取错误信息
     *
     * @param int $code
     * @param string $subCode
     * @return string[]
     */
    static public function mapping(int $code, string $subCode = '')
    : array
    {
        return isset($subCode) ? self::DOUDIANMERRORCODEMAP[$code]['sub'][$subCode] : self::DOUDIANMERRORCODEMAP[$code];
    }
}
