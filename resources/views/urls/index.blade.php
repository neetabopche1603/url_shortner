@extends('layouts.app')
@section('title','List')
@section('content')
  <div class="container mt-lg-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                @include('flashMessage')

                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ ('My URLs') }}</span>
                    <a href="{{ route('urls.create') }}" class="btn btn-sm btn-primary">{{ ('Create New URL') }}</a>
                </div>

                <div class="card-body">
                    @if($urls->isEmpty())
                        <div class="alert alert-info">
                            <p class="mb-0">{{ ('You haven\'t created any URLs yet.') }}</p>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('urls.create') }}" class="btn btn-primary">{{ ('Create Your First URL') }}</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Short URL</th>
                                        <th>Original URL</th>
                                        <th>Created</th>
                                        <th>Expires</th>
                                        <th>Clicks</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($urls as $url)
                                        <tr>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control form-control-sm" value="{{ url('/s/' . $url->short_code) }}" readonly>

                                                    <button class="btn btn-sm btn-outline-primary copy-btn" type="button" data-clipboard-text="{{ url('/s/' . $url->short_code) }}">

                                                        <i class="fas fa-copy"></i> {{ ('Copy') }}
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $url->original_url }}">
                                                    {{ $url->original_url }}
                                                </span>
                                            </td>
                                            <td>{{ $url->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($url->expires_at)
                                                    {{ $url->expires_at->format('M d, Y') }}
                                                    @if($url->isExpired())
                                                        <span class="badge bg-danger">{{ ('Expired') }}</span>
                                                    @elseif($url->expires_at->diffInDays(now()) <= 3)
                                                        <span class="badge bg-warning">{{ ('Expiring Soon') }}</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">{{ ('Never') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $url->click_count }}</td>
                                            <td>
                                                @if($url->is_active)
                                                    <span class="badge bg-success">{{ ('Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ ('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('urls.view', $url) }}" class="btn btn-sm btn-info">
                                                        {{ ('View') }}
                                                    </a>
                                                    <a href="{{ route('urls.edit', $url) }}" class="btn btn-sm btn-primary">
                                                        {{ ('Edit') }}
                                                    </a>
                                                    <form action="{{ route('urls.toggle', $url) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm {{ $url->is_active ? 'btn-warning' : 'btn-success' }}">
                                                            {{ $url->is_active ? ('Disable') : ('Enable') }}
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('urls.destroy', $url) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            {{ ('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
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
                this.innerHTML = '<i class="fas fa-check"></i> {{ ("Copied!") }}';
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

    // Confirmation for delete
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('{{ ("Are you sure you want to delete this URL? This action cannot be undone.") }}')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush

@push('scripts')
<script>
    // Confirm deletion
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('{{ ("Are you sure you want to delete this URL? This action cannot be undone.") }}')) {
                this.submit();
            }
        });
    });

    // Copy URL functionality
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const text = this.getAttribute('data-clipboard-text');
            navigator.clipboard.writeText(text).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '{{ ("Copied!") }}';
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
