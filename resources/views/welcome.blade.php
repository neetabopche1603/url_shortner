@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 text-center">
            <h1 class="display-4 fw-bold mb-4">Welcome to URL Shortener</h1>
            <p class="lead mb-5">Create short, memorable links that redirect to your long URLs. Track clicks, manage expiration dates, and more.</p>

            <div class="row g-4 py-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-link-45deg fs-1"></i>
                            </div>
                            <h5 class="card-title">Shorten URLs</h5>
                            <p class="card-text">Convert long, unwieldy links into short, memorable URLs that are easy to share.</p>
                            <a href="{{ route('urls.create') }}" class="btn btn-primary">Shorten a URL</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-graph-up fs-1"></i>
                            </div>
                            <h5 class="card-title">Track Analytics</h5>
                            <p class="card-text">Monitor clicks, track visitor information, and analyze referrers for your links.</p>
                            @auth
                                <a href="{{ route('urls.index') }}" class="btn btn-primary">View My URLs</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Sign In to Track</a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-clock-history fs-1"></i>
                            </div>
                            <h5 class="card-title">Manage Expiration</h5>
                            <p class="card-text">Set expiration dates for your URLs and receive notifications when they expire.</p>
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-primary">Register Now</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h2 class="mb-4">How It Works</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h5>1. Create</h5>
                            <p>Enter your long URL and get a shortened link instantly.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h5>2. Share</h5>
                            <p>Share your shortened link on social media, emails, or messages.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h5>3. Track</h5>
                            <p>Monitor clicks and visitor information for your links.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endpush
