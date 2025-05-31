@extends('layouts.app')
@section('title','Create')
@section('content')
    <div class="container mt-lg-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Short URL</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('urls.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="original_url" class="form-label">Original URL</label>
                                <input id="original_url" type="url"
                                    class="form-control @error('original_url') is-invalid @enderror" name="original_url"
                                    value="{{ old('original_url') }}" required autofocus placeholder="https://example.com">

                                @error('original_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="expires_in_days" class="form-label">Expires After (days)</label>

                                <input id="expires_in_days" type="number"
                                    class="form-control @error('expires_in_days') is-invalid @enderror"
                                    name="expires_in_days" value="{{ old('expires_in_days', 30) }}" min="1"
                                    max="365">

                                @error('expires_in_days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div class="form-text">
                                    Leave empty for a URL that never expires.
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    Shorten URL
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
