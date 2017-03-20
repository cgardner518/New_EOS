<?php

namespace App\Mail;

use Auth;
use App\EOSRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $email_stuff;
    public $body;
    public $carb_cop;
    public $eos_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_stuff)
    {
        //
        $this->body = urldecode($email_stuff->message);
        if (isset($email_stuff->carb_cop)) {
          $this->carb_cop = urldecode($email_stuff->carb_cop);
        }
        $this->subject = urldecode($email_stuff->subject);

        $eos = EOSRequest::find($email_stuff->id);
        $this->eos_name = $eos->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      if ($this->carb_cop) {
        return $this->from(Auth::user()->email)
        ->cc($this->carb_cop)
        ->subject($this->subject)
        ->view('email.new');
      }else {
        return $this->from(Auth::user()->email)
        ->subject($this->subject)
        ->view('email.new');
      }
    }
}
