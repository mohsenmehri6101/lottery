<?php

namespace Modules\Notification\Http\Notifications\Channels\Notice\Layouts\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Authentication\Entities\User;

class ForgetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $token;
    protected $user;

    public function __construct(User $user,$token)
    {
        $this->token=$token;
        $this->user=$user;
    }

    public function build()
    {
        return $this->markdown('emails.forgetPasswordMail',['token'=>$this->token,'user'=>$this->user]);
//        return $this->view('emails.forgetPasswordMail',['token'=>$this->token,'user'=>$this->user]);
    }
}
