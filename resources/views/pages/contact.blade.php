@extends('layouts.general')

@section('title', 'Contact')

@section('content')
    <div class="masthead clearfix">
        <div class="inner">
            <h3 class="masthead-brand"><a href="{{ url('/') }}">{{ strtoupper(env('APP_NAME')) }}</a></h3>
        </div>
    </div>
    <div class="inner cover">
        <h1 class="cover-heading">Contact</h1>
        <ul style="display: inline-block; text-align: left">
            <li>Twitter:&Tab;<a href="https://twitter.com/soundblr">@soundblr</a></li>
            <li>Tumblr:&Tab;<a href="https://soundblr.tumblr.com/">soundblr</a></li>
            <li>Github:&Tab;<a href="https://github.com/sunflowerfuchs/trntbl-clone">Repo</a>&Tab;&VerticalBar;&Tab;<a href="https://github.com/sunflowerfuchs/trntbl-clone/issues">Issues</a></li>
        </ul>
    </div>
@endsection
