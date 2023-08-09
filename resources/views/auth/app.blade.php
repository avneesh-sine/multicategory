<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @php $favicon = getSetting('favicon'); @endphp
    @if(isset($favicon) && $favicon!=''  && \Storage::exists(config('constants.SETTING_IMAGE_URL').$favicon))
    <link rel="shortcut icon" href="{{ \Storage::url(config('constants.SETTING_IMAGE_URL').$favicon) }}">
    @endif

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin-theme/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{asset('admin-theme/css/sb-admin-2.min.css')}}" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-gradient-primary">
    @yield('content')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin-theme/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('admin-theme/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{asset('admin-theme/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset('admin-theme/js/sb-admin-2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var formValidationOptions = {
        errorElement: 'strong', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: true, // do not focus the last invalid input
        ignore: "",
        errorPlacement: function (error, element) { // render error placement for each input type
            if (element.attr("data-error-container")) { 
                error.appendTo(element.attr("data-error-container"));
            }else{
                error.insertAfter(element); // for other inputs, just perform default behavior
            }
        },
        highlight: function (element) { // hightlight error inputs
            jQuery(element)
                .closest('.form-group')
                    .css({'color':'red'})
                    .addClass('has-error')
                    .removeClass('has-success'); // set error class to the control group
        },
        unhighlight: function (element) { // revert the change done by hightlight
            jQuery(element)
                .closest('.form-group')
                    .css({'color':''})
                    .removeClass('has-error'); // set error class to the control group
        },
        success: function (label) {
            label
            .closest('.form-group').removeClass('has-error'); // set success class to the control group
        }
    };

    jQuery('.autoFillOff').attr('readonly', true);
    setTimeout(function(){
        jQuery('.autoFillOff').attr('readonly', false)
    }, 1000);
    jQuery(document).ready(function(){
        if(jQuery.validator)
            jQuery.validator.setDefaults(formValidationOptions);
    });
    </script>
    @yield('scripts')
</body>
</html>
