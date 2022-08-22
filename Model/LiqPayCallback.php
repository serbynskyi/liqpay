<?php

namespace Perspective\LiqPayPayment\Model;

use Magento\Framework\DB\Transaction;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Sales\Api\InvoiceManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Perspective\LiqPayPayment\Api\LiqPayCallbackInterface;
use Perspective\LiqPayPayment\Helper\Data;

class LiqPayCallback implements LiqPayCallbackInterface
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var InvoiceManagementInterface
     */
    private $invoiceService;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var Data
     */
    private $config;

    /**
     * @param Transaction $transaction
     * @param Request $request
     * @param InvoiceManagementInterface $invoiceService
     * @param OrderRepositoryInterface $orderRepository
     * @param Data $config
     */
    public function __construct(
        Transaction $transaction,
        Request $request,
        InvoiceManagementInterface $invoiceService,
        OrderRepositoryInterface $orderRepository,
        Data $config
    ) {
        $this->transaction = $transaction;
        $this->request = $request;
        $this->invoiceService = $invoiceService;
        $this->orderRepository = $orderRepository;
        $this->config = $config;
    }

    /**
     * @return mixed|void
     */
    public function execute()
    {
        try {
            $requestParams = $this->request->getParams();
            $private_key = $this->config->getPrivateKey();
            $signature = base64_encode(sha1($private_key . $requestParams['data'] . $private_key, 1));
            if ($signature === $requestParams['signature']) {
                $params = json_decode(base64_decode($requestParams['data']), true);
                $orderId = $params['order_id'] ?? null;
                $status = $params['status'] ?? null;
                $transactionId = $params['transaction_id'] ?? null;

                /** @var Order $order */
                $order = $this->orderRepository->get($orderId);
                $historyMessage = [];
                $state = null;
                if ($order->canInvoice() && ($status == 'success')) {
                    $invoice = $this->invoiceService->prepareInvoice($order);
                    $invoice->register()->pay();
                    $transactionSave = $this->transaction->addObject(
                        $invoice
                    )->addObject(
                        $invoice->getOrder()
                    );
                    $transactionSave->save();
                    $historyMessage[] = __('Invoice #%1 created.', $invoice->getIncrementId());
                    $state = Order::STATE_PROCESSING;
                } else {
                    $historyMessage[] = __('Error during creation of invoice.');
                }
                if ($transactionId) {
                    $historyMessage[] = __('LiqPay transaction id %1.', $transactionId);
                }
                if (count($historyMessage)) {
                    $order->addCommentToStatusHistory(implode(' ', $historyMessage));
                }
                if ($state) {
                    $order->setState($state);
                    $order->setStatus($state);
                }
                $this->orderRepository->save($order);
            }
        } catch (\Exception $exception) {
            $this->config->getLogger()->critical($exception);
        }
    }
}
