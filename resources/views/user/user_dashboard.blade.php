@extends('layouts.admin')


@section('content')
<div id="alertModal" class="modal " role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="alert modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <img width="100%" id="img01">
        <div id="caption"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="myModal" class="modal fade widgets-popup" role="dialog">
  <div class="modal-dialog modal-dialog-centered">

    <!-- Modal content-->
    <div class="modal-content">
  


   
      <div class="modal-body">
      <div class="heading">
                    <h3>Widgets</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg class="close-icon" viewBox="0 0 24 24">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                            <path d="M0 0h24v24H0z" fill="none"></path>
                        </svg>
                    </button>
                </div>

                <div class="content">
                <form method="POST" action="{{ route('addRemoveWidgets') }}">
            @csrf
        <div class="checkbox">
            @foreach($all_widgets as $key => $each)
                
                    
                        <label><input type="checkbox" @foreach($widgets as $one) @if($one->widget_id==$each->id) checked  @endif @endforeach name="widget_status[]" value="{{$each->id}}">{{$each->name}}</label><br>
                       
                  
            @endforeach
         
        </div>

        <div class="modal-footer"> 
        <button type="button" class="button ash-button" data-dismiss="modal">Close</button>
                <input type="submit" class="button primary-button mt-2" value="Save" name="">
                               
                            </div>

        </form>
                </div>
 

        
      </div>
      
    </div>

  </div>
</div>
<div class="content-wrapper height-100">
	<div class="container-fluid">
       
		{{--<div class="row justify-content-center align-items-md-center min-height-90">
			 <div class="col-md-12 text-center ">
				<a href="{{route('GetRsiSubscription')}}"><button class="btn btn-primary">RSI settings</button></a>
				<a href="{{route('GetSmaSubscription')}}"><button class="btn btn-primary">SMA settings</button></a>
 
			</div> 
		</div>--}}
		{{-- <div class="row">
			<div class="col-12 mt-4">
			<div class="top-strap">
            
            <span class="item">Workstation-1</span>
            <button type="button" class="button  plus-btn" title="Minimize Widget">
                    <svg xmlns="http://www.w3.org/2000/svg" width="31.5" height="31.5" viewBox="0 0 31.5 31.5">
                        <path id="Icon_awesome-plus" data-name="Icon awesome-plus" d="M29.25,14.625H19.125V4.5a2.25,2.25,0,0,0-2.25-2.25h-2.25a2.25,2.25,0,0,0-2.25,2.25V14.625H2.25A2.25,2.25,0,0,0,0,16.875v2.25a2.25,2.25,0,0,0,2.25,2.25H12.375V31.5a2.25,2.25,0,0,0,2.25,2.25h2.25a2.25,2.25,0,0,0,2.25-2.25V21.375H29.25a2.25,2.25,0,0,0,2.25-2.25v-2.25A2.25,2.25,0,0,0,29.25,14.625Z" transform="translate(0 -2.25)"/>
                      </svg>
                      
                                                      
                </button> 
        </div>
		 --}}
		
		
        <div class="grid-stack" id="simple-grid">
            @foreach($widgets as $key1 =>$value)

                @if($value->widget->code=='top_coin')
                    <div class="grid-stack-item" data-widget_id="{{$value->widget->id}}" data-gs-height="{{$value->height}}" data-gs-width="{{$value->width}}" data-gs-x="{{$value->x}}" data-gs-y="{{$value->y}}">

                        <div class="grid-stack__wrapper grid-stack-item-content ">
                            <div class="grid-stack__header draggable-heading">
                                <div class="left">
                                    <button type="button" class="button lock-button" title="Lock Widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 364.3 485.8">
                                            <g id="padlock" transform="translate(-59.25 1.5)">
                                                <g id="Group_1" data-name="Group 1">
                                                <path id="Path_4" data-name="Path 4" d="M395.95,210.4h-7.1V147.5C388.85,66.2,322.75,0,241.35,0c-81.3,0-147.5,66.1-147.5,147.5a13.5,13.5,0,0,0,27,0,120.5,120.5,0,0,1,241,0v62.9h-275a26.119,26.119,0,0,0-26.1,26.1V404.6a78.314,78.314,0,0,0,78.2,78.2h204.9a78.314,78.314,0,0,0,78.2-78.2V236.5A26.119,26.119,0,0,0,395.95,210.4Zm-.9,194.2a51.235,51.235,0,0,1-51.2,51.2H139.05a51.235,51.235,0,0,1-51.2-51.2V237.4h307.2V404.6Z" stroke="#000" stroke-width="3"/>
                                                <path id="Path_5" data-name="Path 5" d="M241.45,399.1a50.5,50.5,0,1,0-50.5-50.5A50.552,50.552,0,0,0,241.45,399.1Zm0-74.1a23.55,23.55,0,1,1-23.5,23.6A23.537,23.537,0,0,1,241.45,325Z" stroke="#000" stroke-width="3"/>
                                                </g>
                                            </g>
                                        </svg>                                                                   
                                    </button>
                                </div>
                                <div class="center">
                                    <h5>Top 10 Coin Rankings </h5>
                                </div>
                                <div class="right">
                                   
                                </div>                            
                            </div>
                            <div class="grid-stack__content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered draggable-table">
                                            <thead>
                                                <tr> 
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Symbol </th>
                                                    <th>Marketcap</th>
                                                    <th>Price USD</th>
                                                    <th>Price +/- 1H</th>
                                                    <th>Price +/- 7D</th>
                                                    <th>Price +/- 24H</th>
                                                    <th>volume 24H</th>
                                                    {{-- <th>volume +/- 24H</th> --}}
                                                    {{-- <th>Time</th> --}} 
                                                </tr>
                                            </thead>
                                            <tbody class="topcoins">
                                                @if(isset($topcoins))
                                              
                                                @foreach($topcoins as $key => $topcoin)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td><img height="15px" width="15px" class="ranking-icon" src="{{-- https://www.cryptocompare.com/{{$topcoin->CoinInfo->ImageUrl}} --}}">{{$topcoin->name}}</td>
                                                    <td>{{$topcoin->symbol}}</td>
                                                    <td>{{Auth::user()->price_convert($topcoin->quote->USD->market_cap)}}</td>
                                                    <td >{{round($topcoin->quote->USD->price,2)}}</td>
                                                    <td @if($topcoin->quote->USD->percent_change_1h < 0 ) style="color:#f04a38"  @else style="color:#58ca6a" @endif >@if($topcoin->quote->USD->percent_change_1h < 0 ) <i class="fas fa-caret-down"></i>  @else <i class="fas fa-caret-up"></i> @endif{{round($topcoin->quote->USD->percent_change_1h,2)}}%</td>
                                                    <td @if($topcoin->quote->USD->percent_change_7d < 0 ) style="color:#f04a38" @else style="color:#58ca6a" @endif >@if($topcoin->quote->USD->percent_change_1h < 0 ) <i class="fas fa-caret-down"></i>  @else <i class="fas fa-caret-up"></i> @endif{{round($topcoin->quote->USD->percent_change_7d,2)}}%</td>
                                                    <td @if($topcoin->quote->USD->percent_change_24h < 0 ) style="color:#f04a38" @else style="color:#58ca6a" @endif >@if($topcoin->quote->USD->percent_change_1h < 0 ) <i class="fas fa-caret-down"></i>  @else <i class="fas fa-caret-up"></i> @endif{{round($topcoin->quote->USD->percent_change_24h,2)}}%</td>
                                                    <td>{{Auth::user()->price_convert($topcoin->quote->USD->volume_24h)}}</td>
                                                    {{-- <td>{{$volchange[$topcoin->quote->USD->FROMSYMBOL]}}</td> --}}
                                                   {{--  <td>{{date('h:s A',$topcoin->quote->USD->LASTUPDATE)}}</td>   --}}
                                                </tr>
                                                @endforeach
                                                @endif    
                                                @if(isset($topcoins2))
                                                @foreach($topcoins2 as $key => $topcoin)
                                                
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td><img height="15px" width="15px" class="ranking-icon" src="{{-- https://www.cryptocompare.com/{{$topcoin->CoinInfo->ImageUrl}} --}}">{{$topcoin->CoinInfo->FullName}}</td>
                                                    <td>{{$topcoin->CoinInfo->Internal}}</td>
                                                    <td>{{$topcoin->RAW->USD->MKTCAP}}</td>
                                                    <td >{{$topcoin->DISPLAY->USD->PRICE}}</td>
                                                    <td @if($topcoin->DISPLAY->USD->CHANGEPCTHOUR < 0 ) style="color:#f04a38"  @else style="color:#58ca6a" @endif >@if($topcoin->DISPLAY->USD->CHANGEPCTHOUR < 0 ) <i class="fas fa-caret-down"></i>  @else <i class="fas fa-caret-up"></i> @endif{{$topcoin->DISPLAY->USD->CHANGEPCTHOUR}}%</td>
                                                    <td>null</td>
                                                   {{--  <td @if($topcoin->quote->USD->percent_change_7d < 0 ) style="color:#f04a38" @else style="color:#58ca6a" @endif >@if($topcoin-> < 0 ) <i class="fas fa-caret-down"></i>  @else <i class="fas fa-caret-up"></i> @endif{{$topcoin->USD->percent_change_1h}}%</td> --}}
                                                    <td @if($topcoin->DISPLAY->USD->CHANGEPCT24HOUR < 0 ) style="color:#f04a38" @else style="color:#58ca6a" @endif >@if($topcoin->DISPLAY->USD->CHANGEPCT24HOUR < 0 ) <i class="fas fa-caret-down"></i>  @else <i class="fas fa-caret-up"></i> @endif {{$topcoin->DISPLAY->USD->CHANGEPCT24HOUR}}%</td>
                                                    <td>{{$topcoin->RAW->USD->VOLUME24HOURTO}}</td>
                                                    <td></td>
                                                    <td></td>  
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                    </table>        
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($value->widget->code=='market_sentiment')
                    <div class="grid-stack-item" id="{{$value->widget->code}}" data-widget_id="{{$value->widget->id}}" data-gs-height="{{$value->height}}" data-gs-width="{{$value->width}}" data-gs-x="{{$value->x}}" data-gs-y="{{$value->y}}">
                        <div class="grid-stack__wrapper grid-stack-item-content">
                            <div class="grid-stack__header draggable-heading">
                                <div class="left">
                                    <button type="button" class="button lock-button" title="Lock Widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 364.3 485.8">
                                            <g id="padlock" transform="translate(-59.25 1.5)">
                                                <g id="Group_1" data-name="Group 1">
                                                <path id="Path_4" data-name="Path 4" d="M395.95,210.4h-7.1V147.5C388.85,66.2,322.75,0,241.35,0c-81.3,0-147.5,66.1-147.5,147.5a13.5,13.5,0,0,0,27,0,120.5,120.5,0,0,1,241,0v62.9h-275a26.119,26.119,0,0,0-26.1,26.1V404.6a78.314,78.314,0,0,0,78.2,78.2h204.9a78.314,78.314,0,0,0,78.2-78.2V236.5A26.119,26.119,0,0,0,395.95,210.4Zm-.9,194.2a51.235,51.235,0,0,1-51.2,51.2H139.05a51.235,51.235,0,0,1-51.2-51.2V237.4h307.2V404.6Z" stroke="#000" stroke-width="3"/>
                                                <path id="Path_5" data-name="Path 5" d="M241.45,399.1a50.5,50.5,0,1,0-50.5-50.5A50.552,50.552,0,0,0,241.45,399.1Zm0-74.1a23.55,23.55,0,1,1-23.5,23.6A23.537,23.537,0,0,1,241.45,325Z" stroke="#000" stroke-width="3"/>
                                                </g>
                                            </g>
                                        </svg>                                                                                    
                                    </button>
                                </div>
                                <div class="center">
                                    <h5>Market Sentiment</h5>
                                </div>
                                                            
                            </div>
                            <div class="grid-stack__content">
                               
  <div  style="height: 250px" id="sentiment" class="chart-container highcharts-figure"  data-fear={{$fear['fear']}} data-status={{$fear['class']}}>
                                        
                                </div>
                            
                            </div>
                        </div>
                    </div>  
                @endif              
                
                @if($value->widget->code=='dominance')       
                    <div class="grid-stack-item" id="{{$value->widget->code}}" data-widget_id="{{$value->widget->id}}" data-gs-height="{{$value->height}}" data-gs-width="{{$value->width}}" data-gs-x="{{$value->x}}" data-gs-y="{{$value->y}}">
                        <div class="grid-stack__wrapper grid-stack-item-content">
                            <div class="grid-stack__header draggable-heading">
                                <div class="left">
                                    <button type="button" class="button lock-button" title="Lock Widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 364.3 485.8">
                                            <g id="padlock" transform="translate(-59.25 1.5)">
                                                <g id="Group_1" data-name="Group 1">
                                                <path id="Path_4" data-name="Path 4" d="M395.95,210.4h-7.1V147.5C388.85,66.2,322.75,0,241.35,0c-81.3,0-147.5,66.1-147.5,147.5a13.5,13.5,0,0,0,27,0,120.5,120.5,0,0,1,241,0v62.9h-275a26.119,26.119,0,0,0-26.1,26.1V404.6a78.314,78.314,0,0,0,78.2,78.2h204.9a78.314,78.314,0,0,0,78.2-78.2V236.5A26.119,26.119,0,0,0,395.95,210.4Zm-.9,194.2a51.235,51.235,0,0,1-51.2,51.2H139.05a51.235,51.235,0,0,1-51.2-51.2V237.4h307.2V404.6Z" stroke="#000" stroke-width="3"/>
                                                <path id="Path_5" data-name="Path 5" d="M241.45,399.1a50.5,50.5,0,1,0-50.5-50.5A50.552,50.552,0,0,0,241.45,399.1Zm0-74.1a23.55,23.55,0,1,1-23.5,23.6A23.537,23.537,0,0,1,241.45,325Z" stroke="#000" stroke-width="3"/>
                                                </g>
                                            </g>
                                        </svg>                                                                                    
                                    </button>
                                </div>
                                <div class="center">
                                    <h5>Crypto Dominance Chart</h5>
                                </div>
                            </div>
                            <div class="grid-stack__content">
                                
                                <div id="bitcoineth"  style="height: 200px" data-btc="{{$dominance['btc']}}" data-eth="{{$dominance['eth']}}" data-alt="{{$dominance['alt']}}"></div>

                            </div>
                        </div>
                    </div>
                @endif
                @if($value->widget->code=='total_m_cap')  
                    <div class="grid-stack-item" id="{{$value->widget->code}}" data-widget_id="{{$value->widget->id}}" data-gs-height="{{$value->height}}" data-gs-width="{{$value->width}}" data-gs-x="{{$value->x}}" data-gs-y="{{$value->y}}">
                        <div class="grid-stack__wrapper grid-stack-item-content">
                            <div class="grid-stack__header draggable-heading">
                                <div class="left">
                                    <button type="button" class="button lock-button" title="Lock Widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 364.3 485.8">
                                            <g id="padlock" transform="translate(-59.25 1.5)">
                                                <g id="Group_1" data-name="Group 1">
                                                <path id="Path_4" data-name="Path 4" d="M395.95,210.4h-7.1V147.5C388.85,66.2,322.75,0,241.35,0c-81.3,0-147.5,66.1-147.5,147.5a13.5,13.5,0,0,0,27,0,120.5,120.5,0,0,1,241,0v62.9h-275a26.119,26.119,0,0,0-26.1,26.1V404.6a78.314,78.314,0,0,0,78.2,78.2h204.9a78.314,78.314,0,0,0,78.2-78.2V236.5A26.119,26.119,0,0,0,395.95,210.4Zm-.9,194.2a51.235,51.235,0,0,1-51.2,51.2H139.05a51.235,51.235,0,0,1-51.2-51.2V237.4h307.2V404.6Z" stroke="#000" stroke-width="3"/>
                                                <path id="Path_5" data-name="Path 5" d="M241.45,399.1a50.5,50.5,0,1,0-50.5-50.5A50.552,50.552,0,0,0,241.45,399.1Zm0-74.1a23.55,23.55,0,1,1-23.5,23.6A23.537,23.537,0,0,1,241.45,325Z" stroke="#000" stroke-width="3"/>
                                                </g>
                                            </g>
                                        </svg>                                                                                    
                                    </button>
                                </div>
                                <div class="center">
                                    <h5>Total Market Capitalization</h5>
                                </div>
                            </div>
                            <div class="grid-stack__content">
                                <div id="market-capitalization" style="height: 200px"></div>
                            </div>
                        </div>
                    </div>            
                @endif 
                @if($value->widget->code=='portfolio') 
                    <div class="grid-stack-item" id="{{$value->widget->code}}" data-widget_id="{{$value->widget->id}}" data-gs-height="{{$value->height}}" data-gs-width="{{$value->width}}" data-gs-x="{{$value->x}}" data-gs-y="{{$value->y}}">
                        <div class="grid-stack__wrapper grid-stack-item-content">
                            <div class="grid-stack__header draggable-heading">
                                <div class="left">
                                    <button type="button" class="button lock-button" title="Lock Widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 364.3 485.8">
                                            <g id="padlock" transform="translate(-59.25 1.5)">
                                                <g id="Group_1" data-name="Group 1">
                                                <path id="Path_4" data-name="Path 4" d="M395.95,210.4h-7.1V147.5C388.85,66.2,322.75,0,241.35,0c-81.3,0-147.5,66.1-147.5,147.5a13.5,13.5,0,0,0,27,0,120.5,120.5,0,0,1,241,0v62.9h-275a26.119,26.119,0,0,0-26.1,26.1V404.6a78.314,78.314,0,0,0,78.2,78.2h204.9a78.314,78.314,0,0,0,78.2-78.2V236.5A26.119,26.119,0,0,0,395.95,210.4Zm-.9,194.2a51.235,51.235,0,0,1-51.2,51.2H139.05a51.235,51.235,0,0,1-51.2-51.2V237.4h307.2V404.6Z" stroke="#000" stroke-width="3"/>
                                                <path id="Path_5" data-name="Path 5" d="M241.45,399.1a50.5,50.5,0,1,0-50.5-50.5A50.552,50.552,0,0,0,241.45,399.1Zm0-74.1a23.55,23.55,0,1,1-23.5,23.6A23.537,23.537,0,0,1,241.45,325Z" stroke="#000" stroke-width="3"/>
                                                </g>
                                            </g>
                                        </svg>                                                                                    
                                    </button>
                                </div>
                                <div class="center">
                                    <h5>Portfolio</h5>
                                </div>
                            </div>
                            <div class="grid-stack__content">
                                <h5 align="center">Coming Soon</h5>
                                <div class="line-chart">
                                    <div class="aspect-ratio">
                                        <canvas id="portfolio"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($value->widget->code=='news_feed') 
                    <div class="grid-stack-item" data-widget_id="{{$value->widget->id}}" data-gs-height="{{$value->height}}" data-gs-width="{{$value->width}}" data-gs-x="{{$value->x}}" data-gs-y="{{$value->y}}">
                        <div class="grid-stack__wrapper grid-stack-item-content newsfeed">
                            <div class="grid-stack__header draggable-heading">
                                <div class="left">
                                    <button type="button" class="button lock-button" title="Lock Widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 364.3 485.8">
                                            <g id="padlock" transform="translate(-59.25 1.5)">
                                                <g id="Group_1" data-name="Group 1">
                                                <path id="Path_4" data-name="Path 4" d="M395.95,210.4h-7.1V147.5C388.85,66.2,322.75,0,241.35,0c-81.3,0-147.5,66.1-147.5,147.5a13.5,13.5,0,0,0,27,0,120.5,120.5,0,0,1,241,0v62.9h-275a26.119,26.119,0,0,0-26.1,26.1V404.6a78.314,78.314,0,0,0,78.2,78.2h204.9a78.314,78.314,0,0,0,78.2-78.2V236.5A26.119,26.119,0,0,0,395.95,210.4Zm-.9,194.2a51.235,51.235,0,0,1-51.2,51.2H139.05a51.235,51.235,0,0,1-51.2-51.2V237.4h307.2V404.6Z" stroke="#000" stroke-width="3"/>
                                                <path id="Path_5" data-name="Path 5" d="M241.45,399.1a50.5,50.5,0,1,0-50.5-50.5A50.552,50.552,0,0,0,241.45,399.1Zm0-74.1a23.55,23.55,0,1,1-23.5,23.6A23.537,23.537,0,0,1,241.45,325Z" stroke="#000" stroke-width="3"/>
                                                </g>
                                            </g>
                                        </svg>                                                                                    
                                    </button>
                                </div>
                                <div class="center">
                                    <h5>News Feeds</h5>
                                </div>
                                
                            </div>
                            <div class="grid-stack__content">
                                <ul class="drag-list drag-true news-list" data-delay="8" id="authUser" data-id="3">
                                    @if(!empty($feeds))
                                        @foreach($feeds as $single_feed)
                                        <li id="feed-68818" class="article ">
                                            <a target="_blank" href="{{$single_feed->link}}{{-- {{route('NewsRead',[$single_feed->id,preg_replace('/\s+/', '_', $single_feed->title)])}} --}}">
                                                <span class="ago-time" data-time="{{$single_feed->time()}}">{{$single_feed->time()}}</span>
                                                <h2 data-id="{{$single_feed->id}}" >{{$single_feed->title}}</h2>
                                                <h5>
                                                    <span><i class="{{$single_feed->feed->getCategory->icon}}"></i></span> {{isset($single_feed->feed->name) ? $single_feed->feed->name : ''}} &nbsp;
                                                    <span><i class="far fa-calendar-times"></i></span>  {{isset($single_feed->pub_date) ? date("d/m/Y ",strtotime($single_feed->pub_date))  : ''}} &nbsp;
                                                    <span><i class="far fa-clock"></i></span>{{isset($single_feed->pub_date) ? date("H:i:s",strtotime($single_feed->pub_date))  : ''}}
                                                </h5>
                                            </a>
                                        </li>	
                                        @endforeach		
                                    @endif	    
            					</ul>
                            </div>
                        </div>
                    </div>
                @endif
                @if($value->widget->code=='smart_trade') 
                    <div class="grid-stack-item" data-widget_id="{{$value->widget->id}}" data-gs-height="{{$value->height}}" data-gs-width="{{$value->width}}" data-gs-x="{{$value->x}}" data-gs-y="{{$value->y}}">
                       
                        <div class="grid-stack__wrapper grid-stack-item-content">
                            <div class="grid-stack__header draggable-heading">
                                <div class="left">
                                    <button type="button" class="button lock-button" title="Lock Widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 364.3 485.8">
                                            <g id="padlock" transform="translate(-59.25 1.5)">
                                                <g id="Group_1" data-name="Group 1">
                                                <path id="Path_4" data-name="Path 4" d="M395.95,210.4h-7.1V147.5C388.85,66.2,322.75,0,241.35,0c-81.3,0-147.5,66.1-147.5,147.5a13.5,13.5,0,0,0,27,0,120.5,120.5,0,0,1,241,0v62.9h-275a26.119,26.119,0,0,0-26.1,26.1V404.6a78.314,78.314,0,0,0,78.2,78.2h204.9a78.314,78.314,0,0,0,78.2-78.2V236.5A26.119,26.119,0,0,0,395.95,210.4Zm-.9,194.2a51.235,51.235,0,0,1-51.2,51.2H139.05a51.235,51.235,0,0,1-51.2-51.2V237.4h307.2V404.6Z" stroke="#000" stroke-width="3"/>
                                                <path id="Path_5" data-name="Path 5" d="M241.45,399.1a50.5,50.5,0,1,0-50.5-50.5A50.552,50.552,0,0,0,241.45,399.1Zm0-74.1a23.55,23.55,0,1,1-23.5,23.6A23.537,23.537,0,0,1,241.45,325Z" stroke="#000" stroke-width="3"/>
                                                </g>
                                            </g>
                                        </svg>                                                                                    
                                    </button>
                                </div>
                                <div class="center">
                                    <h5>Smart Trade</h5>
                                </div>
                            </div>
                            <h5 align="center">Coming Soon</h5>
                            <div class="grid-stack__content">
                                {{-- <div class="table-responsive">
                                    <table class="table table-striped table-bordered draggable-table">
                                        <thead>
                                            <tr>
                                                <th>Pairs</th>
                                                <th>Time Frame</th>
                                                <th>Activated Price</th>
                                                <th>Triggered Price </th>
                                                <th>Created Date</th>
                                                <th>Created Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-label="Pairs">IOTABTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>
                                           
                                            <tr>
                                                <td data-label="Pairs">REPBTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>    
                                            <tr>
                                                <td data-label="Pairs">IOTABTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>
                                            <tr>
                                                <td data-label="Pairs">LUNBTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>
                                            <tr>
                                                <td data-label="Pairs">AMBBTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>
                                            <tr>
                                                <td data-label="Pairs">SKYBTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>
                                            <tr>
                                                <td data-label="Pairs">REPBTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>
                                            <tr>
                                                <td data-label="Pairs">IOTABTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>
                                            <tr>
                                                <td data-label="Pairs">LUNBTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>
                                            <tr>
                                                <td data-label="Pairs">AMBBTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>
                                            <tr>
                                                <td data-label="Pairs">REPBTC</td>
                                                <td data-label="Time Frame">15m</td>
                                                <td data-label="Activated Price">0.00003268</td>
                                                <td data-label="Triggered Price ">0.00003265</td>
                                                <td data-label="Created Date">22-10-2019</td>
                                                <td data-label="Created Time">09:10:31 am</td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                </div> --}}
                            </div>
                        </div>
                    </div> 
                @endif
                @if($value->widget->code=='smart_signals')
                    <div class="grid-stack-item" id="{{$value->widget->code}}" data-widget_id="{{$value->widget->id}}" data-gs-height="{{$value->height}}" data-gs-width="{{$value->width}}" data-gs-x="{{$value->x}}" data-gs-y="{{$value->y}}">
                        <div class="grid-stack__wrapper grid-stack-item-content">
                            <div class="grid-stack__header draggable-heading">
                                <div class="left">
                                    <button type="button" class="button lock-button" title="Lock Widget">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 364.3 485.8">
                                            <g id="padlock" transform="translate(-59.25 1.5)">
                                                <g id="Group_1" data-name="Group 1">
                                                <path id="Path_4" data-name="Path 4" d="M395.95,210.4h-7.1V147.5C388.85,66.2,322.75,0,241.35,0c-81.3,0-147.5,66.1-147.5,147.5a13.5,13.5,0,0,0,27,0,120.5,120.5,0,0,1,241,0v62.9h-275a26.119,26.119,0,0,0-26.1,26.1V404.6a78.314,78.314,0,0,0,78.2,78.2h204.9a78.314,78.314,0,0,0,78.2-78.2V236.5A26.119,26.119,0,0,0,395.95,210.4Zm-.9,194.2a51.235,51.235,0,0,1-51.2,51.2H139.05a51.235,51.235,0,0,1-51.2-51.2V237.4h307.2V404.6Z" stroke="#000" stroke-width="3"/>
                                                <path id="Path_5" data-name="Path 5" d="M241.45,399.1a50.5,50.5,0,1,0-50.5-50.5A50.552,50.552,0,0,0,241.45,399.1Zm0-74.1a23.55,23.55,0,1,1-23.5,23.6A23.537,23.537,0,0,1,241.45,325Z" stroke="#000" stroke-width="3"/>
                                                </g>
                                            </g>
                                        </svg>                                                                                    
                                    </button>
                                </div>
                                <div class="center">
                                    <h5>Smart Signals</h5>
                                </div>
                                                            
                            </div>
                            <div class="grid-stack__content">
                               
                               
                                    <table id="" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Pairs</th>
                                                <th>Time Frame</th>
                                                <th>Category</th>
                                               
                                                <th>Image</th>
                                               
                                                <th>Created Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($alerts as $alert) 
                                            <tr>
                                                <td>{{$alert->coin}}</td>
                                                <td>{{$alert->time_interval}}</td>
                                                <td>{{($alert->signal) ? $alert->signal->short_text : ''}}</td>
                                               
                                                <td>
                                                    @if(file_exists(public_path('/telegram/'.$alert->id.'.jpg') ))
                                                        {{-- <img height="20px" width="20px" src='{{asset("/telegram/$alert->id")}}.jpg'>  --}}
                                                        <img class="myImg"  height="20px" width="20px" src='{{asset("/telegram/$alert->id")}}.jpg' alt="{{$alert->category.'-'.$alert->coin.'-'.$alert->time_interval}}" >
                                                    @endif
                                                </td>
                                              
                                                <td>{{date_format($alert->created_at,'H:i:s a')}}</td>
                                            </tr>
                                            @endforeach
                                       
                                        </tbody>
                                    </table>
                                

                            
                            </div>
                        </div>
                    </div>  
                @endif  

            @endforeach 
        </div>
	</div>
</div>
    <button id="addWidgetBtn" title="Add Widget" data-toggle="modal" data-target="#myModal"><i class="fas fa-plus"></i></button>

@endsection 

@section('scripts')
	

<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js'></script>
<style type="text/css">


@media (max-width: 600px) {
    .highcharts-figure, .highcharts-data-table table {
        width: 100%;
    }
    .highcharts-figure .chart-container {
        width: 300px;
        float: none;
        margin: 0 auto;
    }

} */

</style>


<script>
    // ============================================
// As of Chart.js v2.5.0
// http://www.chartjs.org/docs
// ============================================

var chart    = document.getElementById('market-capitalization').getContext('2d'),
    gradient = chart.createLinearGradient(0, 0, 0, 235);
// gradient.addColorStop(0.0, 'rgba(124, 124, 124, 1)');
// gradient.addColorStop(0.15, 'rgba(124, 124, 124, 1)');
gradient.addColorStop(0, 'rgba(124, 33, 204, 0.4)');
gradient.addColorStop(0.4, 'rgba(124, 33, 204, 0.2)');
gradient.addColorStop(0.8, 'rgba(124, 33, 204, 0)');


var chart02    = document.getElementById('dominance').getContext('2d'),
    gradient = chart.createLinearGradient(0, 0, 0, 235);
// gradient.addColorStop(0.0, 'rgba(124, 124, 124, 1)');
// gradient.addColorStop(0.15, 'rgba(124, 124, 124, 1)');
gradient.addColorStop(0, 'rgba(124, 33, 204, 0.4)');
gradient.addColorStop(0.4, 'rgba(124, 33, 204, 0.2)');
gradient.addColorStop(0.8, 'rgba(124, 33, 204, 0)');

var chart03    = document.getElementById('market-sentiment').getContext('2d'),
    gradient = chart.createLinearGradient(0, 0, 0, 235);
// gradient.addColorStop(0.0, 'rgba(124, 124, 124, 1)');
// gradient.addColorStop(0.15, 'rgba(124, 124, 124, 1)');
gradient.addColorStop(0, 'rgba(124, 33, 204, 0.4)');
gradient.addColorStop(0.4, 'rgba(124, 33, 204, 0.2)');
gradient.addColorStop(0.8, 'rgba(124, 33, 204, 0)');
 

var chart04    = document.getElementById('portfolio').getContext('2d'),
    gradient = chart.createLinearGradient(0, 0, 0, 235);
// gradient.addColorStop(0.0, 'rgba(124, 124, 124, 1)');
// gradient.addColorStop(0.15, 'rgba(124, 124, 124, 1)');
gradient.addColorStop(0, 'rgba(124, 33, 204, 0.4)');
gradient.addColorStop(0.4, 'rgba(124, 33, 204, 0.2)');
gradient.addColorStop(0.8, 'rgba(124, 33, 204, 0)');


var chart05    = document.getElementById('total-cap').getContext('2d'),
    gradient = chart.createLinearGradient(0, 0, 0, 235);
// gradient.addColorStop(0.0, 'rgba(124, 124, 124, 1)');
// gradient.addColorStop(0.15, 'rgba(124, 124, 124, 1)');
gradient.addColorStop(0, 'rgba(124, 33, 204, 0.4)');
gradient.addColorStop(0.4, 'rgba(124, 33, 204, 0.2)');
gradient.addColorStop(0.8, 'rgba(124, 33, 204, 0)');

var chart06    = document.getElementById('volume').getContext('2d'),
    gradient = chart.createLinearGradient(0, 0, 0, 235);
// gradient.addColorStop(0.0, 'rgba(124, 124, 124, 1)');
// gradient.addColorStop(0.15, 'rgba(124, 124, 124, 1)');
gradient.addColorStop(0, 'rgba(124, 33, 204, 0.4)');
gradient.addColorStop(0.4, 'rgba(124, 33, 204, 0.2)');
gradient.addColorStop(0.8, 'rgba(124, 33, 204, 0)');


</script>
<script src="https://code.highcharts.com/highcharts.js"></script> 
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/modules/variable-pie.js"></script>
{{-- <script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>  --}}
<script type="text/javascript">
    // Get the modal
    $(".myImg").click(function(){
         $("#alertModal").show();
        $("#img01").attr('src',$(this).attr('src'));
         $("#caption").html($(this).attr('alt'));
        $('.alert.modal-title').html($(this).attr('alt'));
        

    // When the user clicks on <span> (x), close the modal
            $(".close").click(function(){
                 $("#alertModal").hide();
            });
    
    });
    
</script>
    <script type="text/javascript">
        $(document).ready(function(){
            if($('#market-capitalization').length >0)
            {
                mcap();
            }
            if($('#sentiment').length >0)
            {
                sentiment();
            }
            if($('#bitcoineth').length >0)
            {
                dominance(); 
            }   
        });
        
        $(function() {
            var isDragging = false;
            $('.grid-stack-item')
            .mousedown(function() {
                isDragging = false;
            })
            .mousemove(function() {
                isDragging = true;
             })
            .mouseup(function() {
                var wasDragging = isDragging;
                isDragging = false;
                if (!wasDragging) {
                    $("#throbble").toggle();
                }
                else
                {
                    
                    var allset={};
                    $('.grid-stack-item.ui-draggable').each(function(index,value) {
                        e=$(value).data();
                        height=e._gridstack_node.height;
                        width=e._gridstack_node.width;
                        x=e._gridstack_node.x;
                        y=e._gridstack_node.y;
                        widget_id=e.widget_id;
                        set={};
                        set['height']=height;
                        set['width']=width;
                        set['x']=x;
                        set['y']=y;
                        allset[widget_id]=set;
                    });
                    //console.log(allset);
                    //allset=JSON.stringify(allset);
                    $.ajax({
                        url:"{{route('SaveWidgets')}}",
                        data:{'positions':allset},
                        dataType:'json',
                        type:'post',
                        success:function(obj){
                            var data = JSON.stringify(obj.id);

                            localStorage.setItem('key',data);

                            //var response = JSON.parse(localStorage.getItem('key'));
                            
                                
                        }
                    });
                   


                    if( $(this).attr("id")=='total_m_cap')
                    {

                        mcap();
                    }
                    if($(this).attr("id")== 'market_sentiment')
                    {

                        sentiment();
                    }
                    if($(this).attr("id")== 'dominance')
                    {

                        dominance();
                    }
                   
                }
            });

            $("ul").sortable();
        });
      
    </script>
<script type="text/javascript">
function mcap()
{

    var chart=Highcharts.chart('market-capitalization', {
        chart: {
            type: 'area',
            zoomType: 'x',
            panning: true,
            panKey: 'shift',
            backgroundColor: 'rgba(0,0,0,0.0)',
            // scrollablePlotArea: {
            // minWidth: 300,
            // height: 200,  
            // }
        },
          title: {
      	text: ''
      }, 
      legend: {
            enabled: false
        },
        yAxis: {
        title: false
    },
      credits: {
            enabled: false
        }, 
        xAxis: {
            categories: ['1/2', '3/2' , '20/3 ', ' 1/3', '2/3', '3/3']
        },

        series: [{ 
            data: [49.9, 31.5, 49.4, 54.2, 34.0, 44.0],
            color: '#377df1',
            fillColor: {
                    linearGradient: [0, 0, 0, 300],
                    stops: [
                        [0, '#377df1'],
                        [1, 'rgba(55,125,241,0.0)']
                    ]
                }
        }]
    });
}
 
</script>
<script type="text/javascript">
    setInterval(function()
    { 
       
        $.ajax({
            type:"post",
            url:"{{route('AjaxliveDashboard')}}",
            datatype:"json",
            /*data: {'feed_count':feed_count,'category':category,'tag':tag,'time':time,'type':type},  */    
            success:function(data)
            {
                obj=JSON.parse(data);

                $('.totalmcap').html(obj.header.totalcapusd);
                $('.total24hr').html(obj.header.total24hr);
                
                $('.eth_price').html(obj.header.eth.price);
                $('.eth_price_change').html(obj.header.eth.icon+' '+obj.header.eth.price_change+'%').css('color',obj.header.eth.color);

                $('.btc_price').html(obj.header.btc.price);
                $('.btc_price_change').html(obj.header.btc.icon+' '+obj.header.btc.price_change+'%').css('color',obj.header.btc.color);
                //console.log(obj.topcoins);
                $('#bitcoineth').data('btc',obj.header.btc_dominance);
                $('#bitcoineth').data('eth',obj.header.eth_dominance);
                $('#bitcoineth').data('alt',obj.header.alt_dominance);
                console.log(obj.header.btc_dominance);
                 $('.topcoins').html('');
                $.each(obj.topcoins, function(k, v) {
                    rank=parseInt(k)+parseInt(1);
                    //console.log(v.quote.USD.price);
                    price = v.DISPLAY.USD.PRICE;
                    percent_change_1h=v.DISPLAY.USD.CHANGEPCTHOUR;
                    if(percent_change_1h > 0) 
                    { 
                        percent_change_1h_icon='<i class="fas fa-caret-up"></i>';
                        percent_change_1h_color='#58ca6a'; 
                    } 
                    else
                    {
                        percent_change_1h_icon='<i class="fas fa-caret-down"></i>';
                        percent_change_1h_color='#f04a38';
                    }

                    percent_change_7d=0.01;
                    if(percent_change_7d > 0) 
                    { 
                        percent_change_7d_icon='<i class="fas fa-caret-up"></i>';
                        percent_change_7d_color='#58ca6a'; 
                    } 
                    else
                    {
                        percent_change_7d_icon='<i class="fas fa-caret-down"></i>';
                        percent_change_7d_color='#f04a38';
                    }

                    percent_change_24h=v.DISPLAY.USD.CHANGEPCT24HOUR;
                     if(percent_change_24h > 0) 
                    { 
                        percent_change_24h_color='#58ca6a'; 
                        percent_change_24h_icon='<i class="fas fa-caret-up"></i>';
                    } 
                    else
                    {
                        percent_change_24h_color='#f04a38';
                        percent_change_24h_icon='<i class="fas fa-caret-down"></i>';
                    }
                    volume_24h=v.RAW.USD.VOLUME24HOURTO;


                    $('.topcoins').append('<tr><td>'+rank+'</td><td>'+v.CoinInfo.FullName+'</td><td>'+v.CoinInfo.Internal+'</td><td>'+v.RAW.USD.MKTCAP+'</td><td>'+price+'</td><td style="color:'+percent_change_1h_color+'">'+percent_change_1h_icon +' '+percent_change_1h+'%</td><td style="color:'+percent_change_7d_color+'">'+percent_change_7d_icon +' '+percent_change_7d+'%</td><td style="color:'+percent_change_24h_color+'">'+percent_change_24h_icon+' '+percent_change_24h+'%</td><td>'+volume_24h+'</td></tr>');

                });

            }   
        });         
    }, 60000);//time in milliseconds    
</script>

 <script type="text/javascript">
    localStorage.clear();
    localStorage.setItem('load_count', 10);
    $( document ).ready(function() {
                load= parseInt(localStorage.getItem('load_count'));
                //console.log(load);
                tag=$('#tags-input').val();
        time=$('#feed_time').val();

        var type = [];
        $("input:checkbox[name=type]:checked").each(function() {
                type.push($(this).val());
        });
                
        $.ajax({
            url:"{{route('GetAllFeed')}}",
            data:{'tag':tag,'time':time,'type':type,'load':load},
            dataType:'json',
            type:'post',
            success:function(obj){
                var data = JSON.stringify(obj.id);

                localStorage.setItem('key',data);
                
                //var response = JSON.parse(localStorage.getItem('key'));
                
                    
            }
        });
    });
</script>{{-- Bitcoin and eth dominance --}}
<script type="text/javascript">
function dominance()
{

     var eth=$('#bitcoineth').data("eth");
        var btc=$('#bitcoineth').data("btc");
        var alt=$('#bitcoineth').data("alt");
        console.log(alt);
        Highcharts.setOptions({
     colors: ['#6a9ef4', '#7faefc', '#99bdf8', '#24CBE5', '#64E572', '#FF9655', '#FFF263',      '#6AF9C4']
    });

    Highcharts.chart('bitcoineth', { 
        chart: {
            type: 'variablepie',
            backgroundColor: 'rgba(0,0,0,0.0)',
        },
        title: {
            text: ''
        },
        credits: {
        enabled: false
    },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> {point.name}</b><br/>' +
                'Dominance: <b>{point.y}</b><br/>' /*+
                'Population density (people per square km): <b>{point.z}</b><br/>'*/
        },
        series: [{
            minPointSize: 10,
            innerSize: '20%',
            zMin: 0,
            name: 'Dominance',
            borderWidth: 0,
            dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>:<br>{point.percentage:.1f} %',
                    yHigh: 20,
                    yLow: -20,
                    style: {
                        color: '#616161',
                        textShadow: false 
                    }
                },
            data: [{
                name: 'BTC',
                y: btc,
                z: 214.5 
            }, {
                name: 'ETH',
                y: eth,
                z: 214.5
            },  {
                name: 'Altcoin',
                y: alt,
                z: 214.5
            }]
        }]
    });
}

</script>
<script type="text/javascript">
localStorage.clear();
localStorage.setItem('load_count', 40);
$('.newsfeed').scroll(function() {
    e=$(document).height(); r=$('.newsfeed').height();
    s=r+r;
    t=$('.newsfeed').scrollTop();
    console.log(t+50+'===='+s);
   
    var response = JSON.parse(localStorage.getItem('key'));
    if(Array.isArray(response) && response.length)
    {
        if(t+50 >= s) {
            if(!$('#loader').length)
            {
                $('.drag-list').append('<li id="loader">Loading..</li>');
            }   
            load= parseInt(localStorage.getItem('load_count'))+1;
            //load=localStorage.setItem('load_count', parseInt(localStorage.getItem('load_count'))+50);
            tag=$('#tags-input').val();
            time=$('#feed_time').val();

            var type = [];
            $("input:checkbox[name=type]:checked").each(function() {
                    type.push($(this).val());
            });
            //console.log(tag+'='+time+'='+type);
            $.ajax({
                url:"{{route('PreLoader')}}",
                type: 'POST',
                dataType: 'json',
                data:{'tag':tag,'time':time,'type':type,'load':load,'response':response},
                success: function(obj)
                {
                    //$('.drag-list').html('');
                    $.each(obj.result, function(k, v) {
                        $("input[name=feed_count]").val(v.last_update);
                        sid='feed-'+v.id;

                        if($("#"+sid).length == 0) {
                            if(k<40)
                            {
                                $('.drag-list').append('<li id="feed-'+v.id+'" class="article"><a target="_blank" href="'+v.link+'" ><span class="ago-time">'+v.time+'</span><h2 data-id="'+v.id+'">'+v.title+' </h2><h5><span><i class="'+v.icon+'"></i></span>'+v.feed_provider+'&nbsp;<span><i class="far fa-calendar-times"></i></span>'+v.pub_date+'&nbsp;<span><i class="far fa-clock"></i></span>'+v.pub_time+'</h5></a></li>');
                            }
                        }

                    });
                    response.splice(0,40);
                    var data = JSON.stringify(response);
                    localStorage.setItem('key',data);   
                    localStorage.setItem('load_count', parseInt(localStorage.getItem('load_count'))+40);
                },
                complete: function(){
                        $('#loader').remove();
                        if(Array.isArray(response) && !response.length)
                            {
                                if(!$('#loader_end').length)
                                {

                                    $('.drag-list').append('<li id="loader_end">You have reached to end.</li>');
                                }
                            }  
                      }
            });
        }
    }
    else
    {
        if(!$('#loader_end').length)
        {

            $('.drag-list').append('<li id="loader_end">You have reached to end.</li>');
        }
    }    

}); 
</script> 
<script type="text/javascript">
    setInterval(function()
    { 
        
        var phrases = [];

        $('.drag-list').each(function(){
        // this is inner scope, in reference to the .phrase element
        var phrase = '';
        $(this).find('li').each(function(){
        // cache jquery var
        var current = $(this).children().children('.ago-time');
        var time=current.text();
        var min = 0;
        array_time=time.split(" ");

        if(array_time[0] > 59 && array_time[1] == 'Min')
        {
            time = 1 + ' Hr';
        }
        else if(array_time[0] <= 59 && array_time[1] == 'Min')
        {
            temp = array_time[0];
            temp++;
            time = temp + ' Min';
        }
        else if(array_time[0] == 'Few' && array_time[1] == 'Sec')
        {
            temp = 1;
            time = temp + ' Min';
        }
        else if (array_time[1] == 'Hr') {
            min++;
            if(min == 60)
            {
                min = 0;
                if(array_time[0] >23)
                {
                    time = 1 + ' day';
                }
                else
                {
                    temp = array_time[0];
                    temp++;
                    time = temp + ' Hr';
                }
            }
        }
        else{}

        current.text(time);
        
        // check if our current li has children (sub elements)
        // if it does, skip it
        // ps, you can work with this by seeing if the first child
        // is a UL with blank inside and odd your custom BLANK text
        if(current.children().size() > 0) {return true;}
        // add current text to our current phrase
        phrase += current.text();
        });
        // now that our current phrase is completely build we add it to our outer array
        phrases.push(phrase);
        });
                        
    }, 60000);
</script>
{{-- market sentiment --}}
<script type="text/javascript">
function sentiment()
{

    var value=$('.highcharts-figure').data("fear");
    var status=$('.highcharts-figure').data("status");
    var status=status.replace('_',' ');
    console.log(status);
   

    // The speed gauge
   Highcharts.chart('sentiment', {

       chart: {
           type: 'gauge',
           plotBackgroundColor: null,
           plotBackgroundImage: null,
           plotBorderWidth: 0,
           plotShadow: false,
           backgroundColor: 'rgba(0,0,0,0.0)',
       },

       title: {
           text: ''
       },
        credits: {
            enabled: false
        },

        pane: {
           startAngle:-150,
           endAngle: 150,
           background: [{
               backgroundColor: {
                   linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                   stops: [
                       [0, '#82aef6'],
                       [1, '#82aef6']
                   ]
               },
               borderWidth: 0,
               outerRadius: '0%'
           }, {
               backgroundColor: {
                   linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                   stops: [
                       [0, '#333'],
                       [1, '#FFF']
                   ]
               },
               borderWidth: 0,
               outerRadius: '10%'
           }, {
                backgroundColor: 'rgba(0,0,0,0.0)'
           }, {
               backgroundColor: '#000',
               borderWidth: 0,
               outerRadius: '0%',
               innerRadius: '0%'
           }]
       },

       // the value axis
       yAxis: {
            title: {

                text: status,
                fontSize:'1px',
                style: {
                   color: 'rgba(0,0,0,0.0)'
                }
           },
           min: 0,
           max: 100,

           minorTickInterval: 'auto',
           minorTickWidth: 1,
           minorTickLength: 5,
           minorTickPosition: 'inside',
           minorTickColor: '#c8dcfe',

           tickPixelInterval: 20,
           tickWidth: 2,
           tickPosition: 'inside',
           tickLength: 12,
           tickColor: '#fff',
           
           labels: {
               step: 2,
               rotation: 'auto',       
               style: {
                   color: '#606163'
               },          
           },
           
           plotBands: [{
               from: 0,
               to: 25,
               color: '#FE2A15' // red
           }, {
               from: 25,
               to: 50,
               color: '#FE5F15' // orange
           }, {
               from: 50,
               to: 75,
               color: '#FEAD15' // yellow
           },{
               from: 75,
               to: 100,
               color: '#46FE15' // green
           }]
        // plotBands: [{
        //        from: 0,
        //        to: 25,
        //        color: '#86b2fb' // red
        //    }, {
        //        from: 25,
        //        to: 50,
        //        color: '#679df7' // orange
        //    }, {
        //        from: 50,
        //        to: 75,
        //        color: '#4989f4' // yellow
        //    },{
        //        from: 75,
        //        to: 100,
        //        color: '#377df1' // green
        //    }]
       },

       series: [{
           name: 'Sentiment',
           data: [value],
          
           dial: {
            backgroundColor: '#2965c7'
            },
           tooltip: {
               valueSuffix: ' '
           }
       }]


   });

    // The RPM gauge
}

</script>
{{-- Live data change --}}

@endsection

