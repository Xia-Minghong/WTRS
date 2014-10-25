@include('header')
    <div id="wrapper">

    @include('admin/nav')
  <div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">System Inputs</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  <div class="row">
  <div class="col-lg-6">
    <div class="alert alert-danger" role="alert">
    <strong>Notes:</strong>
    <p><ol>
        <li>First time: Please upload teachers' particulars before the timetables</li>
        <li>Once two tables are up: You can upload either or both in any order</li>
    </ol></p>
    </div>
    </div>
  </div>
  <div class="row">
          <div class="col-md-6">
              <div class="panel @if($errors->has() && $errors->get('type')[0]=='particular') @if($errors->get('success')[0]) panel-success @else panel-danger @endif @else panel-default @endif">
                  <div class="panel-heading">
                      <h3 class="panel-title">Particulars Import</h3>
                  </div>
                  <div class="panel-body">
                  @if($errors->has() && $errors->get('type')[0]=='particular')
                      @if($errors->get('success')[0])
                        <div class="text-success">Particulars Imported</div>
                      @else
                        <div class="text-danger">{{ $errors->get('message')[0] }}<p><a href="/admin/actions/upload">Retry</a></p></div>
                      @endif
                  @else
                  {{ Form::open(array('name'=>'ParticularForm','class'=>'ParticularForm',
                  'role'=>'form', 'action'=>'ParticularController@store', 'files' => true)) }}
                          <fieldset>
                          <div class="controls form-inline">
                              <div class="form-group">
                                   <div class="input-group" >
                                       <span class="input-group-btn">
                                           <span class="btn btn-primary btn-file">
                                               Browse&hellip; <input type="file" name="particularfile">
                                           </span>
                                       </span>
                                       <input type="text" class="form-control" readonly>
                                   </div>
                              </div>
                              <div class="form-group pull-right">
                              {{ Form::submit('Upload', array('class'=>'btn btn-info btn-block')) }}
                              </div>
                          </div>
                          </fieldset>
                  {{ FORM::close() }}
                  @endif
                  </div>
              </div>
          </div>
      </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel @if($errors->has() && $errors->get('type')[0]=='timetable') @if($errors->get('success')[0]) panel-success @else panel-danger @endif @else panel-default @endif">
                <div class="panel-heading">
                    <h3 class="panel-title">Timetables Import</h3>
                </div>
                <div class="panel-body">
                @if($errors->has() && $errors->get('type')[0]=='timetable')
                    @if($errors->get('success')[0])
                      <div class="text-success">Timetables Imported</div>
                    @else
                      <div class="text-danger">{{ $errors->get('message')[0] }}<p><a href="/admin/actions/upload">Retry</a></p></div>
                    @endif
                @else
                {{ Form::open(array('name'=>'TimeTableForm','class'=>'TimeTableForm','role'=>'form'
                ,'action'=>'TimeTableController@store', 'files' => true)) }}
                        <fieldset>
                        <div class="controls form-inline">
                            <div class="form-group">
                                 <div class="input-group" >
                                     <span class="input-group-btn">
                                         <span class="btn btn-primary btn-file">
                                             Browse&hellip; <input type="file" name="timetablefile">
                                         </span>
                                     </span>
                                     <input type="text" class="form-control" readonly>
                                 </div>
                            </div>
                            <div class="form-group pull-right">
                            {{ Form::submit('Upload', array('class'=>'btn btn-info btn-block')) }}
                            </div>
                        </div>
                        </fieldset>
                {{ FORM::close() }}
                @endif
                <div class="row">
                <div class="col-md-8">
                <a href="/admin/reports/timetable">Check who doesn't have any timetable</a>
                </div>
                </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

@include('footer')