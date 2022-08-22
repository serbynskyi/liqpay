<?php

namespace Perspective\LiqPayPayment\Controller\LiqPay;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Sales\Model\Order;
use Perspective\LiqPayPayment\Model\Sdk\LiqPay;

class Index extends Action
{
    /** @var Session */
    private $checkoutSession;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var LiqPay
     */
    private $sdkLiqPay;

    /**
     * @param Session $checkoutSession
     * @param Context $context
     * @param LiqPay $sdkLiqPay
     */
    public function __construct(
        Session $checkoutSession,
        Context $context,
        LiqPay $sdkLiqPay
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->sdkLiqPay = $sdkLiqPay;
    }

    /**
     * Initialize redirect to bank
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var Order $order */
        $order = $this->checkoutSession->getLastRealOrder();

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $formRaw = $this->sdkLiqPay->getFormRaw($order->getTotalDue(), $order->getId(), $order->getIncrementId());
        $url = $formRaw['url'];
        $params = [
            'data' => $formRaw['data'],
            'signature' => $formRaw['signature']
        ];
        $url = $this->_url->addQueryParams($params)->getRedirectUrl($url);
        $resultRedirect->setPath($url);

        return $resultRedirect;
    }
}
