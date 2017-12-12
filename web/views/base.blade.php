<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('browsertitle')
    </title>

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/styles.css">

    @yield('css')

</head>

<body>

@include('partials.topnav')
@yield('oustidecontainer')

<div class="container">

    <div class="row push-down">
        <div class="col-md-12">
            <br><br>
            @include('partials.error-message')
            @include('partials.success-message')
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>

</div>

<footer class="footer">
    <div class="row footer-background">
        <div class="col-md-3 margin-left-5px">
            <div class="padding-left-8px">
                <h4>Contact Us</h4>
                123 Main St.<br>
                Unionville, PA<br>
                76543<br>
                +1 (555) 555-1212
            </div>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col-md-3">
            <img src="/assets/map-small.png" class="pull-right">
        </div>
    </div>
</footer>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
@if ((Acme\Auth\LoggedIn::user()) && (Acme\Auth\LoggedIn::user()->access_level == 2))
    <script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.4.5/ckeditor.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
@endif

@yield('bottomjs')
@include('admin.admin-js')

</body>

</html>
