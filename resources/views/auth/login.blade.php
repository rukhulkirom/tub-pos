@extends('layouts.auth')

@section('login')
<div class="login-box-body">
    <div class="login-logo">
        <a href="{{ url('/') }}">
            <img src="{{ url($setting->path_logo) }}" alt="logo" width="150">
        </a>
    </div>
    
    <p class="login-box-msg">Sign in to start your session</p>
    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="form-group @error('email') has-error @enderror">
            <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input type="email" name="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
            </div>
            @error('email')
                <span class="help-block">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group @error('password') has-error @enderror">
            <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            @error('password')
                <span class="help-block">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block btn-lg btn-hover">Sign In</button>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .login-box-body {
        background-color: #ffffff;
        border: 1px solid #0d1ca5;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .login-logo {
        margin-bottom: 20px;
    }
    .login-box-msg {
        font-size: 18px;
        color: #333;
    }
    .help-block {
        color: #d9534f;
    }
    .btn-hover {
        transition: background-color 0.3s, transform 0.3s;
    }
    .btn-hover:hover {
        background-color: #0b1680;
        transform: scale(1.05);
    }
</style>
@endsection
