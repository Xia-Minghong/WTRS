@include('header')
    <div id="wrapper">

    @include('admin/nav')
  <div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Change Administrator Password</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel @if($errors->has('success') && $errors->get('success')) panel-success @else panel-default @endif">
                <div class="panel-heading">
                    <h3 class="panel-title">New Password</h3>
                </div>
                <div class="panel-body">
                @if($errors->has('success') && $errors->get('success'))
                <div class="text-success">Password Changed Successfully</div>
                @else
                {{ Form::open(array('name'=>'ChangePassForm','class'=>'ChangePassForm',
                'role'=>'form', 'action'=>'AdminController@storepwd')) }}
                        <fieldset>
                        <div class="controls form-inline">
                            <div class="form-group">
                                 <div class="form-group">
                                 {{ Form::password('newpassword',array('class'=>'form-control input-sm', 'placeholder'=>'New Password')) }}
                               </div>
                               <div class="form-group">
                                   {{ Form::password('confirmnewpassword',array('class'=>'form-control input-sm', 'placeholder'=>'Confirm Password')) }}
                                 </div>
                            </div>
                            <div class="form-group pull-right">
                            {{ Form::submit('Change', array('class'=>'btn btn-info btn-block')) }}
                            </div>
                        </div>
                        </fieldset>
                {{ FORM::close() }}
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@include('footer')