@extends('layouts.app')
@section('title','Success')

@section('content')
<div class="container mt-lg-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">URL Shortened Successfully</div>

                <div class="card-body">
                    <div class="alert alert-success mb-4">
                        <p class="mb-0">{{('Your URL has been shortened successfully!') }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">{{('Original URL:') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $originalUrl }}" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">{{('Short URL:') }}</label>
                        <div class="input-group">
                            <input id="shortUrlInput" type="text" class="form-control" value="{{ $shortUrl }}" readonly>
                            <button class="btn btn-outline-primary" type="button" id="copyButton" onclick="copyShortUrl()">{{ ('Copy') }}</button>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ $shortUrl }}" target="_blank" class="btn btn-primary">{{ ('Open Short URL') }}</a>
                        <a href="{{ route('urls.create') }}" class="btn btn-outline-secondary">{{('Create Another URL') }}</a>

                        @guest
                            <div class="alert alert-info mt-3">
                                <p class="mb-0">{{ ('Sign up or log in to manage your shortened URLs.') }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary flex-grow-1">{{('Login') }}</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-success flex-grow-1">{{ ('Register') }}</a>
                            </div>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-info">{{('Go to Dashboard') }}</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyShortUrl() {
        var copyText = document.getElementById("shortUrlInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");

        var copyButton = document.getElementById("copyButton");
        copyButton.innerHTML = "{{ __('Copied!') }}";
        copyButton.classList.remove("btn-outline-primary");
        copyButton.classList.add("btn-success");

        setTimeout(function() {
            copyButton.innerHTML = "{{ __('Copy') }}";
            copyButton.classList.remove("btn-success");
            copyButton.classList.add("btn-outline-primary");
        }, 2000);
    }
</script>
@endpush
