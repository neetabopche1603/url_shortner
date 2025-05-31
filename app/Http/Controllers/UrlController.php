<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Services\UrlService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UrlController extends Controller
{

    protected $urlService;

    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }


    // Show the form to create a new shortened URL.
    public function create()
    {
        return view('urls.create');
    }

    //    Store a new created URL in storage.
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url|max:2048',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ]);

        try {
            $url = $this->urlService->createShortUrl(
                $request->input('original_url'),
                $request->input('expires_in_days')
            );

            if ($url) {
                $shortUrl = $this->urlService->getFullShortUrl($url);
                return redirect()->route('urls.success')->with([
                    'shortUrl' => $shortUrl,
                    'originalUrl' => $url->original_url,
                ]);
            }

            return redirect()->back()->with('error', 'Failed to create shortened URL. Please try again.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    //    Show the success page after creating a URL.
    public function success()
    {
        if (!session('shortUrl') || !session('originalUrl')) {
            return redirect()->route('urls.create');
        }

        return view('urls.success', [
            'shortUrl' => session('shortUrl'),
            'originalUrl' => session('originalUrl'),
        ]);
    }

    //   Redirect from a short URL to the original URL.
    public function redirect(string $shortCode, Request $request)
    {
        try {
            $url = $this->urlService->findByShortCode($shortCode);

            if (!$url) {
                return redirect()->route('urls.create')
                    ->with('error', 'The URL you are trying to access has expired or does not exist.');
            }

            // Record the visit
            $this->urlService->recordVisit($url, $request);

            // Redirect to exit page which will then redirect to the original URL
            return redirect()->route('urls.exit', ['shortCode' => $shortCode]);
        } catch (Exception $e) {
            return redirect()->route('urls.create')
                ->with('error', 'An error occurred while redirecting: ' . $e->getMessage());
        }
    }

    // Show the exit page before redirecting to the original URL.
    public function showExitPage(string $shortCode)
    {
        try {
            $url = $this->urlService->findByShortCode($shortCode);

            if (!$url) {
                return redirect()->route('urls.create')
                    ->with('error', 'The URL you are trying to access has expired or does not exist.');
            }

            return view('urls.exit', [
                'url' => $url,
                'countdown' => 5, // 5 seconds countdown before redirect
            ]);
        } catch (Exception $e) {
            return redirect()->route('urls.create')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // Display a listing of the user's URLs.
    public function index()
    {
        $urls = $this->urlService->getUrlsForUser(Auth::id());
        return view('urls.index', compact('urls'));
    }

    //     Show the form for editing the specified URL.
    public function edit(Url $url)
    {
        // Check if the user owns this URL
        if ($url->user_id !== Auth::id()) {
            return redirect()->route('urls.index')
                ->with('error', 'You do not have permission to edit this URL.');
        }

        return view('urls.edit', compact('url'));
    }

    //     Update the specified URL in storage.
    public function update(Request $request, Url $url)
    {
        $request->validate([
            'original_url' => 'required|url|max:2048',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
            'is_active' => 'boolean',
        ]);

        // Check if the user owns this URL
        if ($url->user_id !== Auth::id()) {
            return redirect()->route('urls.index')
                ->with('error', 'You do not have permission to update this URL.');
        }

        try {
            if ($this->urlService->updateUrl($url, $request->only(['original_url', 'expires_in_days', 'is_active']))) {
                return redirect()->route('urls.index')
                    ->with('success', 'URL updated successfully.');
            }

            return redirect()->back()
                ->with('error', 'Failed to update URL. Please try again.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // Remove the specified URL from storage.
    public function destroy(Url $url)
    {
        // Check if the user owns this URL
        if ($url->user_id !== Auth::id()) {
            return redirect()->route('urls.index')
                ->with('error', 'You do not have permission to delete this URL.');
        }

        try {
            if ($this->urlService->deleteUrl($url)) {
                return redirect()->route('urls.index')
                    ->with('success', 'URL deleted successfully.');
            }

            return redirect()->back()
                ->with('error', 'Failed to delete URL. Please try again.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // Toggle the active status of the specified URL.
    public function toggleStatus(Url $url)
    {
        // Check if the user owns this URL
        if ($url->user_id !== Auth::id()) {
            return redirect()->route('urls.index')
                ->with('error', 'You do not have permission to change this URL status.');
        }

        try {
            if ($this->urlService->toggleUrlStatus($url)) {
                $status = $url->is_active ? 'activated' : 'deactivated';
                return redirect()->route('urls.index')
                    ->with('success', "URL {$status} successfully.");
            }

            return redirect()->back()
                ->with('error', 'Failed to toggle URL status. Please try again.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    //    Show the URL analytics.
    public function view(Url $url)
    {
        // Check if the user owns this URL
        if ($url->user_id !== Auth::id()) {
            return redirect()->route('urls.index')
                ->with('error', 'You do not have permission to view analytics for this URL.');
        }

        // Load URL with its visits
        $url->load('visits');

        return view('urls.view', compact('url'));
    }
}
