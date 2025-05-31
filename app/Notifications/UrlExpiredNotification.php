<?php

namespace App\Notifications;

use App\Models\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UrlExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The URL that has expired.
     *
     * @var Url
     */
    protected $url;

    /**
     * Create a new notification instance.
     *
     * @param Url $url
     * @return void
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Shortened URL has expiry soon!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your shortened URL has expiry soon:')
            ->line('Original URL: ' . $this->url->original_url)
            ->line('Short URL: ' . url('/s/' . $this->url->short_code))
            ->line('Expired at: ' . $this->url->expires_at->format('d M Y H:i:s'))
            ->action('Manage Your URLs', url('/dashboard'))
            ->line('Thank you for using our URL shortener service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'url_id' => $this->url->id,
            'short_code' => $this->url->short_code,
            'original_url' => $this->url->original_url,
            'expired_at' => $this->url->expires_at,
        ];
    }
}
