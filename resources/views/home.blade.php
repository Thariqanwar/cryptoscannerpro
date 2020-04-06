@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{route('AddNewUser')}}"><button class="btn btn-primary">ADD NEW USER</button></a>
                    <a href="{{route('UserList')}}"><button class="btn btn-primary">USER LIST</button></a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
