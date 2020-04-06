@extends('layouts.admin')


@section('content')
<div class="content-wrapper height-100">
    <!-- Feeds List HTML Starts Here -->
    @if(session('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
    @endif
    <div class=" dashboard">
      <div class="container-fluid">
        <div class="row">
          <h2>Subscription Amount </h2>
          <hr>
          <div class="col-12">
            <div class="clearfix">
            </div>
          </div>
          <form method="POST" action="{{ route('AddSubscriptionAmount') }}">
            @csrf
            <div class="col-12">

              @foreach($user_category as $key => $each_cat)
                  <div class="admin-controller @if($key != 0) mt-3 @endif">
                    <h2> {{$each_cat->category_type}}</h2>
                    <div class="row">
                      @foreach($each_cat->amount as $each)
                        <div class="col-md-3 mt-3">
                          <label class="control-label">{{$each->plan_period->text}}</label>
                          <input type="text" value="{{$each->amount}}" class="form-control" placeholder="Amount" name="price[]">
                        </div>      
                      @endforeach
                    </div>
                  </div> 
              @endforeach 
              <button type="submit" id="1" class="apply-button button primary-button mt-4 ">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>     
    <!-- Feeds List HTML Ends Here -->
    <br><br><br><br><br><br>
</div>

  

  
  <!-- Custom Script -->
  <script src="{{asset('admin/js/multi-select.js')}}"></script> 
  <script>
    /////////////////// product +/-
    $(document).ready(function () {
      $('.num-in span').click(function () {
        var $input = $(this).parents('.num-block').find('input.in-num');
        if ($(this).hasClass('minus')) {
          var count = parseFloat($input.val()) - 1;
          count = count < 1 ? 0 : count;
          if (count < 2) {
            $(this).addClass('dis');
          }
          else {
            $(this).removeClass('dis');
          }
          $input.val(count);
        }
        else {
          var count = parseFloat($input.val()) + 1
          $input.val(count);
          if (count > 1) {
            $(this).parents('.num-block').find(('.minus')).removeClass('dis');
          }
        }

        $input.change();
        return false;
      });

      $('#freeuser_telegram').on('change', function() {
        console.log($(this).val());
      });

    }); 
  </script>   

@endsection


