@extends('auth.app')

@section('content')
<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                                @endif
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2"> {{ __('Confirm Password') }}</h1>
                                    <p class="mb-4">{{ __('Please confirm your password before continuing.') }}</p>
                                </div>
                                <form class="user" method="POST" action="{{ route('password.confirm') }}" id="frmForgetPassword">
                                    @csrf
                                    <div class="form-group">
                                        <input id="password" type="password" class="form-control  form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" autofocus  placeholder="Enter {{ __('Password') }}...">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        {{ __('Confirm Password') }}
                                    </button>
                                </form>
                                <hr>
                                @if (Route::has('password.request'))
                                <div class="text-center">
                                    <a href="{{ route('password.request') }}" class="small">Forgot Password?</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
