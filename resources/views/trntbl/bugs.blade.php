@extends('layouts.trntbl')

@section('title', 'Known bugs')

@section('content')
    <div class="masthead clearfix">
        <div class="inner">
            <h3 class="masthead-brand">{{ strtoupper(env('APP_NAME')) }}</h3>
        </div>
    </div>
    <div class="inner cover">
        <h1 class="cover-heading">Known bugs</h1>
        <ul style="display: inline-block; text-align: left">
            <li>Music starts out too loud</li>
            <li>Play button is hard to hit on some devices</li>
        </ul>
    </div>
@endsection