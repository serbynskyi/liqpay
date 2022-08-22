<?php

namespace Perspective\LiqPayPayment\Block\Form;

use Magento\Payment\Block\Form;

/**
 * Block for LiqPay payment method form
 */
class LiqPay extends Form
{
    /**
     * LiqPay template
     *
     * @var string
     */
    protected $_template = 'Perspective_LiqPayPayment::form/liqpay.phtml';
}
