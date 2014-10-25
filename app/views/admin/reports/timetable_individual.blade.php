<div class="row">
<div class="col-md-12 col-lg-10 table-responsive">
@if($errors->has() && $errors->get('success'))
      <div class="alert alert-success" role="alert">
      Timetable successfully created<p><a href="/admin/reports/timetable">Add More</a></p>
      </div>
@endif
<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Timetime of <strong>{{ $results[0]['short_name'] }}</strong></h3>
                </div>
                <div class="panel-body">
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
        {{ $results[$j-1]['slot_'.ceil($i/1800+1)] }}
        </td>
        @endfor

  </tr>
  @endfor
</tbody>
</table>
</div>
</div>
</div>