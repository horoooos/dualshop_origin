@extends('layouts.app')

@section('content')
    <div class="login-page">
        <div class="form-container">
            {{ $slot }}
        </div>
    </div>
@endsection
