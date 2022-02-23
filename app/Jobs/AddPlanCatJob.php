<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\AddPlanCatSendEmail;
use Mail;

class AddPlanCatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $email;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email,$user)
    {
        
        $this->email = $email;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userData = $this->user;
        $email = $this->email;
        //dd($email);

        $emails = new AddPlanCatSendEmail($userData);   
        Mail::to($email)->send($emails);
    }
}
