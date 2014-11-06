@include('header')

    <div id="wrapper">

    @include('admin/nav')

  <div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Generate Relief Timetables <small>For
          @if(date( "w")!=0 && date( "w")!=6) Today @else Next Monday @endif
          </small></h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  @if($errors->has() && $errors->get('success'))
        <div class="alert alert-success" role="alert">
        <p>Relief Table Updated</p>
        </div>
  @endif
  @if($errors->has() && $errors->get('final'))
          <div class="alert alert-danger" role="alert">
          <p>This page will only be displayed once</p>
          </div>
    @endif
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Filter" ng-model="query">
        </div>
    </div>
</div>
<div class="row">
@if($errors->has() && $errors->get('final'))
    <div class="col-md-9">
          <a class="btn btn-danger" href="{{ URL::to('admin/actions/generaterelief/confirm')}}">Confirm & Send Email</a><br>
    </div>
@endif
</div>
<div class="row">
<div class="col-md-12 col-lg-10 table-responsive">
<p>Number of filtered Reliefs: {% filteredItems.length %}</p>

{{ Form::open(array('name'=>'generateRelief','class'=>'generateReliefForm','role'=>'form', 'action'=>'ReliefController@store')) }}
<table class="table table-striped table-bordered table-hover" ng-init="records = {{ htmlspecialchars(json_encode($results)); }}" ng-hide="!records.length">
  <thead>
    <tr>
        <th>Record ID</th>
        <th>MC ID</th>
        <th>Name</th>
        <th>Class</th>
        <th>Time</th>
        <th>Date</th>
        <th>Relief Teacher<br/>Group | Name | Score</th>
    </tr>
  </thead>

<tbody>
    <tr ng-repeat="record in filteredItems = (records | filter:query | orderBy:'date':'false')">
        <td >{% record.record_id %}</td>
        <td >{% record.mc_id %}</td>
        <td >{% record.full_name %}</td>
        <td >{% record.slot_value %}</td>
        <td >{% record.slot_time %}</td>
        <td >{% record.date %}</td>
        <td >
        @if($errors->has() && $errors->get('final'))
        {% record.relief_short_name %}
        @else
        <div class="form-inline" >
          <div ng-show="record.relief_short_name">{% record.relief_short_name %}</div>
          <input type="text" class="form-control" name="{% record.record_id %}" ng-hide="record.relief_teacher.length" placeholder="Name in Timetable">
          <select class="form-control" name="{% record.record_id %}" ng-hide="!record.relief_teacher.length || record.confirmed==1">
              <option value="{% relief_teacher.short_name %}" ng-repeat="relief_teacher in record.relief_teacher">
              Grp {% relief_teacher.grouping %} | {% relief_teacher.full_name %} | {% relief_teacher.mc_score %}
              </option>
          </select>
        </div>
        @endif
        </td>
    </tr>

</tbody>
</table>



<div class="row" ng-hide="!records.length || records[records.length-1].confirmed==1">


@if(!($errors->has() && $errors->get('final')))
    <div class="checkbox col-md-9 text-danger">
          <label>{{ Form::checkbox('final', 'yes', true) }} <strong>Final Submission<small>
          <br>*Add 1 MC score to each relief teacher.
          <br>*Emails will be sent to the relief teachers
          <br>*The relief records will be purged hence you cannot edit again</small></strong></label>
    </div>
    <div class="col-md-9">
          {{ Form::submit('Relief', array('class'=>'btn btn-success')) }}
    </div>
@endif
</div>
<div class="row" ng-show="!records.length">
<div class="alert alert-success">No relief to be assigned.</div>
</div>
{{ FORM::close() }}

</div>
</div>



</div>
</div>
@include('footer')