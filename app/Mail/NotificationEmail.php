<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $notificationData;
    public $approvalData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notificationData,$approvalData)
    {
            if((!empty($notificationData))){
                $this->notificationData = $notificationData;
            }
            else{
                $this->approvalData = $approvalData;
            }  
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notificationEmail');
    }
}
