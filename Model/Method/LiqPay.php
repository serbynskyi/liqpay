<?php

namespace Perspective\LiqPayPayment\Model\Method;

use Magento\Framework\Event\ManagerInterface;
use Magento\Payment\Gateway\Command\CommandManagerInterface;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\Config\ValueHandlerPoolInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectFactory;
use Magento\Payment\Gateway\Validator\ValidatorPoolInterface;
use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Api\Data\CartInterface;
use Perspective\LiqPayPayment\Helper\Data;
use Psr\Log\LoggerInterface;

class LiqPay extends Adapter
{
    /**
     * @var Data
     */
    private $config;

    /**
     * @param ManagerInterface $eventManager
     * @param ValueHandlerPoolInterface $valueHandlerPool
     * @param PaymentDataObjectFactory $paymentDataObjectFactory
     * @param $code
     * @param $formBlockType
     * @param $infoBlockType
     * @param Data $config
     * @param CommandPoolInterface|null $commandPool
     * @param ValidatorPoolInterface|null $validatorPool
     * @param CommandManagerInterface|null $commandExecutor
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        ManagerInterface $eventManager,
        ValueHandlerPoolInterface $valueHandlerPool,
        PaymentDataObjectFactory $paymentDataObjectFactory,
        $code,
        $formBlockType,
        $infoBlockType,
        Data $config,
        CommandPoolInterface $commandPool = null,
        ValidatorPoolInterface $validatorPool = null,
        CommandManagerInterface $commandExecutor = null,
        LoggerInterface $logger = null
    ) {
        parent::__construct(
            $eventManager,
            $valueHandlerPool,
            $paymentDataObjectFactory,
            $code,
            $formBlockType,
            $infoBlockType,
            $commandPool,
            $validatorPool,
            $commandExecutor,
            $logger
        );
        $this->config = $config;
    }

    /**
     * @param CartInterface|null $quote
     * @return array|bool|mixed|null
     */
    public function isAvailable(CartInterface $quote = null)
    {
        $quoteShippingMethod = $quote->getShippingAddress()->getShippingMethod();
        $shippingMethods = $this->config->getShippingMethods();
        if ($this->config->isEnabled() && $shippingMethods && in_array($quoteShippingMethod, $shippingMethods)) {
            return parent::isAvailable($quote);
        }
        return false;
    }
}
