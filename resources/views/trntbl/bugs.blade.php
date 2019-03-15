@extends('layouts.trntbl')

@section('title', 'Known bugs')

@section('content')
    <div class="masthead clearfix">
        <div class="inner">
            <h3 class="masthead-brand"><a href="{{ url('/') }}">{{ strtoupper(env('APP_NAME')) }}</a></h3>
        </div>
    </div>
    <div class="inner cover">
        <h1 class="cover-heading">Supported sources</h1>
        <ul style="display: inline-block; text-align: left">
            <li>Currently, only audio posts directly from tumblr (not embedded from soundcloud, spotify, etc.) can be played.</li>
        </ul>
        <h1 class="cover-heading">Known bugs</h1>
        <ul style="display: inline-block; text-align: left">
            <li>When you haven't accepted cookies on tumblr yet, the audio files will fail to load</li>
            <li>When disabling shuffle, you start from the beginning of the list</li>
            <li>Play button is hard to hit on some devices</li>
        </ul>
        <h1 class="cover-heading">Planned features</h1>
        <ul style="display: inline-block; text-align: left">
            <li>Graphical changes</li>
            <li>Alternative variant of video posts</li>
        </ul>
    </div>
@endsection
