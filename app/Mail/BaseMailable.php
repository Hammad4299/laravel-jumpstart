<?php

namespace App\Mail;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BaseMailable extends Mailable
{
    //Email field equals 'email' from all places except in scenarios where $to is set directly as in following code without any setter
    use Queueable, SerializesModels;

     /**
     * @return Mailable
     */
    protected function replaceDefaultReceipts() {
        $receipts = config('mail.defaultTo');
        $only = config('mail.onlyDefault');

        if(!empty($receipts) && $only!=false){
            $this->cc = $receipts;
            $this->bcc = $receipts;
            $this->to = $receipts;
        }

        return $this;
    }

    public function send(MailerContract $mailer)
    {
        $this->replaceDefaultReceipts();
        parent::send($mailer);
    }
}
