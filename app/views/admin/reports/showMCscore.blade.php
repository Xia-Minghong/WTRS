@include('header')

    <div id="wrapper">
    @include('admin/nav')

  <div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">MC Scores</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Filter" ng-model="query">
        </div>
    </div>
    <div class="col-sm-5">
        <div class="form-inline">
          <label>Sort:</label>
          <select class="form-control" ng-model="predicate" ng-init="predicate='mc_score'">
              <option value="mc_score">MC Score</option>
              <option value="short_name">Name</option>
              <option value="designation">Designation</option>
              <option value="grouping">Grouping</option>
          </select>
          <label>Reversed:</label>
          <select class="form-control" ng-model="reverse" ng-init="reverse='false'">
              <option value="false">False</option>
              <option value="true">True</option>
          </select>
        </div>
    </div>
    <div class="col-sm-2">
        {{ Form::open(array('name'=>'editMC','class'=>'editMCForm','role'=>'form', 'action'=>'MCController@editScore')) }}
        <div class="form-group">
          {{ Form::submit('Reset All to 0', array('class'=>'btn btn-danger btn-block')) }}
          <input type="hidden" name="short_name" value=""/>
        </div>
        {{ FORM::close() }}
    </div>
</div>
<div class="row">
<div class="col-md-12 col-lg-10 table-responsive">
<p>Number of filtered teachers: {% filteredItems.length %}</p>

<table class="table table-striped table-bordered table-hover" ng-init="teachers = {{ htmlspecialchars(json_encode($results, JSON_NUMERIC_CHECK)); }}">
  <thead>
    <tr>
        <th>NRIC</th>
        <th>Name</th>
        <th>Designation</th>
        <th>Grouping</th>
        <th>MC Score</th>
        <th>Action</th>
    </tr>
  </thead>

<tbody>
    <tr ng-repeat="teacher in filteredItems = (teachers | filter:query | orderBy:predicate:reverse)">
        <td >{% teacher.nric %}</td>
        <td >{% teacher.full_name %}</td>
        <td >{% teacher.designation %}</td>
        <td >{% teacher.grouping %}</td>
        <td >{% teacher.mc_score %}</td>
        <td>
            <div class="row">
            {{ Form::open(array('name'=>'editMC','class'=>'editMCForm','role'=>'form', 'action'=>'MCController@editScore')) }}
            <div class="col-xs-7">
                {{ Form::text('mc_score',null,array('class'=>'form-control input-sm', 'placeholder'=>'Set MC Score')) }}
            </div>
            <div class="col-xs-4">
                {{ Form::submit('Set', array('class'=>'btn btn-info btn-block')) }}
                <input type="hidden" name="short_name" value="{% teacher.short_name %}"/>
            </div>
            {{ FORM::close() }}
            </div>
        </td>
    </tr>
</tbody>
</table>


</div>
</div>
</div>
</div>
@include('footer')