<?php

namespace Perspective\LiqPayPayment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Psr\Log\LoggerInterface;

class Data extends AbstractHelper
{
    const XML_PATH_METHOD_ACTIVE = 'payment/liqpay/active';

    const XML_PATH_PUBLIC_KEY = 'payment/liqpay/public_key';

    const XML_PATH_PRIVATE_KEY = 'payment/liqpay/private_key';

    const XML_PATH_RESULT_URL = 'payment/liqpay/result_url';

    const XML_PATH_SHIPPING_METHODS = 'payment/liqpay/shipping_methods';

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_METHOD_ACTIVE);
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_PUBLIC_KEY);
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_PRIVATE_KEY);
    }

    /**
     * @return string
     */
    public function getResultUrl()
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_RESULT_URL);
    }

    /**
     * @return false|string[]
     */
    public function getShippingMethods()
    {
        return explode(',', $this->scopeConfig->getValue(self::XML_PATH_SHIPPING_METHODS));
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->_logger;
    }

}
