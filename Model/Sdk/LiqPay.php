<?php

namespace Perspective\LiqPayPayment\Model\Sdk;

use Perspective\LiqPayPayment\Helper\Data;
use Perspective\LiqPayPayment\Model\Sdk\Core\LiqPay as CoreLiqPay;

class LiqPay extends CoreLiqPay
{
    const SERVER_URL = 'http://local-m2.sytes.net/rest/V1/liqpay/callback/';

    /**
     * @var Data
     */
    private $config;

    public function __construct($public_key, $private_key, Data $config, $api_url = null)
    {
        $public_key = $config->getPublicKey();
        $private_key = $config->getPrivateKey();
        parent::__construct($public_key, $private_key, $api_url);
        $this->config = $config;
    }

    /**
     * @param float|null $amount
     * @param string $orderId
     * @param string $orderIncrementId
     * @return array
     */
    public function getFormRaw($amount, $orderId, $orderIncrementId)
    {
        $params = [
            'action' => 'pay',
            'version' => '3',
            'amount' => $amount,
            'currency' => 'UAH',
            'description' => 'Оплата замовлення: ' . $orderIncrementId,
            'order_id' => $orderId,
            'language' => 'uk',
            'result_url' => $this->config->getResultUrl(),
            'server_url' => self::SERVER_URL
        ];
        return $this->cnb_form_raw($params);
    }
}
