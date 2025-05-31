<?php

namespace App\Services;

use App\Models\Url;
use App\Models\UrlVisit;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UrlService
{
    //     Generate a unique short code for a URL.
    public function generateUniqueCode()
    {
        $code = Str::random(6);

        // Check if code already exists
        while (Url::where('short_code', $code)->exists()) {
            $code = Str::random(6);
        }

        return $code;
    }

    /**
     * Create a new shortened URL.
     *
     * @param string $originalUrl
     * @param int|null $expiresInDays
     * @param string|null $customCode
     * @return Url|null
     */
    public function createShortUrl(string $originalUrl, ?int $expiresInDays = 30, ?string $customCode = null)
    {
        try {
            $expiresAt = $expiresInDays ? Carbon::now()->addDays($expiresInDays) : null;

            $url = new Url();
            $url->original_url = $originalUrl;
            $url->short_code = $customCode ?: $this->generateUniqueCode();
            $url->expires_at = $expiresAt;
            $url->user_id = Auth::id();
            $url->save();

            return $url;
        } catch (Exception $e) {
            Log::error('Failed to create short URL: ' . $e->getMessage());
            return null;
        }
    }

    //  Find a URL by its short code.
    public function findByShortCode(string $shortCode)
    {
        return Url::where('short_code', $shortCode)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            })
            ->where('is_active', true)
            ->first();
    }

    // Record a visit to a URL.
    public function recordVisit(Url $url, Request $request)
    {
        try {

            UrlVisit::create([
                'url_id' => $url->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
            ]);

            // Increment click count
            $url->incrementClickCount();
        } catch (Exception $e) {
            Log::error('Failed to record URL visit: ' . $e->getMessage());
        }
    }

    //    Get all URLs for a user.
    public function getUrlsForUser(int $userId)
    {
        return Url::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    //    Update a URL.
    public function updateUrl(Url $url, array $data)
    {
        try {
            // We don't allow changing the short_code for existing URLs
            unset($data['short_code']);

            if (isset($data['expires_in_days']) && $data['expires_in_days']) {
                $data['expires_at'] = Carbon::now()->addDays($data['expires_in_days']);
                unset($data['expires_in_days']);
            }

            return $url->update($data);
        } catch (Exception $e) {
            Log::error('Failed to update URL: ' . $e->getMessage());
            return false;
        }
    }

    // Delete a URL.
    public function deleteUrl(Url $url)
    {
        try {
            return $url->delete();
        } catch (Exception $e) {
            Log::error('Failed to delete URL: ' . $e->getMessage());
            return false;
        }
    }

    // Toggle the active status of a URL.
    public function toggleUrlStatus(Url $url)
    {
        try {
            $url->is_active = !$url->is_active;
            return $url->save();
        } catch (Exception $e) {
            Log::error('Failed to toggle URL status: ' . $e->getMessage());
            return false;
        }
    }

    // Get the full short URL.
    public function getFullShortUrl(Url $url)
    {
        return url('/s/' . $url->short_code);
    }

    //    Check for expired URLs and process them.
    public function processExpiredUrls()
    {
        $count = 0;

        try {
            $expiredUrls = Url::where('is_active', true)
                ->whereNotNull('expires_at')
                ->where('expires_at', '<', Carbon::now())
                ->get();

            foreach ($expiredUrls as $url) {
                $url->is_active = false;
                $url->save();
                $count++;
            }
        } catch (Exception $e) {
            Log::error('Failed to process expired URLs: ' . $e->getMessage());
        }

        return $count;
    }
}
