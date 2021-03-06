<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterUser extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$code)
    {
        $this->user=$user;
        $this->code=$code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url('api/verifyemail/'.$this->code);
        return $this->view('RegisterUser')->with([
            "fullname"=>$this->user->fullname,
            "url"=>$url,
            "code"=>$this->code,
          
        ]);
    }
}
