@extends('layouts.app')
@section('title','User Profile View')
@section('content')

<div class="container mt-lg-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ ('User Profile') }}</div>

                <div class="card-body">
                    @include('flashMessage')

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>{{ ('Personal Information') }}</h5>
                            <hr>
                            <dl class="row">
                                <dt class="col-sm-3">{{ ('Name') }}</dt>
                                <dd class="col-sm-9">{{ $user->name }}</dd>

                                <dt class="col-sm-3">{{ ('Email') }}</dt>
                                <dd class="col-sm-9">
                                    {{ $user->email }}
                                </dd>

                                <dt class="col-sm-3">{{ ('Created_at') }}</dt>
                                <dd class="col-sm-9">{{ $user->created_at->format('F d, Y') }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="{{ route('profile.edit') }}" class="btn btn-success me-md-2">
                                    {{ ('Edit Profile') }}
                                </a>
                                <a href="{{ route('profile.change-password') }}" class="btn btn-secondary">
                                    {{ ('Change Password') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
