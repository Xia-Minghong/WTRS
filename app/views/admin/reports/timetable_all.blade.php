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
<table class="table table-striped table-bordered table-hover" ng-init="teachers = {{ htmlspecialchars(json_encode($results)); }}">
  <thead>
    <tr>
        <th colspan="4">Teachers with out timetables</th>
    </tr>
    <tr>
        <th>
            Full Name
        </th>
        <th>
            Name in Timetable
        </th>
        <th>
            Grouping
        </th>
        <th>
            Action
        </th>
        {{--<th>--}}
            {{--Day--}}
        {{--</th>--}}
     {{--for( $i = 1; $i <= 13; $i++)--}}
        {{--<th>--}}
            {{--Slot_{{ $i }}--}}
        {{--</th>--}}
     {{--endfor--}}
    {{--</tr>--}}


  </thead>

<tbody>


<tr ng-repeat="teacher in filteredItems = ( teachers | filter:query | orderBy:predicate:reverse)">
    {{--<td><a href="/admin/reports/timetable/{% timetable.short_name %}">{% timetable.short_name %}</a></td>--}}
    {{--<td>{% timetable.day %}</td>--}}
    {{--for( $i = 1; $i <= 13; $i++)--}}
        {{--<td>--}}
            {{--{% timetable.slot_{{ $i }} %}--}}
        {{--</td>--}}
     {{--endfor--}}
     <td>{% teacher.full_name %}</td>
     <td>{% teacher.short_name %}</td>
     <td>{% teacher.grouping %}</td>
     <td>
        <a class="btn btn-info btn-block" href="{{ URL::to('admin/actions/createtimetable')}}/{% teacher.short_name %}">Assign Timetable</a>
     </td>
</tr>

</tbody>
</table>
</div>
</div>