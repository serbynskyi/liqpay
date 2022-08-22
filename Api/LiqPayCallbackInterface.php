<?php

namespace Perspective\LiqPayPayment\Api;

interface LiqPayCallbackInterface
{
    /**
     * @return mixed
     */
    public function execute();
}
