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
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4"> {{ __('Register') }}</h1>
                                </div>
                                @if($flash = session('error'))            
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    {{ $flash }}
                                </div>
                                @endif
                                @if($flash = session('success'))      
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    {{ $flash }}
                                </div>
                                @endif
                                <form class="user" method="POST" action="{{ route('register') }}" id="frmRegister">
                                    @csrf

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input id="first_name" type="text" class="form-control form-control-user @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" autofocus  placeholder="Enter {{ __('First Name') }}">  
                                                @if($errors->has('first_name'))
                                                <strong for="first_name" class="invalid-feedback">{{ $errors->first('first_name') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input id="last_name" type="text" class="form-control form-control-user @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" autocomplete="last_name" autofocus  placeholder="Enter {{ __('Last Name') }}">  
                                                @if($errors->has('last_name'))
                                                <strong for="last_name" class="invalid-feedback">{{ $errors->first('last_name') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input id="email" type="email" class="form-control autoFillOff form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus  placeholder="Enter {{ __('E-Mail Address') }}..."> 
                                        @if($errors->has('email'))
                                        <strong for="email" class="invalid-feedback">{{ $errors->first('email') }}</strong>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <input id="password" type="password" class="form-control autoFillOff form-control-user @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="{{ __('Enter Password') }}...">
                                        @if($errors->has('password'))
                                        <strong for="password" class="invalid-feedback">{{ $errors->first('password') }}</strong>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <input id="password_confirmation" type="password" class="form-control autoFillOff form-control-user @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="current-password_confirmation" placeholder="{{ __('Enter Confirm Password') }}...">
                                        @if($errors->has('password_confirmation'))
                                        <strong for="password_confirmation" class="invalid-feedback">{{ $errors->first('password_confirmation') }}</strong>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        {{ __('Register') }}
                                    </button>
                                </form>
                                <hr>
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
    jQuery('#frmRegister').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            },
            password_confirmation: {
                required: true,
                equalTo: '[name="password"]'
            }
        }
    });
});
</script>
@endsection
