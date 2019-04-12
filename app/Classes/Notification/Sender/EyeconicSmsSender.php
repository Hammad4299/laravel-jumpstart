<?php

namespace App\Classes\Notification\Sender;

use App\Classes\AppResponse;
use Illuminate\Support\Collection;
use App\Classes\Notification\Receipt\ReceiptContract;
use App\Classes\Notification\Receipt\SMSReceiptContract;
use App\Classes\Notification\MessageBuilder\SMSMessageBuilderContract;
use App\Apis\EyeconicSmsApiClient;
use App\Classes\Notification\SenderType;

class EyeconicSmsSender implements SenderContract {
    /**
     * @var Collection|SMSReceiptContract[]
     */
    protected $receipts;

    protected $apiClient;
    
    public function __construct()
    {
        $this->apiClient = new EyeconicSmsApiClient($this->clientSmsSetting->user_guid);
    }

    function getType() {
        return SenderType::TYPE_SMS;
    }
    /**
     * @param Collection|SMSReceiptContract[] $receipts
     * @return void
     */
    function setReceipts($receipts) {
        $this->receipts = $receipts;
    }

    /**
     * @param SMSMessageBuilderContract $messageBuilder
     * @return AppResponse
     */
    function send($messageBuilder) {
        $resp = new AppResponse(true);
        
        if($resp->getStatus()) {
            $messageBuilder->setPreferredReplacements([
                'first_name'=>'~firstname~',
                'last_name'=>'~lastname~',
                'email'=>'~email~',
                'mobile'=>'~mobile~'
            ]);
            $message = $messageBuilder->buildSmsMessage();

            try {
                foreach ($this->receipts as $receipt) {
                    $this->apiClient->sendMessage($receipt->getDestinationPhoneNumber(),$message->getMessage());
                }
            } catch(\Exception $e) {
                $resp->setStatus(false);
                $resp->message = $e->getMessage();
                report($e);
            }
        }
        return $resp;
    }
}