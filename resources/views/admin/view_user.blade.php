@extends('layouts.admin')
@section('content')
<div class="content-wrapper height-100">
    <div class=" dashboard">
        <div class="container-fluid">


            <div class=" ">


                <div class="user-view-left">
                    <div class="admin-controller">

                    
                        <div class="row">
                            <div class="col-md-12 mt-3">



                                <div class="kt-widget__media">
                                    <img class="kt-widget__img "
                                        src="https://keenthemes.com/metronic/themes/metronic/theme/default/demo1/dist/assets/media/users/300_21.jpg"
                                        alt="image">
                                </div>
                                <h2 class="text-center mt-2">{{$user->name}}</h2>
                                <p class="text-center mt-1">Suscribed Users List:</p>
                                <div class="text-center mt-2">
                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">active</span>
                                </div>
                                <div class="kt-widget__body" id="kt-widget">

                                    <a data-scroll href="#profile-box-01" class="active">
                                        Profile Overview
                                    </a>
                                    <a data-scroll href="#profile-box-02">
                                        Personal info
                                    </a>
                                    <a data-scroll href="#profile-box-03">
                                        Account info
                                    </a>
                                    <a data-scroll href="#profile-box-04">
                                        Change Password
                                    </a>
                                    <a data-scroll href="#profile-box-05">
                                        Email settings
                                    </a>
                                    <a data-scroll href="#profile-box-06">
                                        Saved Credit Cards
                                    </a>
                                    <a data-scroll href="#profile-box-06">
                                        Tax information
                                    </a>






                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
                <div class="user-view-right">

                    <div class="admin-controller " id="profile-box-01">
                        <form class="form-horizontal" method="post" action="{{ route('ChangeSubscriptionPeriod') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    @if(session('succes'))
                                    <label class="control-label success">{{ session('succes') }}</label>
                                    @endif
                                    <h2>Profile Overview</h2>
                                    <p> user details </p>
                                </div>
                                <div class="col-md-6 head-right-btns">
                                    <a href="{{route('UserList')}}" class="btn btn-label-brand mt-2"> Back </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12  ">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <input type="hidden" name="_token1"
                                        value="toKLmUNDnGp17R1fyYIZV7ckavnjXWrd2RMF32yC">
                                    <div class="row">
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Name</label>
                                            <input type="text" name="name" value="{{$user->name}}" required=""
                                                class="form-control " id="name" placeholder="Enter Name">
                                            <span class="text-danger"></span>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Email</label>
                                            <input type="email" name="email" required="" class="form-control"
                                                value="{{$user->email}}" id="email" placeholder="Enter email">
                                            <span class="text-danger"></span>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Password</label>
                                            <input type="text" name="password" required=""
                                                value="{{$user->password_string}}" class="form-control " id="pwd"
                                                placeholder="Generate password" readonly="true">
                                            <span class="text-danger"></span>
                                            <button type="button" id="generate">Generate</button>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Subscription period</label>
                                            <div class="form-group ">
                                                <select name="subscription_period" required="" class="form-control "
                                                    id="subscription_period">
                                                    <option value="1">3 Month </option>
                                                    <option value="2">1 Year </option>
                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12  mt-3 mb-2">
                                            <button type="submit"
                                                class="button btn-default primary-button">Submit</button>
                                            <a href="https://test.cryptoscannerpro.com/admin/user/list">
                                                <button type="button"
                                                    class="button btn-default primary-button">Cancel</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="admin-controller mt-3" id="profile-box-02">
                        <form class="form-horizontal" method="post" action="{{ route('ChangeSubscriptionPeriod') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    @if(session('succes'))
                                    <label class="control-label success">{{ session('succes') }}</label>
                                    @endif
                                    <h2>Personal info</h2>
                                    <p> user details </p>
                                </div>
                                <div class="col-md-6 head-right-btns">
                                    <a href="{{route('UserList')}}" class="btn btn-label-brand mt-2"> Back </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12  ">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <input type="hidden" name="_token1"
                                        value="toKLmUNDnGp17R1fyYIZV7ckavnjXWrd2RMF32yC">
                                    <div class="row">
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Name</label>
                                            <input type="text" name="name" value="{{$user->name}}" required=""
                                                class="form-control " id="name" placeholder="Enter Name">
                                            <span class="text-danger"></span>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Email</label>
                                            <input type="email" name="email" required="" class="form-control"
                                                value="{{$user->email}}" id="email" placeholder="Enter email">
                                            <span class="text-danger"></span>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Password</label>
                                            <input type="text" name="password" required=""
                                                value="{{$user->password_string}}" class="form-control " id="pwd"
                                                placeholder="Generate password" readonly="true">
                                            <span class="text-danger"></span>
                                            <button type="button" id="generate">Generate</button>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Subscription period</label>
                                            <div class="form-group ">
                                                <select name="subscription_period" required="" class="form-control "
                                                    id="subscription_period">
                                                    <option value="1">3 Month </option>
                                                    <option value="2">1 Year </option>
                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12  mt-3 mb-2">
                                            <button type="submit"
                                                class="button btn-default primary-button">Submit</button>
                                            <a href="https://test.cryptoscannerpro.com/admin/user/list">
                                                <button type="button"
                                                    class="button btn-default primary-button">Cancel</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="admin-controller mt-3" id="profile-box-03">
                        <form class="form-horizontal" method="post" action="{{ route('ChangeSubscriptionPeriod') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    @if(session('succes'))
                                    <label class="control-label success">{{ session('succes') }}</label>
                                    @endif
                                    <h2>Account info</h2>
                                    <p> user details </p>
                                </div>
                                <div class="col-md-6 head-right-btns">
                                    <a href="{{route('UserList')}}" class="btn btn-label-brand mt-2"> Back </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12  ">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <input type="hidden" name="_token1"
                                        value="toKLmUNDnGp17R1fyYIZV7ckavnjXWrd2RMF32yC">
                                    <div class="row">
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Name</label>
                                            <input type="text" name="name" value="{{$user->name}}" required=""
                                                class="form-control " id="name" placeholder="Enter Name">
                                            <span class="text-danger"></span>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Email</label>
                                            <input type="email" name="email" required="" class="form-control"
                                                value="{{$user->email}}" id="email" placeholder="Enter email">
                                            <span class="text-danger"></span>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Password</label>
                                            <input type="text" name="password" required=""
                                                value="{{$user->password_string}}" class="form-control " id="pwd"
                                                placeholder="Generate password" readonly="true">
                                            <span class="text-danger"></span>
                                            <button type="button" id="generate">Generate</button>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Subscription period</label>
                                            <div class="form-group ">
                                                <select name="subscription_period" required="" class="form-control "
                                                    id="subscription_period">
                                                    <option value="1">3 Month </option>
                                                    <option value="2">1 Year </option>
                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12  mt-3 mb-2">
                                            <button type="submit"
                                                class="button btn-default primary-button">Submit</button>
                                            <a href="https://test.cryptoscannerpro.com/admin/user/list">
                                                <button type="button"
                                                    class="button btn-default primary-button">Cancel</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="admin-controller mt-3" id="profile-box-04">
                        <form class="form-horizontal" method="post" action="{{ route('ChangeSubscriptionPeriod') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    @if(session('succes'))
                                    <label class="control-label success">{{ session('succes') }}</label>
                                    @endif
                                    <h2> Change Password</h2>
                                    <p> user details </p>
                                </div>
                                <div class="col-md-6 head-right-btns">
                                    <a href="{{route('UserList')}}" class="btn btn-label-brand mt-2"> Back </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12  ">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <input type="hidden" name="_token1"
                                        value="toKLmUNDnGp17R1fyYIZV7ckavnjXWrd2RMF32yC">
                                    <div class="row">
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Name</label>
                                            <input type="text" name="name" value="{{$user->name}}" required=""
                                                class="form-control " id="name" placeholder="Enter Name">
                                            <span class="text-danger"></span>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Email</label>
                                            <input type="email" name="email" required="" class="form-control"
                                                value="{{$user->email}}" id="email" placeholder="Enter email">
                                            <span class="text-danger"></span>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Password</label>
                                            <input type="text" name="password" required=""
                                                value="{{$user->password_string}}" class="form-control " id="pwd"
                                                placeholder="Generate password" readonly="true">
                                            <span class="text-danger"></span>
                                            <button type="button" id="generate">Generate</button>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Subscription period</label>
                                            <div class="form-group ">
                                                <select name="subscription_period" required="" class="form-control "
                                                    id="subscription_period">
                                                    <option value="1">3 Month </option>
                                                    <option value="2">1 Year </option>
                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12  mt-3 mb-2">
                                            <button type="submit"
                                                class="button btn-default primary-button">Submit</button>
                                            <a href="https://test.cryptoscannerpro.com/admin/user/list">
                                                <button type="button"
                                                    class="button btn-default primary-button">Cancel</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>




                    <div class="admin-controller mt-3 " id="profile-box-05">


                        <div class="row">
                            <div class="col-md-6">
                                @if(session('success'))
                                <label class="control-label success">{{ session('success') }}</label>
                                @endif
                                <h2> Subscription</h2>
                                <p> Change subscription details </p>


                            </div>
                            <div class="col-md-6 head-right-btns">
                                <a href="{{route('UserList')}}" class="btn btn-label-brand mt-2"> Back </a>
                            </div>
                        </div>

                        <div class="row">
                            <form class="form-horizontal" method="post" action="{{ route('ChangeSubscription') }}">

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-12  ">

                                    <input type="hidden" name="_token1"
                                        value="toKLmUNDnGp17R1fyYIZV7ckavnjXWrd2RMF32yC">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <div class="row">
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Subscription period</label>
                                            <div class="form-group ">
                                                <select name="subscription_period" required="" class="form-control "
                                                    id="subscription_period">
                                                    @foreach($subscription_period as $sub)
                                                    <option {{($user->sub_period->id==$sub->id) ? 'selected' : ''}}
                                                        value="{{$sub->id}}">{{$sub->text}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6  mt-3">
                                            <label class="control-label">Subscription Type</label>

                                            <div class="form-group ">
                                                <select name="subscription_type" required="" class="form-control "
                                                    id="subscription_period">
                                                    @foreach($user_types as $type)
                                                    <option
                                                        {{($user->subscription_details->id==$type->id) ? 'selected' : ''}}
                                                        value="{{$type->id}}">{{$type->category_type}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger"></span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12  mt-3 mb-2">
                                            <button type="submit"
                                                class="button btn-default primary-button">Submit</button>
                                            <a href="https://test.cryptoscannerpro.com/admin/user/list">
                                                <button type="button"
                                                    class="button btn-default primary-button">Cancel</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>

                    <div class="admin-controller mt-3  " id="profile-box-06">
                        <div class="row">
                            <div class="col-md-6">
                                <h2> IP Log</h2>

                            </div>
                            <div class="col-md-6 head-right-btns">
                                <a href="{{route('UserList')}}" class="btn btn-label-brand mt-2"> Back </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12  ">

                                <div class="row">
                                    <table class="table">

                                        <head>
                                            <tr>
                                                <th>Ip address</th>
                                                <th>login date</th>
                                                <th>last login</th>
                                            </tr>
                                            <tbody>
                                                @foreach($iplog as $key =>$value)
                                                <tr>
                                                    <td>{{$value->ip_adress}}</td>
                                                    <td>{{$value->created_at}}</td>
                                                    <td>{{$user->last_login_at}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </head>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="admin-controller mt-5  " id="profile-box-07">
                        <div class="row">
                            <div class="col-md-6">
                                <h2> Payment History</h2>

                            </div>
                            <div class="col-md-6 head-right-btns">
                                <a href="{{route('UserList')}}" class="btn btn-label-brand mt-2"> Back </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12  ">

                                <div class="row">
                                    <table class="table">

                                        <head>
                                            <tr>
                                                <th>Transaction id</th>
                                                <!--  <th>Transaction id</th> -->
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                            <tbody>
                                                @foreach($payment_details as $key =>$value)
                                                <tr>
                                                    <td>{{$value->txn_id}}</td>
                                                    <!--  <td>{{$value->txn_id}}</td> -->
                                                    <td>{{$value->amount}}</td>


                                                    <td>{{($value->status== 1 && $value->payment_status== 100 ) ? 'Completed' : 'Pending'}}
                                                    </td>

                                                    <td>{{$user->created_at}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </head>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>


                    <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>

                </div>
            </div>








            @section('scripts')
            <script>
            $('#selectall').click(function() {
                $('.selectedId').prop('checked', this.checked);
            });
            $('.selectedId').change(function() {
                var check = ($('.selectedId').filter(":checked").length == $('.selectedId').length);
                $('#selectall').prop("checked", check);
            });
            </script>
            <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#generate').click(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{route('GeneratePassword')}}",
                        type: 'post',
                        success: function(password) {
                            $('#pwd').val(password);
                        }
                    });
                });
                // AJAX request
            });




            // Single menu
            function scrollToSection(event) {
                $('#kt-widget a').removeClass('active');
                $(this).addClass("active");
                event.preventDefault();
                


                var $section = $($(this).attr('href'));
                $('html, body').animate({
                    scrollTop: $section.offset().top - 90
                }, 500);
            }
            $('#kt-widget [data-scroll]').on('click', scrollToSection);
            </script>
            @endsection
            @endsection