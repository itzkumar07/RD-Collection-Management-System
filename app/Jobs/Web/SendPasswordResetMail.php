<?php

namespace App\Jobs\Web;

use App\Notifications\Web\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendPasswordResetMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mail_id;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mail_id,$url)
    {
        $this->url = $url;
        $this->mail_id = $mail_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::route('mail',$this->mail_id)->notify(new PasswordReset($this->url));
    }
}
