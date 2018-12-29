<?php

namespace App\Classes\Notification\Sender;

use App\Classes\AppResponse;
use Illuminate\Support\Collection;
use App\Classes\Notification\Receipt\ReceiptContract;
use App\Classes\Notification\MessageBuilder\MessageBuilderContract;

interface SenderContract {
    function getType();
    /**
     * @param Collection|ReceiptContract[] $receipts
     * @return void
     */
    function setReceipts($receipts);

    /**
     * @param MessageBuilderContract $messageBuilder
     * @return AppResponse
     */
    function send($messageBuilder);
}