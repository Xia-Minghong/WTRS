@include('header')

    <div id="wrapper">

    @include('admin/nav')
  <div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Timetables</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
    @if($teacher_name)
        @include('admin/reports/timetable_individual')
    @else
        @include('admin/reports/timetable_all')
    @endif
</div>
</div>
@include('footer')