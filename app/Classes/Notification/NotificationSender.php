<?php

namespace App\Classes\Notification;

use App\Classes\AppResponse;
use App\Models\ClientSetting;
use App\Classes\Notification\Receipt\ReceiptContract;
use App\Classes\Notification\Sender\EyeconicSmsSender;
use App\Classes\Notification\Receipt\SMSReceiptContract;
use App\Classes\Notification\Receipt\EmailReceiptContract;
use App\Classes\Notification\MessageBuilder\SMSMessageBuilderContract;

class NotificationSender {
    /**
     * @var EmailMessageBuilderContract
     */
    protected $emailMessageBuilder;

    /**
     * @var SMSMessageBuilderContract
     */
    protected $smsMessageBuilder;

    /**
     * @var Client
     */
    protected $client;
    /**
     * @var ClientSetting
     */
    protected $clientSetting;
    
    /**
     * @param Client $client
     * @param ClientSetting $clientSetting
     */
    public function __construct($client, $clientSetting)
    {
        $this->client = $client;
        $this->clientSetting = $clientSetting;
    }

    public function setSmsMessageBuilder($builder) {
        $this->smsMessageBuilder = $builder;
    }

    /**
     * Make adjustment to receipt addresses for easy development
     * @param (EmailReceiptContract|SMSReceiptContract)[] $receipts
     */
    public static function makeDefaultAdjustments($receipts) {
        $sms = config('app.sms');
        foreach ($receipts as $receipt) {
            if(method_exists($receipt,'setDestinationEmail')) {
                //TODO
            }
            if($sms['onlyDefault']=='true' && method_exists($receipt,'setDestinationPhoneNumber')) {
                $receipt->setDestinationPhoneNumber($sms['default']);
            }
        }
    }

    /**
     * @param ReceiptContract[] $receipts
     * @return AppResponse
     */
    public function send($receipts) {
        self::makeDefaultAdjustments($receipts);
        $resp = new AppResponse(true);
        if($this->smsMessageBuilder) {
            $sender = new EyeconicSmsSender($this->client,$this->clientSetting->sms_setting);
            $sender->setReceipts($receipts);
            $resp = $sender->send($this->smsMessageBuilder);
        }
        return $resp;
    }
}