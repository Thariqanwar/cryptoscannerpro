@extends('layouts.admin')


@section('content')

 

<div class="content-wrapper ">

    <!-- Feeds List HTML Starts Here -->
    <div class=" dashboard">


        <div class="container-fluid">
            <div class="row"> 
                <div class="col-12">

                    <div class="admin-controller">
                        <h2> Signals Settings </h2>



                        <div class="row">

                        <div class="col-12 mt-3">
 <table class="signals-table">
      <thead>
        
        <tr>
          <th class="column1" data-column="column1">Name</th>
          @foreach($time as $names)
          <th>{{$names->short}}</th>
          @endforeach
        </tr>
       
      <thead>
      <tbody>
        @foreach($signal as $signals)
        <tr id="{{$signals->id}}">
          <td>{{$signals->name}}</td>
          @foreach($time as $times)
          @php
          $check="";
          if(isset($settings))
          {

          foreach ($settings as $key => $set) {
             if($set->time_frame==$times->id && $set->signal_id==$signals->id)
             {
                $check="checked";
             }
           }

          } 
          @endphp
          <td class="column2" data-column="column2">
                            <div class="form-check onoffswitch options">
                                    <input class="onoffswitch-checkbox" type="checkbox" id="{{$signals->id}}','{{$times->id}}"
                                        name="time_frame01" value="{{$signals->id}},{{$times->id}}" required data-toggle="toggle" {{(isset($check) ? $check : '') }}>
                                    <label class="form-check-label onoffswitch-label" for="{{$signals->id}}','{{$times->id}}">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
          </td>
         
          @endforeach

        </tr>
       
       
       
         @endforeach
      </tbody>
    </table>
  

                        </div>





                 

                        </div> 
                    </div>






                    {{-- <button type="button" id="1" class="apply-button button primary-button mt-4 ">Update</button> --}}



                </div>
            </div>

        </div>
    </div>
    <!-- Feeds List HTML Ends Here -->

    <br><br><br><br><br><br>

</div>
 

@endsection
@section('scripts')


<script>
$(document).ready(function() {

    var requiredCheckboxes = $('.options :checkbox[required]');
    if (requiredCheckboxes.is(':checked')) {
        requiredCheckboxes.removeAttr('required');
        $('.time-req').html('');
    } else {

        requiredCheckboxes.attr('required', 'required');
        $('.time-req').html('Select a time frame');
    }
    requiredCheckboxes.change(function() {
        if (requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
            $('.time-req').html('');
        } else {

            requiredCheckboxes.attr('required', 'required');
            $('.time-req').html('Select a time frame');
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#generate').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{route('GeneratePassword')}}",
            
            type:'post',
            success: function(password) {
                $('#pwd').val(password);
            }
        });
    });
    // AJAX request
    $('#subscription_period').change(function(e) {
        var data = $('form').serialize();
        e.preventDefault();
        $.ajax({
            url: "{{route('GetTimeFrames')}}",
            type: 'post',
            data: data,
            success: function(timeframes) {
                $("input[name='time_frame']").each(function(e) {
                    if ($(this).val() == 1) {
                        $(this).attr("checked", "checked");
                    }
                });
                console.log(timeframes);
            }
        });
    });

});
</script>

<script type="text/javascript">
    $(".onoffswitch-checkbox").change(function(){
        var data=$(this).val();
    if($(this).prop("checked") == true){
      
       $.ajax({
            url: "{{route('OnSignalSettings')}}",
            type: 'post',
            data:{'data':data},
            dataType:'json',
            success: function(password) {
               
            }
        });
       
    }else{
       //var data=$(this).val();
       
        $.ajax({
            url: "{{route('OffSignalSettings')}}",
            type: 'post',
            data:{'data':data},
            dataType:'json',
            success: function(password) {
               
            }
        });
        console.log("false");
       //run code
    }
});
</script>

<script>
    $('td').on('mouseover',function(){
        var table1 = $(this).parent().parent().parent();
        var table2 = $(this).parent().parent();
        var verTable = $(table1).data('vertable')+"";
        var column = $(this).data('column') + ""; 

        $(table2).find("."+column).addClass('hov-column');
        $(table1).find(".row100.head ."+column).addClass('hov-column-head');
    });
    $('td').on('mouseout',function(){
        var table1 = $(this).parent().parent().parent();
        var table2 = $(this).parent().parent();
        var verTable = $(table1).data('vertable')+"";
        var column = $(this).data('column') + ""; 

        $(table2).find("."+column).removeClass('hov-column');
        $(table1).find(".row100.head ."+column).removeClass('hov-column-head');
    });
</script>



@endsection