@extends('layouts.app')
@section('title','Exit')

@section('content')
<div class="container mt-lg-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">{{ ('Redirecting...') }}</div>

                <div class="card-body text-center">
                    <h4 class="mb-4">{{ ('You are being redirected to:') }}</h4>

                    <div class="alert alert-info mb-4">
                        <p class="mb-0 text-break">{{ $url->original_url }}</p>
                    </div>

                    <div class="mb-4">
                        <h5>{{ ('Redirecting in') }} <span id="countdown">{{ $countdown }}</span> {{ ('seconds') }}</h5>
                        <div class="progress">
                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ $url->original_url }}" class="btn btn-primary">{{ ('Proceed Now') }}</a>
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary">{{ ('Cancel and Return Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // Countdown timer for redirection
    var countdown = {{ $countdown }};
    var progressBar = document.getElementById('progress-bar');
    var countdownDisplay = document.getElementById('countdown');
    var originalWidth = 100;
    var interval = setInterval(function() {
        countdown--;
        countdownDisplay.textContent = countdown;

        // Update progress bar
        var percentage = (countdown / {{ $countdown }}) * 100;
        progressBar.style.width = percentage + '%';

        if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = "{{ $url->original_url }}";
        }
    }, 1000);
</script>
@endpush
