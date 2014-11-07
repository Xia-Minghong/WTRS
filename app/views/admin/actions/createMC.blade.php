@include('header')
    <div id="wrapper">

    @include('admin/nav')
  <div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Create New MC</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">MC Submission</h3>
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
                    <form name="MCsubmission" class="AdminMCForm" role="form" action="{{URL::to('/')}}/" method="post">
                        <fieldset>
                        <div class="form-group">
                        <input class="form-control" placeholder="NRIC" name="nric" type="username" autofocus>
                        </div>
                        <div class="controls form-inline">
                            <div class="form-group">
                                <input name="fromdate" class="form-control admindatepicker" data-provide="datepicker" placeholder="From Date">
                            </div>
                            <label class="">&nbsp;to&nbsp;</label>
                            <div class="form-group">
                                <input name="todate" class="form-control admindatepicker" data-provide="datepicker" placeholder="To Date">
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
                                <input type="hidden" name="isAdmin" value="true"/>
                            </div>
                            <div class="text-info">*Weekends will be automatically left out.</div>
                            <input type="submit" class="btn btn-success btn-block"/>
                        </fieldset>
                </form>
                @endif
                </div>

            </div>
        </div>
    </div>
</div>
</div>

@include('footer')