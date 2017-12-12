@extends('base')

@section('browsertitle')
    Acme: login
@stop


@section('content')
<div class="row">
  <div class="col-md-2">

  </div>

  <div class="col-md-8">

    <h1>Log In</h1>

    <hr>

    <form name="loginform" id="loginform" action="/login" method="post" class="form-horizontal">
        <input type="hidden" name="_token" value="{!! htmlspecialchars($signer->getSignature()) !!}">
      <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
          <input type="email" class="form-control email required" value="" id="email" name="email" placeholder="user@example.com">
        </div>
      </div>
      <div class="form-group">
        <label for="password" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control required" name="password" id="password" placeholder="Password">
        </div>
      </div>

      <hr>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Sign in</button>
        </div>
      </div>
    </form>

  </div>

  <div class="col-md-2">

  </div>
</div>
@stop

@section('bottomjs')
<script>
$(document).ready(function(){
    $("#loginform").validate();
});
</script>
@stop
