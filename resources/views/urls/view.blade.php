@extends('layouts.app')
@section('title', 'View Details')

@section('content')
    <div class="container mt-lg-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ ('URL Details') }}</span>
                        <a href="{{ route('urls.index') }}" class="btn btn-sm btn-secondary">{{ ('Back to URLs') }}</a>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        {{ ('URL Information') }}
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <strong>{{ ('Short URL') }}:</strong>
                                            <div class="input-group input-group-sm mt-1">
                                                <input type="text" class="form-control form-control-sm"
                                                    value="{{ url('/s/' . $url->short_code) }}" readonly>
                                                <button class="btn btn-sm btn-outline-primary copy-btn" type="button"
                                                    data-clipboard-text="{{ url('/s/' . $url->short_code) }}">
                                                    {{ ('Copy') }}
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>{{ ('Original URL') }}:</strong>
                                            <div class="text-break">
                                                <a href="{{ $url->original_url }}"
                                                    target="_blank">{{ $url->original_url }}</a>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>{{ ('Created') }}:</strong>
                                            {{ $url->created_at->format('M d, Y H:i') }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>{{ ('Expires') }}:</strong>
                                            @if ($url->expires_at)
                                                {{ $url->expires_at->format('M d, Y H:i') }}
                                                @if ($url->isExpired())
                                                    <span class="badge bg-danger">{{ ('Expired') }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">{{ ('Never') }}</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item">
                                            <strong>{{ ('Status') }}:</strong>
                                            @if ($url->is_active)
                                                <span class="badge bg-success">{{ ('Active') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ ('Inactive') }}</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item">
                                            <strong>{{ ('Total Clicks') }}:</strong>
                                            <span class="badge bg-primary">{{ $url->click_count }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                {{ ('Recent Visits') }}
                            </div>
                            <div class="card-body">
                                @if ($url->visits->isEmpty())
                                    <div class="alert alert-info">
                                        {{ ('No visits recorded yet.') }}
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ ('Date & Time') }}</th>
                                                    <th>{{ ('IP Address') }}</th>
                                                    <th>{{ ('User Agent') }}</th>
                                                    <th>{{ ('Referrer') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($url->visits->sortByDesc('created_at') as $visit)
                                                    <tr>
                                                        <td>{{ $visit->created_at->format('M d, Y H:i:s') }}</td>
                                                        <td>{{ $visit->ip_address }}</td>
                                                        <td>
                                                            <span class="d-inline-block text-truncate"
                                                                style="max-width: 250px;" title="{{ $visit->user_agent }}">
                                                                {{ $visit->user_agent }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if ($visit->referrer)
                                                                <span class="d-inline-block text-truncate"
                                                                    style="max-width: 250px;"
                                                                    title="{{ $visit->referrer }}">
                                                                    {{ $visit->referrer }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted">{{ ('Direct') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('script')
    <script>
        // Copy URL functionality
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const text = this.getAttribute('data-clipboard-text');
                navigator.clipboard.writeText(text).then(() => {
                    const originalText = this.innerHTML;
                    this.innerHTML = '{{ ('Copied!') }}';
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
