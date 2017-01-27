<?php

namespace App\Notifications;

use App\EOSRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EOSRequestCompleted extends Notification
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
      $name = $this->eos->name;
        return (new MailMessage)
                    ->success()
                    ->line('Your request, '.$name.' has been completed.')
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
