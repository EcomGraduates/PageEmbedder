@extends('layouts.app')

@section('title', __('Hello World'))

@section('content')
<div class="section-heading">
    {{ __('Hello World') }}
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-12 text-center">
            <div style="margin-top: 100px;">
                <h1 style="font-size: 48px; color: #3f8adb;">Hello World!</h1>
                <p class="text-muted">This is a basic FreeScout module with settings page integration.</p>
            </div>
        </div>
    </div>
</div>
@endsection 