<?php

namespace App\Classes\Notification\Sender;

use App\Models\Client;
use App\DTO\SMSSettingDTO;
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
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var SMSSettingDTO
     */
    protected $clientSmsSetting;
    /**
     * @var EyeconicSmsApiClient
     */
    protected $apiClient;
    /**
     * @param Client $client
     * @param SMSSettingDTO $smsSetting
     */
    public function __construct($client, $smsSetting)
    {
        $this->client = $client;
        $this->clientSmsSetting = $smsSetting;
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
        $keyword = $this->clientSmsSetting->keyword;
        $user_guid = $this->clientSmsSetting->user_guid;
        $shortcode = $this->clientSmsSetting->shortcode;
        if(empty($shortcode) || empty($user_guid) || empty($keyword)) {
            $resp->addError('shortcode','Client not configured for sms');
            $resp->addError('client','Client not configured for sms');
            $resp->addError('keyword','Client not configured for sms');
            $resp->addError('shortcode','Client not configured for sms');
        }
        
        if($resp->getStatus()) {
            $messageBuilder->setPreferredReplacements([
                'first_name'=>'~firstname~',
                'last_name'=>'~lastname~',
                'email'=>'~email~',
                'mobile'=>'~mobile~',
                'address'=>'~address~',
                'city'=>'~city~',
                'state'=>'~state_id~',
                'zip'=>'~zip~',
                'country'=>'~country_id~'
            ]);
    
            $message = $messageBuilder->buildSmsMessage();

            try {
                
                foreach ($this->receipts as $receipt) {
                    $this->apiClient->registerContact($receipt);
                    $this->apiClient->sendMessage($receipt->getDestinationPhoneNumber(),$message->getMessage(), $this->clientSmsSetting->keyword, $this->clientSmsSetting->shortcode);
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