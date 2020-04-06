	@extends('layouts.admin')
	@section('content')
    <div class="content-wrapper height-100">

        <div class="grid-stack">

            <div class="grid-stack-item" data-gs-height="4" data-gs-width="4" data-gs-x="0" data-gs-y="0">
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
                            <h5>Feeds</h5>
                        </div>
                        <div class="right">
                            <button type="button" class="button minimize-button" title="Minimize Widget">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 48">
                                    <path id="Path_1" data-name="Path 1" d="M376,232H8a8,8,0,0,0-8,8v32a8,8,0,0,0,8,8H376a8,8,0,0,0,8-8V240A8,8,0,0,0,376,232Z" transform="translate(0 -232)"/>
                                </svg>                                          
                            </button>
                            <button type="button" class="button close-button" title="Close Widget">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 496">
                                    <path id="Path_3" data-name="Path 3" d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm0,464C137.3,472,40,375.9,40,256,40,137.3,136.1,40,256,40c118.7,0,216,96.1,216,216C472,374.7,375.9,472,256,472Zm94.8-285.3L281.5,256l69.3,69.3a12.011,12.011,0,0,1,0,17l-8.5,8.5a12.011,12.011,0,0,1-17,0L256,281.5l-69.3,69.3a12.011,12.011,0,0,1-17,0l-8.5-8.5a12.011,12.011,0,0,1,0-17L230.5,256l-69.3-69.3a12.011,12.011,0,0,1,0-17l8.5-8.5a12.011,12.011,0,0,1,17,0L256,230.5l69.3-69.3a12.011,12.011,0,0,1,17,0l8.5,8.5a12.2,12.2,0,0,1,0,17Z" transform="translate(-8 -8)"/>
                                </svg>                                          
                            </button>
                        </div>                            
                    </div>
                    <div class="grid-stack__content">
                        <ul class="drag-list drag-true">
                            <li>
                                <span class="ago-time">8min</span>
                                <span class="content">
                                    <h2>ROGER VER: ETHEREUM ICO’LAR İÇIN HARIKA BIR PLATFORM 1</h2>
                                    <h5> 
                                        <span><img src="{{asset('admin/images/feeds.png')}}"></span> Koinbox &nbsp; 
                                        <span><img src="{{asset('admin/images/calendar.png')}}"></span>  Tue, 15 Oct 2019 &nbsp; 
                                        <span><img src="{{asset('admin/images/clock.png')}}"></span>08:29:46 +0000
                                    </h5>
                                </span>
                                <span class="drag-area">
                                    <div class="button-wrap">
                                        <a href="#">
                                            <img src="{{asset('admin/images/view.svg')}}" class="img-fluid" alt="move">
                                        </a>
                                        <a href="#">
                                            <img src="{{asset('admin/images/edit.svg')}}" class="img-fluid" alt="edit">
                                        </a>
                                        <a href="#">
                                            <img src="{{asset('admin/images/delete.svg')}}" class="img-fluid" alt="delete">
                                        </a>
                                    </div>								
                                </span>
                            </li>
                            <li>
                                <span class="ago-time">17min</span>
                                <span class="content">
                                    <h2>ROGER VER: ETHEREUM ICO’LAR İÇIN HARIKA BIR PLATFORM 2</h2>
                                    <h5> 
                                        <span><img src="{{asset('admin/images/feeds.png')}}"></span> Koinbox &nbsp; 
                                        <span><img src="{{asset('admin/images/calendar.png')}}"></span>  Tue, 15 Oct 2019 &nbsp; 
                                        <span><img src="{{asset('admin/images/clock.png')}}"></span>08:29:46 +0000
                                    </h5>
                                </span>
                                <span class="drag-area">
                                    <div class="button-wrap">
                                        <a href="#">
                                            <img src="{{asset('admin/images/view.svg')}}" class="img-fluid" alt="move">
                                        </a>
                                        <a href="#">
                                            <img src="{{asset('admin/images/edit.svg')}}" class="img-fluid" alt="edit">
                                        </a>
                                        <a href="#">
                                            <img src="{{asset('admin/images/delete.svg')}}" class="img-fluid" alt="delete">
                                        </a>
                                    </div>							
                                </span>
                            </li>
                            <li>
                                <span class="ago-time">36min</span>
                                <span class="content">
                                    <h2>ROGER VER: ETHEREUM ICO’LAR İÇIN HARIKA BIR PLATFORM 3</h2>
                                    <h5> 
                                        <span><img src="{{asset('admin/images/feeds.png')}}"></span> Koinbox &nbsp; 
                                        <span><img src="{{asset('admin/images/calendar.png')}}"></span>  Tue, 15 Oct 2019 &nbsp; 
                                        <span><img src="{{asset('admin/images/clock.png')}}"></span>08:29:46 +0000
                                    </h5>
                                </span>
                                <span class="drag-area">
                                    <div class="button-wrap">
                                        <a href="#">
                                            <img src="{{asset('admin/images/view.svg')}}" class="img-fluid" alt="move">
                                        </a>
                                        <a href="#">
                                            <img src="{{asset('admin/images/edit.svg')}}" class="img-fluid" alt="edit">
                                        </a>
                                        <a href="#">
                                            <img src="{{asset('admin/images/delete.svg')}}" class="img-fluid" alt="delete">
                                        </a>
                                    </div>							
                                </span>
                            </li>
                            <li>
                                <span class="ago-time">54min</span>
                                <span class="content">
                                    <h2>ROGER VER: ETHEREUM ICO’LAR İÇIN HARIKA BIR PLATFORM 4</h2>
                                    <h5> 
                                        <span><img src="{{asset('admin/images/feeds.png')}}"></span> Koinbox &nbsp; 
                                        <span><img src="{{asset('admin/images/calendar.png')}}"></span>  Tue, 15 Oct 2019 &nbsp; 
                                        <span><img src="{{asset('admin/images/clock.png')}}"></span>08:29:46 +0000
                                    </h5>
                                </span>
                                <span class="drag-area">
                                    <div class="button-wrap">
                                        <a href="#">
                                            <img src="{{asset('admin/images/view.svg')}}" class="img-fluid" alt="move">
                                        </a>
                                        <a href="#">
                                            <img src="{{asset('admin/images/edit.svg')}}" class="img-fluid" alt="edit">
                                        </a>
                                        <a href="#">
                                            <img src="{{asset('admin/images/delete.svg')}}" class="img-fluid" alt="delete">
                                        </a>
                                    </div>							
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid-stack-item" data-gs-height="4" data-gs-width="8" data-gs-x="4" data-gs-y="0">
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
                            <h5>Alerts Log</h5>
                        </div>
                        <div class="right">
                            <button type="button" class="button minimize-button" title="Minimize Widget">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 48">
                                    <path id="Path_1" data-name="Path 1" d="M376,232H8a8,8,0,0,0-8,8v32a8,8,0,0,0,8,8H376a8,8,0,0,0,8-8V240A8,8,0,0,0,376,232Z" transform="translate(0 -232)"/>
                                </svg>                                          
                            </button>
                            <button type="button" class="button close-button" title="Close Widget">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 496">
                                    <path id="Path_3" data-name="Path 3" d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm0,464C137.3,472,40,375.9,40,256,40,137.3,136.1,40,256,40c118.7,0,216,96.1,216,216C472,374.7,375.9,472,256,472Zm94.8-285.3L281.5,256l69.3,69.3a12.011,12.011,0,0,1,0,17l-8.5,8.5a12.011,12.011,0,0,1-17,0L256,281.5l-69.3,69.3a12.011,12.011,0,0,1-17,0l-8.5-8.5a12.011,12.011,0,0,1,0-17L230.5,256l-69.3-69.3a12.011,12.011,0,0,1,0-17l8.5-8.5a12.011,12.011,0,0,1,17,0L256,230.5l69.3-69.3a12.011,12.011,0,0,1,17,0l8.5,8.5a12.2,12.2,0,0,1,0,17Z" transform="translate(-8 -8)"/>
                                </svg>                                          
                            </button>
                        </div>                            
                    </div>
                    <div class="grid-stack__content">
                        <div class="table-responsive">
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
                                    @foreach($alert_logs as $alert)
                                    <tr>
                                        <td data-label="Pairs">{{$alert->coin}}</td>
                                        <td data-label="Time Frame">{{$alert->time_interval}}</td>
                                        <td data-label="Activated Price">{{$alert->price}}</td>
                                        <td data-label="Triggered Price ">{{$alert->price_2}}</td>
                                        <td data-label="Created Date">{{date_format($alert->created_at,'d-m-Y')}}</td>
                                        <td data-label="Created Time">{{date_format($alert->created_at,'H:i:s a')}}</td>
                                    </tr>
                                    @endforeach                             
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-stack-item" data-gs-height="4" data-gs-width="5" data-gs-x="0" data-gs-y="4">
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
                            <h5>Alert Settings</h5>
                        </div>
                        <div class="right">
                            <button type="button" class="button minimize-button" title="Minimize Widget">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 48">
                                    <path id="Path_1" data-name="Path 1" d="M376,232H8a8,8,0,0,0-8,8v32a8,8,0,0,0,8,8H376a8,8,0,0,0,8-8V240A8,8,0,0,0,376,232Z" transform="translate(0 -232)"/>
                                </svg>                                          
                            </button>
                            <button type="button" class="button close-button" title="Close Widget">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 496">
                                    <path id="Path_3" data-name="Path 3" d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm0,464C137.3,472,40,375.9,40,256,40,137.3,136.1,40,256,40c118.7,0,216,96.1,216,216C472,374.7,375.9,472,256,472Zm94.8-285.3L281.5,256l69.3,69.3a12.011,12.011,0,0,1,0,17l-8.5,8.5a12.011,12.011,0,0,1-17,0L256,281.5l-69.3,69.3a12.011,12.011,0,0,1-17,0l-8.5-8.5a12.011,12.011,0,0,1,0-17L230.5,256l-69.3-69.3a12.011,12.011,0,0,1,0-17l8.5-8.5a12.011,12.011,0,0,1,17,0L256,230.5l69.3-69.3a12.011,12.011,0,0,1,17,0l8.5,8.5a12.2,12.2,0,0,1,0,17Z" transform="translate(-8 -8)"/>
                                </svg>                                          
                            </button>
                        </div>                            
                    </div>
                    <div class="grid-stack__content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered draggable-table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Time Frame</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="Pairs"><b>RSI</b></td>
                                        <td data-label="Time Frame">15 Minutes,<br />1Hour,<br />4 Hours</td>
                                        <td data-label="Action"><a class="btn btn-sm btn-warning d-inline-block">Edit</a></td>       
                                    </tr>
                                    <tr>
                                        <td data-label="Pairs"><b>SMA</b></td>
                                        <td data-label="Time Frame">15 Minutes,<br />1Hour,<br />4 Hours</td>
                                        <td data-label="Action"><a class="btn btn-sm btn-warning d-inline-block">Edit</a></td>   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>
    @endsection
