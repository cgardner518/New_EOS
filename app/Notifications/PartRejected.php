<?php

namespace App\Notifications;

use App\StlFile;
use App\EOSRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PartRejected extends Notification
{
    use Queueable;

    protected $part, $eos, $msg;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(StlFile $part, $msg)
    {
        //
        $this->part = $part;
        $this->eos = EOSRequest::find($part->eos_id);
        $this->msg = urldecode($msg);
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
        return (new MailMessage)
                            ->error()
                            ->line('Your .stl file, '. $this->part->file_name .' has been rejected for the following reason:')
                            ->line($this->msg)
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
