@extends('layouts.trntbl')

@section('title', 'Known bugs')

@section('content')
    <div class="masthead clearfix">
        <div class="inner">
            <h3 class="masthead-brand"><a href="{{ url('/') }}">{{ strtoupper(env('APP_NAME')) }}</a></h3>
        </div>
    </div>
    <div class="inner cover">
        <h1 class="cover-heading">Known bugs</h1>
        <ul style="display: inline-block; text-align: left">
            <li>Play button is hard to hit on some devices</li>
            <li>Soundcloud posts are currently not playable, and i can't change that until Soundcloud reopens API-applications</li>
        </ul>
        <h1 class="cover-heading">Planned features</h1>
        <ul style="display: inline-block; text-align: left">
            <li>Skip function</li>
            <li>Graphical changes</li>
            <li>Alternative variant of video posts</li>
        </ul>
    </div>
@endsection