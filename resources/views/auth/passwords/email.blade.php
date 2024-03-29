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
                                    <h1 class="h4 text-gray-900 mb-4"> {{ __('Reset Password') }}</h1>
                                </div>
                                <form class="user" method="POST" action="{{ route('password.email') }}" id="frmForgetPassword">
                                    @csrf
                                    <div class="form-group">
                                        <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus  placeholder="Enter {{ __('E-Mail Address') }}..."> 
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </form>
                                <hr>
                                @if (Route::has('register'))
                                <div class="text-center">
                                    <a href="{{ route('register') }}" class="small">Create an Account!</a>
                                </div>
                                @endif
                                @if (Route::has('login'))
                                <div class="text-center">
                                    <a href="{{ route('login') }}" class="small">Already have an account? Login!</a>
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

@section('scripts')
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#frmForgetPassword').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        }
    });
});
</script>
@endsection
