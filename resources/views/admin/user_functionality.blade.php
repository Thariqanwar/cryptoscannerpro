@extends('layouts.admin')


@section('content')
	<div class="content-wrapper height-100">

	    	<!-- Feeds List HTML Starts Here -->
			<div class=" dashboard">

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="clearfix">
			</div>
		</div>
		<form method="POST" action="{{ route('AddFunctionalities') }}">
        @csrf
		<div class="col-12">
			<div class="admin-controller">
				<h2> Free Users</h2>
				<!-- <h3>
					Free Users
				</h3> -->
				<div class="row">
					<div class="col-md-3 mt-3">
						<label class="control-label">Telegram Channels</label>
						<!-- <input type="text" id="freevalues" class="lorem"> -->
						<select id="freeuser_telegram" name="freeuser_telegram[]" multiple="multiple">
						@foreach($data as $keys => $values)
							<optgroup label="{{$keys}}">
								@foreach($values as $key => $value )
								<option value="{{$key}}" <?= (($free['selected']) ? in_array($key,$free['selected']) : '')? "selected":"" ?> >{{$value}}</option>
								@endforeach
							</optgroup>
						@endforeach	
						</select>

							
					</div>

					<div class="col-md-3 mt-3">
						<label class="control-label">Feeds</label>
						<select id="freeuser_feed" name="freeuser_feed[]" multiple="multiple">
						@foreach($feedCategory as $value)
								<option value="{{$value->id}}" <?= (($free['feed_category']) ? in_array($value->id,$free['feed_category']) : '')? "selected":"" ?> >{{$value->category_name}}</option>
						@endforeach
						</select>
					</div>
					<div class="col-md-6  ">

						<div class="row">
							<div class="col-md-4 mt-3">
								<label class="control-label">Delay in Minutes</label>
								<div class="num-block skin-2">
									<div class="num-in">
										<span class="minus dis"></span>
										<input type="text" name="freeuser_delay" id="freeuser_delay" class="in-num" value="{{$free['delay']}}" readonly="">
										<span class="plus"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4 mt-3">

								<label class="control-label">Crypto Scanner PRO</label>
								<div class="clearfix"></div>
								<div class="custom-control custom-checkbox custom-control-inline ">
									<input type="checkbox" value="1" name="freeuser_scannerpro" <?= ($free['crypto_scanner'])? 'checked':'' ?>
										class="custom-control-input feed_type" id="freeuser_scannerpro"  >
									<label class="custom-control-label" for="freeuser_scannerpro">Active </label>
								</div>
							</div>
							<div class="col-md-4 mt-3">
								<label class="control-label">Smart Trade</label>
								<div class="clearfix"></div>
								<div class="custom-control custom-checkbox custom-control-inline ">
									<input type="checkbox" value="1" name="freeuser_smart_trade" <?= ($free['smart_trade'])? 'checked':'' ?>
										class="custom-control-input feed_type" id="freeuser_smart_trade">
									<label class="custom-control-label" for="freeuser_smart_trade">Active
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="admin-controller mt-3">
				<h2> Basic Users </h2>
				<div class="row">
					<div class="col-md-3 mt-3">
						<label class="control-label">Telegram Channels</label>
						<select id="basic_telegram" name="basic_telegram[]" multiple="multiple">
						@foreach($data as $keys => $values)
							<optgroup label="{{$keys}}">
								@foreach($values as $key => $value )
								<option value="{{$key}}" <?= (($basic['selected']) ? in_array($key,$basic['selected']) :'')? "selected":"" ?> >{{$value}}</option>
								@endforeach
							</optgroup>
						@endforeach	
						</select>
					</div>
					<div class="col-md-3 mt-3">
						<label class="control-label">Feeds</label>
						<select id="basic_feed" name="basic_feed[]" multiple="multiple">
						@foreach($feedCategory as $value)
								<option value="{{$value->id}}" <?= (($basic['feed_category']) ? in_array($value->id,$basic['feed_category']):'')? "selected":"" ?> >{{$value->category_name}}</option>
						@endforeach
						</select>
					</div>
					<div class="col-md-6  ">

						<div class="row">
							<div class="col-md-4 mt-3">
								
								<label class="control-label">Delay in Minutes</label>
								<div class="num-block skin-2">
									<div class="num-in">
										<span class="minus dis"></span>
										<input type="text" id="basic_delay" name="basic_delay" class="in-num" value="{{$basic['delay']}}" readonly="">
										<span class="plus"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4 mt-3">

								<label class="control-label">Crypto Scanner PRO</label>
								<div class="clearfix"></div>
								<div class="custom-control custom-checkbox custom-control-inline ">
									<input type="checkbox" value="1" name="basic_scannerpro" <?= ($basic['crypto_scanner'])? 'checked':'' ?>
										class="custom-control-input feed_type" id="basic_scannerpro">
									<label class="custom-control-label" for="basic_scannerpro">Active </label>
								</div>
							</div>
							<div class="col-md-4 mt-3">
								<label class="control-label">Smart Trade</label>
								<div class="clearfix"></div>
								<div class="custom-control custom-checkbox custom-control-inline ">
									<input type="checkbox" value="1" name="basic_smart_trade" <?= ($basic['smart_trade'])? 'checked':'' ?>
										class="custom-control-input feed_type" id="basic_smart_trade">
									<label class="custom-control-label" for="basic_smart_trade">Active
									</label>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="admin-controller mt-3">
				<h2> Advanced Users </h2>

				<!-- <h3>
					Free Users
				</h3> -->

				<div class="row">

					
					<div class="col-md-3 mt-3">
						<label class="control-label">Telegram Channels</label>
						<select id="advanced_telegram" name="advanced_telegram[]" multiple="multiple">
						@foreach($data as $keys => $values)
							<optgroup label="{{$keys}}">
								@foreach($values as $key => $value )
								<option value="{{$key}}" <?= (($ad['selected']) ? in_array($key,$ad['selected']) : '')? "selected":"" ?> >{{$value}}</option>
								@endforeach
							</optgroup>
						@endforeach	
						</select>
					</div>
					<div class="col-md-3 mt-3">
						<label class="control-label">Feeds</label>
						<select id="advanced_feed" name="advanced_feed[]" multiple="multiple">
						@foreach($feedCategory as $value)
								<option value="{{$value->id}}" <?= (($ad['feed_category']) ? in_array($value->id,$ad['feed_category']) : '')? "selected":"" ?> >{{$value->category_name}}</option>
						@endforeach
						 
						</select>
					</div>

					<div class="col-md-6  ">

						<div class="row">
							<div class="col-md-4 mt-3">
								
								<label class="control-label">Delay in Minutes</label>
								<div class="num-block skin-2">
									<div class="num-in">
										<span class="minus dis"></span>
										<input type="text" id="advanced_delay" name="advanced_delay" class="in-num" value="{{$ad['delay']}}" readonly="">
										<span class="plus"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4 mt-3">

								<label class="control-label">Crypto Scanner PRO</label>
								<div class="clearfix"></div>
								<div class="custom-control custom-checkbox custom-control-inline ">
									<input type="checkbox" value="1" name="advanced_scannerpro" <?= ($ad['crypto_scanner'])? 'checked':'' ?>
										class="custom-control-input feed_type" id="advanced_scannerpro">
									<label class="custom-control-label" for="advanced_scannerpro">Active </label>
								</div>
							</div>
							<div class="col-md-4 mt-3">
								<label class="control-label">Smart Trade</label>
								<div class="clearfix"></div>
								<div class="custom-control custom-checkbox custom-control-inline ">
									<input type="checkbox" value="1" name="advanced_smart_trade" <?= ($ad['smart_trade'])? 'checked':'' ?>
										class="custom-control-input feed_type" id="advanced_smart_trade">
									<label class="custom-control-label" for="advanced_smart_trade">Active
									</label>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="admin-controller mt-3">
				<h2> Professional Users </h2>

				<!-- <h3>
					Free Users
				</h3> -->
				<div class="row">
					<div class="col-md-3 mt-3">
						<label class="control-label">Telegram Channels</label>
						<select id="pro_telegram" name="pro_telegram[]" multiple="multiple">
						@foreach($data as $keys => $values)
							<optgroup label="{{$keys}}">
								@foreach($values as $key => $value )
								<option value="{{$key}}" <?= (($pro['selected']) ? in_array($key,$pro['selected']) : '')? "selected":"" ?> >{{$value}}</option>
								@endforeach
							</optgroup>
						@endforeach	
						</select>
					</div>
					<div class="col-md-3 mt-3">
						<label class="control-label">Feeds</label>
						<select id="pro_feed" name="pro_feed[]" multiple="multiple">
						@foreach($feedCategory as $value)
								<option value="{{$value->id}}" <?= (($pro['feed_category']) ? in_array($value->id,$pro['feed_category']): '')? "selected":"" ?> >{{$value->category_name}}</option>
						@endforeach
						</select>
					</div>

					<div class="col-md-6  ">

						<div class="row">
							<div class="col-md-4 mt-3">
								
								<label class="control-label">Delay in Minutes</label>
								<div class="num-block skin-2">
									<div class="num-in">
										<span class="minus dis"></span>
										<input type="text" id="pro_delay" name="pro_delay" class="in-num" value="{{$pro['delay']}}" readonly="">
										<span class="plus"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4 mt-3">

								<label class="control-label">Crypto Scanner PRO</label>
								<div class="clearfix"></div>
								<div class="custom-control custom-checkbox custom-control-inline ">
									<input type="checkbox" value="1" name="pro_scannerpro" <?= ($pro['crypto_scanner'])? 'checked':'' ?>
										class="custom-control-input feed_type" id="pro_scannerpro">
									<label class="custom-control-label" for="pro_scannerpro">Active </label>
								</div>
							</div>
							<div class="col-md-4 mt-3">
								<label class="control-label">Smart Trade</label>
								<div class="clearfix"></div>
								<div class="custom-control custom-checkbox custom-control-inline ">
									<input type="checkbox" value="1" name="pro_smart_trade" <?= ($pro['smart_trade'])? 'checked':'' ?>
										class="custom-control-input feed_type" id="pro_smart_trade">
									<label class="custom-control-label" for="pro_smart_trade">Active
									</label>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
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


