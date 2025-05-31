<?php

namespace App\Services;

use App\Models\Url;
use App\Notifications\UrlExpiredNotification;
use Exception;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send URL expiration notification to the URL owner.
     *
     * @param Url $url
     * @return bool
     */
    public function sendUrlExpirationNotification(Url $url)
    {
        try {
            // Only send notifications for URLs that have an owner
            if ($url->user) {
                $url->user->notify(new UrlExpiredNotification($url));
                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::error('Failed to send URL expiration notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process and send notifications for all expired URLs.
     * This can be called from a scheduled task.
     *
     * @return int Number of notifications sent
     */
    public function processExpiredUrlNotifications(): int
    {
        $count = 0;

        try {

            $date = now()->addDay();
            $expiredUrls = Url::whereNotNull('user_id')
                ->whereNotNull('expires_at')
                ->whereDate('expires_at', '=', $date)
                ->get();

            foreach ($expiredUrls as $url) {
                if ($this->sendUrlExpirationNotification($url)) {
                    $count++;
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to process expired URL notifications: ' . $e->getMessage());
        }

        return $count;
    }
}
