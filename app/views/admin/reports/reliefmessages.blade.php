 {{ HTML::style('css/bootstrap.min.css') }}
 <div class="row">
 <div class="col-lg-6">
 <button onclick="javascript:window.print()" class="btn btn-success">Print messages</button>
 <a href="{{ URL::to('admin')}}">Back to Admin Page</a><br>&nbsp;
 </div>
 </div>
 <?php $count=0;?>
 @foreach($results as $result)
 @if($count%2==0)<div class="row">@endif
 <div class="col-md-4">
 <table class="table" frame="box">
 <tr>
     <td>
     <p>Dear <b>{{ $result->relief_teacher_title }} {{ $result->relief_teacher_full_name }}</b>,</p>
     <p>You have been assigned to relief the following class today.</p>
     <table>
        <tr><th>Original teacher: </th><td>{{ $result->on_leave_teacher_title }} {{ $result->on_leave_teacher_full_name }}</td></tr>
        <tr><th>Class@Venue: </th><td>{{ $result->{'slot_'.strval($result->slot)} }}</td></tr>
        <tr><th>Time: </th><td>
        <?php
        $slot_str = array();
        for($i=0;$i<=21600;$i+=1800){
           if ($i<=18000) {
               //07:30-08:30 ... 12:30-13:30
               array_push($slot_str, date('H:i', mktime(7, 30, 0, 1, 1) + $i).'-'.date('H:i', mktime(7, 30, 0, 1, 1) + $i + 1800));
           } else {
               //special slots calculation of 13:00-13:25 and 13:25-13:50
               array_push($slot_str, date('H:i', mktime(7, 30, 0, 1, 1) + $i).'-'.date('H:i', mktime(7, 30, 0, 1, 1) + $i + 1500));
               $i-=300;
           }
        }
        echo $slot_str[$result->slot-1];
        ?>
        </td></tr>
     </table>
     </td>
 </tr>

</table>
</div>
 @if($count%2==1)</div>@endif
 <?php $count+=1;?>
@endforeach
