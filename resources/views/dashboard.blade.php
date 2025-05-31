@extends('layouts.app')

@section('content')
    <div class="container mt-lg-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ 'Dashboard' }}</div>

                    <div class="card-body">

                        @include('flashMessage')

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <h1 class="display-4">{{ $urls->count() }}</h1>
                                        <h5 class="card-title">{{ 'Total URLs' }}</h5>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('urls.index') }}"
                                            class="btn btn-sm btn-primary">{{ 'View All' }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <h1 class="display-4">{{ $urls->sum('click_count') }}</h1>
                                        <h5 class="card-title">{{ 'Total Clicks' }}</h5>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('urls.create') }}"
                                            class="btn btn-sm btn-success">{{ 'Create New URL' }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <h1 class="display-4">{{ $urls->where('is_active', true)->count() }}</h1>
                                        <h5 class="card-title">{{ 'Active URLs' }}</h5>
                                    </div>
                                    <div class="card-footer">
                                        @php
                                            $expiredCount = $urls
                                                ->filter(function ($url) {
                                                    return $url->expires_at && $url->expires_at->isPast();
                                                })
                                                ->count();
                                        @endphp
                                        @if ($expiredCount > 0)
                                            <span
                                                class="badge bg-warning">{{ __(':count expired', ['count' => $expiredCount]) }}</span>
                                        @else
                                            <span class="badge bg-success">{{ 'All Active Urls' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ 'Recent URLs' }}</h5>
                            </div>
                            <div class="card-body">
                                @if ($urls->isEmpty())
                                    <div class="alert alert-info">
                                        <p class="mb-0">{{ 'You haven\'t created any URLs yet.' }}</p>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('urls.create') }}"
                                            class="btn btn-primary">{{ 'Create Your First URL' }}</a>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Short URL</th>
                                                    <th>Original URL</th>
                                                    <th>Created</th>
                                                    <th>Clicks</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($urls->take(5) as $url)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ url('/s/' . $url->short_code) }}" target="_blank">
                                                                {{ url('/s/' . $url->short_code) }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="d-inline-block text-truncate"
                                                                style="max-width: 200px;" title="{{ $url->original_url }}">
                                                                {{ $url->original_url }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $url->created_at->format('M d, Y') }}</td>
                                                        <td>{{ $url->click_count }}</td>
                                                        <td>
                                                            @if ($url->is_active)
                                                                @if ($url->expires_at && $url->expires_at->isPast())
                                                                    <span
                                                                        class="badge bg-danger">{{ __('Expired') }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-success">{{ __('Active') }}</span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ route('urls.view', $url) }}"
                                                                    class="btn btn-sm btn-info">{{ __('View') }}</a>
                                                                <a href="{{ route('urls.edit', $url) }}"
                                                                    class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if ($urls->count() > 5)
                                        <div class="text-center mt-3">
                                            <a href="{{ route('urls.index') }}"
                                                class="btn btn-outline-primary">{{('View All URLs') }}</a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
    <script>
        // Copy URL functionality
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const text = this.getAttribute('data-clipboard-text');
                navigator.clipboard.writeText(text).then(() => {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> {{ __('Copied!') }}';
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-success');

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-outline-primary');
                    }, 2000);
                });
            });
        });
    </script>
@endpush
