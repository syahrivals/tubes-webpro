@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
    <div class="card shadow" style="max-width:400px;width:100%;border-radius:18px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <!-- <img src="/favicon.ico" alt="Logo" style="height:48px;"> -->
                <h1 class="mt-2 mb-0" style="font-weight:700;color:var(--primary);">Login</h1>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;font-weight:600;">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection
