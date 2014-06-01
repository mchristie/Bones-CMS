@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8 offset-md-2">

            @if($error)
                <div class="alert alert-danger">{{$error}}</div>
            @endif

            <form role="form" method="post">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="" />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="" />
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>

            </form>
        </div>
    </div>

@endsection