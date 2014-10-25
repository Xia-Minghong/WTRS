@include('header')

    <div id="wrapper">

    @include('admin/nav')

  <div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">MC List</h1>
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
    <div class="col-lg-offset-9 col-md-offset-9 col-sm-offset-9">
          <a class="btn btn-success" href="/admin/actions/createMC">Add New</a>
    </div>
</div>

<div class="row">
<div class="col-md-12 col-lg-10 table-responsive">
<p>Number of filtered MCs: {% filteredItems.length %}</p>
<table class="table table-striped table-bordered table-hover" ng-init="MCs = {{ htmlspecialchars(json_encode($results)); }}" ng-show="MCs.length">
  <thead>
    <tr>
        <th>MC ID</th>
        <th>NRIC</th>
        <th>Name</th>
        <th>Designation</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
  </thead>

<tbody>
    <tr ng-repeat="MC in filteredItems = (MCs | filter:query | orderBy:'date':'true')">
        <td >{% MC.mc_id %}</td>
        <td >{% MC.nric %}</td>
        <td >{% MC.full_name %}</td>
        <td >{% MC.designation %}</td>
        <td >{% MC.date %}</td>
        <td>
            <div class="row">
            {{ Form::open(array('name'=>'editMCList','class'=>'editMCListForm','role'=>'form', 'action'=>'MCController@delete')) }}
            <div class="col-xs-4">
                <input type="hidden" name="mc_id" value="{% MC.mc_id %}"/>
                {{ Form::submit('Delete', array('class'=>'btn btn-danger')) }}
            </div>
            {{ FORM::close() }}
            </div>
        </td>
</tr>
</tbody>
</table>
<div class="row" ng-hide="MCs.length">
<div class="alert alert-success">
No MC.
</div>
</div>

</div>
</div>
</div>
</div>
@include('footer')