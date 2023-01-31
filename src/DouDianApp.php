<?php

/*
 * Author: cc
 *  Created by PhpStorm.
 *  Copyright (c)  cc Inc. All rights reserved.
 */

namespace Imactool\Jinritemai;

use Imactool\Jinritemai\AfterSale\AfterSale;
use Imactool\Jinritemai\AfterSale\AfterSaleProvider;
use Imactool\Jinritemai\Auth\Auth;
use Imactool\Jinritemai\Bill\Bill;
use Imactool\Jinritemai\Bill\BillProvider;
use Imactool\Jinritemai\Btas\Btas;
use Imactool\Jinritemai\Btas\BtasProvider;
use Imactool\Jinritemai\Comment\Commnet;
use Imactool\Jinritemai\Comment\CommnetProvider;
use Imactool\Jinritemai\Core\ContainerBase;
use Imactool\Jinritemai\Http\Client;
use Imactool\Jinritemai\Auth\AuthProvider;
use Imactool\Jinritemai\Insurance\Insurance;
use Imactool\Jinritemai\Insurance\InsuranceProvider;
use Imactool\Jinritemai\Logistics\Logistics;
use Imactool\Jinritemai\Logistics\LogisticsProvider;
use Imactool\Jinritemai\Order\Order;
use Imactool\Jinritemai\Order\OrderProvider;
use Imactool\Jinritemai\Product\Product;
use Imactool\Jinritemai\Product\ProductProvider;
use Imactool\Jinritemai\Shop\Shop;
use Imactool\Jinritemai\Shop\ShopProvider;
use Imactool\Jinritemai\Stock\Stock;
use Imactool\Jinritemai\Stock\StockProvider;

/**
 * @property-read Shop $Shop
 * @property-read Auth $Auth
 * @property-read Product $Product
 * @property-read AfterSale $AfterSale
 * @property-read Commnet $Commnet
 * @property-read Insurance $Insurance
 * @property-read Logistics $Logistics
 * @property-read Order $Order
 * @property-read Stock $Stock
 * @property-read Bill $Bill
 * @property-read Btas $Btas
 */
class DouDianApp extends ContainerBase
{
    use Client;
    private static $config;
    /**
     * 配置服务提供者.
     *
     * @var string[]
     */
    protected $provider = [
        ShopProvider::class,
        AuthProvider::class,
        ProductProvider::class,
        AfterSaleProvider::class,
        CommnetProvider::class,
        InsuranceProvider::class,
        LogisticsProvider::class,
        OrderProvider::class,
        StockProvider::class,
        BillProvider::class,
        BtasProvider::class,
    ];

    public function __construct(array $config)
    {
        self::$config = $config;
        Client::setAppConfig('config', $config);
        parent::__construct();
    }

    public function shopApp(int $shopId, string $refreshToken)
    {
        parent::__construct();
        $this->shopId = $shopId;
        $this->refreshToken = $refreshToken;

        return $this;
    }
}
