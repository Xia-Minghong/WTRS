@include('header')
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
@include('nav_header')
</nav>
<div class="container">
    <div class="col-xs-6 col-sm-6 col-md-12">
    <div class="row" style="margin-top: 50px">
        <div class="row centered-form">
          <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Please Login</h3>
              </div>
              <div class="panel-body">
                {{ Form::open(array('name'=>'signin','class'=>'signinForm','role'=>'form', 'action'=>'TeacherController@signinVerify')) }}

                  <div class="form-group">
                    {{ Form::text('nric',null,array('class'=>'form-control input-sm', 'placeholder'=>'NRIC')) }}
                    {{--<input type="email" name="email" class="form-control input-sm" placeholder="Email Address">--}}
                  </div>

                  @if($errors->has('message'))
                    <div class="text-danger form-group">{{ $errors->first('message') }}</div>
                  @endif

                  {{--<div class="checkbox">--}}
                    {{--<label>--}}
                      {{--<input name="remember" type="checkbox" value="Remember Me"> Remember Me--}}
                      {{--<a href="/forgot" class="pull-right">Forgot Password?</a>--}}
                    {{--</label>--}}
                  {{--</div>--}}

                  {{ Form::submit('Continue', array('class'=>'btn btn-info btn-block')) }}
                  {{--<input type="submit" value="Login" class="btn btn-info btn-block">--}}

                {{ FORM::close() }}
              </div>
            </div>
            {{--<div class="text-center">--}}
              {{--<a href="/register" >Don't have an account? Register</a>--}}
            {{--</div>--}}
          </div>
        </div>
    </div>
    </div>
</div>
@include('footer')
