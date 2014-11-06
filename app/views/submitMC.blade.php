@include('header')
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
@include('nav_header')
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="login-panel panel @if($errors->has() && $errors->get('success')) panel-success @else panel-default @endif">
                <div class="panel-heading">
                    <h3 class="panel-title">MC Submission</h3>
                </div>

                <div class="panel-body">
                @if($errors->has() && $errors->get('success'))
                    <div class="text-success">Your MC has been successfully submitted<p><a href="{{ URL::to('signout')}}">Sign Out</a></p></div>
                @else
                {{ Form::open(array('name'=>'MCsubmission','class'=>'MCForm','role'=>'form', 'action'=>'MCController@store')) }}
                        <fieldset>
                        <div class="form-group">
                        <label>Name:&nbsp;</label>{{ Auth::teacher()->get()->short_name }}
                        </div>
                        <div class="controls form-inline">
                            <div class="form-group">
                                {{--<input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>--}}
                                <input name="fromdate" class="form-control datepicker" data-provide="datepicker" placeholder="From Date">
                            </div>
                            <label class="">&nbsp;to&nbsp;</label>
                            <div class="form-group">
                                <input name="todate" class="form-control datepicker" data-provide="datepicker" placeholder="To Date">
                            </div>
                        </div>
                        <br/>
                        <label>Type of Leave:</label>
                            <div class="form-group">
                                <select class="form-control" name="type">
                                    <option value="1">Medical Leave</option>
                                      <option value="2">On Course Leave</option>
                                      <option value="3">Personal Leave</option>
                                </select>
                            </div>
                        <div class="text-danger"><label>Remarks:&nbsp;</label>*Please contact the admin office if you have other requirements.</div>
                        <div class="text-info">*Weekends will be automatically left out.</div>
    <br/>

                            {{ Form::submit('Submit', array('class'=>'btn btn-success btn-block')) }}
                        </fieldset>
                {{ FORM::close() }}
                @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer')