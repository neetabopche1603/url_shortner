<?php

namespace App\Http\Controllers;

use App\Services\UrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{

    protected $urlService;

    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }

    // Show the dashboard with user's URLs
    public function index()
    {
        $urls = $this->urlService->getUrlsForUser(Auth::id());
        return view('dashboard', compact('urls'));
    }
}
