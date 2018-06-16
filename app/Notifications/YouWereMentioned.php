<?php

namespace App\Notifications;

use App\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class YouWereMentioned extends Notification
{
    use Queueable;

    /**
     * @var \App\Reply or \App\Thread
     */
    protected $subject;

    /**
     * Create a new notification instance.
     *
     * @param $subject
     */
    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message(),
            'notifier' => $this->user(),
            'link' => $this->subject->path()
        ];
    }

    /**
     * Get a message title for the notification.
     */
    public function message()
    {
        return sprintf('%s mentioned you in "%s"', $this->user()->name, $this->subject->title());
    }

    /**
     * Get the associated user for the subject.
     */
    public function user()
    {
        // dd($this->subject instanceof Reply);
        return $this->subject instanceof Reply ? $this->subject->owner : $this->subject->creator;
    }
}
