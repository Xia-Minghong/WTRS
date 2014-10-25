@include('header')

    <div id="wrapper">

    @include('admin/nav')
  <div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Teachers' Particulars</h1>
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
          <select class="form-control" ng-model="predicate" ng-init="predicate='full_name'">
              <option value="full_name">Full Name</option>
              <option value="grouping">Grouping</option>
          </select>
          <label>Reversed:</label>
          <select class="form-control" ng-model="reverse" ng-init="reverse='false'">
              <option value="false">False</option>
              <option value="true">True</option>
          </select>
        </div>
    </div>
</div>
<div class="row">
<div class="col-md-12 col-lg-10 table-responsive">

<p>Number of filtered teachers: {% filteredItems.length %}</p>

<table class="table table-striped table-bordered table-hover" ng-init="particulars = {{ htmlspecialchars(json_encode($results)); }}" ng-show="particulars.length">
  <thead>
    <tr>
        <th>NRIC</th>
        <th>Title</th>
        <th>Full Name</th>
        <th>Timetable Name</th>
        <th>Designation</th>
        <th>Grouping</th>
        <th>Email Address</th>
        {{--<th>Contact Nos</th>--}}
    </tr>
  </thead>

<tbody>
{{--@if(!$teacherid)--}}
{{--@foreach($results as $result)--}}
    {{--<tr>--}}
    {{--@for($i=1;$i<=13;$i++)--}}
        {{--<td>{{ $result->get($i) }}</td>--}}
    {{--@endfor--}}
    {{--</tr>--}}
{{--@endforeach--}}
{{--@else--}}
    {{--<tr>{{ $teacherid }}</tr>--}}
{{--@endif--}}


    <tr ng-repeat="particular in filteredItems = (particulars | filter:query | orderBy:predicate:reverse)">
        {{--@for($i=1;$i<=12;$i++)--}}
        {{--<td >{% particular.{{ $i }} %}</td>--}}
        {{--@endfor--}}
        <td>{% particular.nric %}</td>
        <td>{% particular.title %}</td>
        <td>{% particular.full_name %}</td>
        <td><a href="/admin/reports/timetable/{% particular.short_name %}">{% particular.short_name %}</a></td>
        <td>{% particular.designation %}</td>
        <td>{% particular.grouping %}</td>
        <td>{% particular.email_address %}</td>
        {{--<td>{% particular.contact_nos %}</td>--}}
    </tr>
</tbody>
</table>
<div class="row">
<div class="alert alert-danger" ng-hide="particulars.length">
No Teacher in database. <strong>Please <a href="/admin/actions/upload">upload teachers' particulars</a></strong>
</div>
</div>
</div>

</div>
</div>
</div>
</div>
@include('footer')