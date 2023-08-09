@extends('admin.layouts.app')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">{{ __('Profile') }}</h1>
    <!-- Content Row -->
    <div class="card shadow mb-4">
        {!! Form::open(['method' => 'POST', 'files'=>true, 'route' => ['admin.profile.store'], 'class' => 'form-horizontal', 'id' => 'frmProfile']) !!}
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Profile') }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{$errors->has('first_name') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label for="first_name">{{ __('First Name') }} <span style="color:red">*</span></label>                                
                                {!! Form::text('first_name', old('first_name', $user->first_name), ['class' => 'form-control', 'placeholder' => __('First Name')]) !!}
                                @if($errors->has('first_name'))
                                <strong for="first_name" class="help-block">{{ $errors->first('first_name') }}</strong>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{$errors->has('last_name') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label for="last_name">{{ __('Last Name') }} <span style="color:red">*</span></label>                                
                                {!! Form::text('last_name', old('last_name', $user->last_name), ['class' => 'form-control', 'placeholder' => __('Last Name')]) !!}
                                @if($errors->has('last_name'))
                                <strong for="last_name" class="help-block">{{ $errors->first('last_name') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('email') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                        <label for="email">{{ __('Email') }} <span style="color:red">*</span></label>                        
                        {!! Form::text('email', old('email', $user->email), ['class' => 'form-control', 'placeholder' => __('Email')]) !!}
                        @if($errors->has('email'))
                        <strong for="email" class="help-block">{{ $errors->first('email') }}</strong>
                        @endif
                    </div>


                    <div class="form-group {{$errors->has('reset_password') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                        <label for="reset_password">
                            {!! Form::checkbox('reset_password', '1', old('reset_password'), ['id'=>'reset_password']) !!} {{ __('Reset Password') }}</label>
                    </div>

                    <div id="passwordCont">
                        <div class="form-group {{$errors->has('old_password') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                            <label for="old_password">{{ __('Old Password') }} <span style="color:red">*</span></label>                            
                            {!! Form::password('old_password', ['class' => 'form-control autoFillOff', 'placeholder' => __('Old Password')]) !!}
                            @if($errors->has('old_password'))
                            <strong for="old_password" class="help-block">{{ $errors->first('old_password') }}</strong>
                            @endif
                        </div>
                        <div class="form-group {{$errors->has('password') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                            <label for="password">{{ __('Password') }} <span style="color:red">*</span></label>                            
                            {!! Form::password('password', ['class' => 'form-control autoFillOff', 'placeholder' => __('Password'), 'id'=>'password']) !!}
                            @if($errors->has('password'))
                            <strong for="password" class="help-block">{{ $errors->first('password') }}</strong>
                            @endif
                        </div>
                        <div class="form-group {{$errors->has('confirm_password') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                            <label for="confirm_password">{{ __('Confirm Password') }} <span style="color:red">*</span></label>
                            {!! Form::password('password_confirmation', ['class' => 'form-control autoFillOff', 'placeholder' => __('Confirm Password')]) !!}
                            @if($errors->has('confirm_password'))
                            <strong for="password_confirmation" class="help-block">{{ $errors->first('confirm_password') }}</strong>
                            @endif
                        </div>
                    </div>

                    @if(isset($user) && $user->getMedia('profile_picture')->count() > 0 && file_exists($user->getFirstMedia('profile_picture')->getPath()))
                    <div class="form-group row">
                        <div class="col-md-2">                            
                            <img class="img-fluid" src="{{ $user->getFirstMedia('profile_picture')->getFullUrl() }}">
                        </div>
                    </div>
                    @endif
                    <div class="form-group {{$errors->has('profile_picture') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                        <label for="title">{{__('Profile Picture') }} </label>
                        <div></div>
                        {{ Form::file('profile_picture') }}
                        @if($errors->has('profile_picture'))
                        <strong for="profile_picture" class="help-block">{{ $errors->first('profile_picture') }}</strong>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-responsive btn-primary btn-sm">{{ __('Submit') }}</button>
            <a href="{{route('admin.dashboard')}}"  class="btn btn-responsive btn-danger btn-sm">{{ __('Cancel') }}</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#reset_password').change(function(){
        var checked = jQuery(this).prop('checked');
        jQuery('#passwordCont').hide();

        if(checked==true)
            jQuery('#passwordCont').show();
    }).trigger('change');

    jQuery('#frmProfile').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                email:true
            },
            old_password: {
                required: function(){
                    if(jQuery('#frmProfile #reset_password').prop('checked')==false){
                        return false;
                    }else{
                        return true;
                    }
                }
            },
            password: {
                required: function(){
                    if(jQuery('#frmProfile #reset_password').prop('checked')==false){
                        return false;
                    }else{
                        return true;
                    }
                }
            },
            password_confirmation: {
                required: function(){  
                    if(jQuery('#frmProfile #reset_password').prop('checked')==false){
                        return false;
                    }else{
                        return true;
                    }
                },
                equalTo: "#password"
            }
        }
    });
});
</script>
@endsection