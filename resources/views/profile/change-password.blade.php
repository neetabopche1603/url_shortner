@extends('layouts.app')
@section('title', 'User Change Password')
@section('content')
    <div class="container mt-lg-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ 'Change Password' }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update-password') }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="current_password"
                                    class="col-md-4 col-form-label text-md-end">{{ 'Current Password' }}</label>

                                <div class="col-md-6">
                                    <input id="current_password" type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        name="current_password" required autocomplete="current-password">

                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ 'New Password' }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ 'Confirm New Password' }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4 d-flex">
                                    <button type="submit" class="btn btn-success me-2">
                                        {{ 'Change Password' }}
                                    </button>
                                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                        {{ 'Cancel' }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
