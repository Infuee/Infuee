<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddPlanCatSendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $users;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->users;
        //return $this->markdown('email.plansadd');
        $this->subject( 'New plan introduction' )
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->view( 'email.plansadd',$confirmed = array( 'user_info'=>$user ) );
    }
}
