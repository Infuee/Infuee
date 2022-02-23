<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\TransactionEmailSend;
use Mail;

class SendEmailTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    protected $users;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details,$users)
    {

        $this->users = $users;
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $userData = $this->users;

        $email = new TransactionEmailSend($userData);   
        Mail::to($this->details['email'])->send($email);
    }
}
