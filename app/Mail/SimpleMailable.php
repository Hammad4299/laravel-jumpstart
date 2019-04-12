<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class SimpleMailable extends BaseMailable
{
    use Queueable, SerializesModels;
    public $content;
    public $esubject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $subject)
    {
        //
        $this->content = $content;
        $this->esubject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->html($this->content)
                ->subject($this->esubject);
                // ->text('email.text',[
                //     'text'=>strip_tags($this->content)
                // ]);
    }
}
