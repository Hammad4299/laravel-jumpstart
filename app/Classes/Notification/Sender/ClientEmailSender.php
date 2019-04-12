<?php

namespace App\Classes\Notification\Sender;

use App\Classes\AppResponse;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Classes\Notification\SenderType;
use App\Classes\Notification\Receipt\ReceiptContract;
use App\Classes\Notification\Receipt\EmailReceiptContract;
use App\Classes\Notification\MessageBuilder\EmailMessageBuilderContract;

class ClientEmailSender implements SenderContract {
    /**
     * @var Collection|EmailReceiptContract[]
     */
    protected $receipts;
    
    public function __construct($smtpSetting)
    {
    }

    function getType() {
        return SenderType::TYPE_EMAIL;
    }
    /**
     * @param Collection|EmailReceiptContract[] $receipts
     * @return void
     */
    function setReceipts($receipts) {
        $this->receipts = $receipts;
    }

    /**
     * @param EmailMessageBuilderContract $messageBuilder
     * @return AppResponse
     */
    function send($messageBuilder) {
        $resp = new AppResponse(true);
        
        if($resp->getStatus()) {
            $message = $messageBuilder->buildMailable();

            // // Backup your default mailer
            // $backup = Mail::getSwiftMailer();

            // // Setup your gmail mailer
            // $transport = new \Swift_SmtpTransport($server, intval($port), $encryption);
            // $transport->setUsername($username);
            // $transport->setPassword($password);
            // $clientMailer = new \Swift_Mailer($transport);
            // Mail::setSwiftMailer($clientMailer);


            try {
                $receipts = [];
                foreach ($this->receipts as $receipt) {
                    if(!empty($receipt->getDestinationEmail())) {
                        $receipts[] = [
                            'name'=>trim($receipt->getFirstName().' '.$receipt->getLastName()),
                            'email'=>$receipt->getDestinationEmail()
                        ];
                    }
                }

                // /**
                //  * @var Mailable $message
                //  */
                // if(method_exists($message,'from')) {
                //     $message->from($senderEmail);
                // }
                //Important to not use queue here. Because it will most probably use .env credential rather than Client SMTP setting.
                if(count($receipts)>0) {
                    Mail::bcc($receipts)
                        ->send($message);
                }
            } catch(\Exception $e) {
                $resp->setStatus(false);
                $resp->message = $e->getMessage();
                report($e);
            }

            // Restore your original mailer
            // Mail::setSwiftMailer($backup);
        }
        return $resp;
    }
}