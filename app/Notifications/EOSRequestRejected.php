<?php

namespace App\Notifications;

use App\Email;
use App\EOSRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EOSRequestRejected extends Notification
{
    use Queueable;

    protected $eos;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EOSRequest $eos)
    {
        //
        $this->eos = $eos;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      $msg = Email::where('eos','=', $this->eos->id)->pluck('email_message');
      // $url = url('/requests/'.$this->eos->id);
        return (new MailMessage)
                    ->error()
                    ->line('Your EOS request has been rejected for the following reason:')
                    ->line($msg[0])
                    ->action('View Request', 'http://chris.zurka.com/requests/'.$this->eos->id)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
