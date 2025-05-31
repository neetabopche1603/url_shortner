@extends('layouts.app')
@section('title','Edit')
@section('content')
    <div class="container mt-lg-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @include('flashMessage')
                    <div class="card-header">Edit URL</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('urls.update', $url) }}">
                            @csrf

                            <div class="mb-3">
                                <label for="short_code" class="form-label">Short URL</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ url('/s/') }}/</span>
                                    <input id="short_code" type="text" class="form-control"
                                        value="{{ $url->short_code }}" readonly>
                                </div>
                                <div class="form-text">
                                    The short code cannot be changed.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="original_url" class="form-label">Original URL</label>
                                <input id="original_url" type="url"
                                    class="form-control @error('original_url') is-invalid @enderror" name="original_url"
                                    value="{{ old('original_url', $url->original_url) }}" required>

                                @error('original_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="expires_in_days" class="form-label">Extend Expiration (days)</label>
                                <input id="expires_in_days" type="number"
                                    class="form-control @error('expires_in_days') is-invalid @enderror"
                                    name="expires_in_days" value="{{ old('expires_in_days') }}" min="1"
                                    max="365">

                                @error('expires_in_days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">
                                    @if ($url->expires_at)
                                        {{ 'Currently expires on' }}: {{ $url->expires_at->format('M d, Y H:i') }}.
                                        Enter a value to extend from the current expiration date.
                                    @else
                                        This URL never expires. Enter a value to set an expiration date.
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                        name="is_active" value="1" {{ $url->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">{{ ('Active') }}</label>
                                </div>
                                <div class="form-text">
                                    {{ ('Inactive URLs will not redirect to the original URL.') }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ ('Date & Counts') }}</label>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ ('Created') }}
                                        <span>{{ $url->created_at->format('M d, Y H:i') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ ('Clicks') }}
                                        <span class="badge bg-primary rounded-pill">{{ $url->click_count }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('urls.index') }}" class="btn btn-secondary">{{ ('Cancel') }}</a>
                                <button type="submit" class="btn btn-success">
                                    {{ ('Update URL') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
