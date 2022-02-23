<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionEmailSend extends Mailable
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
        $usersNew =$this->users;
        if($usersNew['type'] == "Debit"){
            $this->subject( 'Transaction Reports' )
            ->view( 'email.wallet_amount_debit',$confirmed = array('user_info'=>$usersNew['user_name_influ'],'job_cost'=>$usersNew['job_cost'],'username'=>$usersNew['user_name'],'job_name' => $usersNew['job_name'] ) );
        } else {
            
        /*return $this->markdown('email.wallet_amount_credit',$users);*/
        $this->subject( 'Transaction Reports' )
            ->view( 'email.wallet_amount_credit',$confirmed = array('user_info'=>$usersNew['user_name_influ'],'job_cost'=>$usersNew['job_cost'],'username'=>$usersNew['user_name'],'job_name' => $usersNew['job_name'] ) );
        }              
    }
}
