@include('header')
    <div id="wrapper">

    @include('admin/nav')
  <div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Create Timetable for <small>{{ $teacher_name }}</small></h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-danger" role="alert">
      <strong>The format of class:</strong>
      <p><ul>
          <li><strong>Class@Venue</strong>&nbsp;For example, <strong>FAM_T@3H </strong> means <strong>FAM_T</strong> class at venue <strong>3H</strong></li>
          <li><strong>You will not be able to edit this timetable again once submitted</strong></li>
      </ul></p>
      </div>
      </div>
    </div>
  <!-- /.row -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Timetable</h3>
                </div>
                <div class="panel-body">
                 @if($errors->has('success') && $errors->get('success')==true)
                    <div class="text-success">Your MC has been successfully submitted<p><a href="{{ URL::to('admin/actions/createMC')}}">Enter Another</a></p></div>
                 @else
                    <div class="text-danger"><p>
                    @if(Session::has('message'))
                        {{ Session::get('message') }}
                    @endif
                    </p></div>
                {{ Form::open(array('name'=>'createtimetable','class'=>'TimetableForm','role'=>'form', 'action'=>array('TimeTableController@create',$teacher_name))) }}
                        <fieldset>
                          <table class="table table-bordered table-hover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                              </tr>
                            </thead>
                            <tbody>
                              {{--print timetable forms--}}
                              @for($i=0;$i<=21600;$i+=1800)
                              <tr>
                                <td>
                                @if($i<=18000)
                                {{ date('H:i', mktime(7, 30, 0, 1, 1) + $i),'-', date('H:i', mktime(7, 30, 0, 1, 1) + $i + 1800); }}
                                @else
                                {{ date('H:i', mktime(7, 30, 0, 1, 1) + $i),'-', date('H:i', mktime(7, 30, 0, 1, 1) + $i + 1500); }}
                                <?php $i-=300; ?>
                                @endif
                                </td>
                                    @for($j=1;$j<=5;$j++)
                                    <td>
                                    {{ Form::text($j.'-slot_'.ceil($i/1800+1),null,array('class'=>'form-control input-sm', 'placeholder'=>'Class@Venue')) }}
                                    </td>
                                    @endfor

                              </tr>
                              @endfor
                            </tbody>
                          </table>
                            {{ Form::submit('Submit', array('class'=>'btn btn-success btn-block')) }}
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